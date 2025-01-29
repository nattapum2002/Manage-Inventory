<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReceiptProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function GetReceiptPlan($date, $shift_id)
    {
        return DB::table('receipt_plan')
            ->join('shift', 'receipt_plan.shift_id', '=', 'shift.shift_id')
            ->join('shift_time', 'shift.shift_time_id', '=', 'shift_time.shift_time_id')
            ->join('receipt_plan_detail', 'receipt_plan.receipt_plan_id', '=', 'receipt_plan_detail.receipt_plan_id')
            ->join('product', 'receipt_plan_detail.product_id', '=', 'product.product_id')
            ->leftJoin('warehouse', 'product.warehouse_id', '=', 'warehouse.id')
            ->select(
                'receipt_plan.receipt_plan_id',
                'receipt_plan.date',
                'shift_time.shift_name',
                'product.product_id',
                'product.product_number',
                'product.product_description',
                'receipt_plan_detail.total_weight',
                'warehouse.warehouse_name as warehouse'
            )
            ->whereDate('receipt_plan.date', $date)
            ->where('shift.shift_time_id', $shift_id)
            ->get();
    }

    // private function GetProductTransaction($date, $transaction_type, $product_id)
    // {
    //     return DB::table('product_transactions')
    //         ->join('product', 'product_transactions.product_id', '=', 'product.product_id')
    //         ->selectRaw('
    //         product_transactions.product_id,
    //         SUM(product_transactions.quantity) as quantity
    //         ')
    //         ->whereDate('product_transactions.datetime', $date)
    //         ->where('product_transactions.transaction_type', $transaction_type)
    //         ->where('product_transactions.product_id', $product_id)
    //         ->groupBy('product_transactions.product_id')
    //         ->first();
    // }

    private function GetProductTransaction($date, $transaction_type, $product_id, $shift_time_id)
    {
        $shift_time = DB::table('shift_time')->where('shift_time_id', $shift_time_id)->first();
        return DB::table('product_transactions')
            ->join('product', 'product_transactions.product_id', '=', 'product.product_id')
            ->selectRaw('
                product_transactions.product_id,
                SUM(product_transactions.quantity) as quantity
            ')
            ->where(function ($query) use ($date, $shift_time) {
                $query->whereTime('product_transactions.datetime', '>=', $shift_time->start_shift)
                    ->whereDate('product_transactions.datetime', $date) // วันที่เดียวกัน
                    ->orWhere(function ($query) use ($date, $shift_time) {
                        $query->whereTime('product_transactions.datetime', '<=', $shift_time->end_shift)
                            ->whereDate('product_transactions.datetime', '=', Carbon::parse($date)->addDay()->toDateString()); // วันถัดไป
                    });
            })
            ->where('product_transactions.transaction_type', $transaction_type)
            ->where('product_transactions.product_id', $product_id)
            ->groupBy('product_transactions.product_id')
            ->first();
    }


    public function ReceiptPlanFilter(Request $request)
    {
        // ดึงข้อมูลแผนการรับสินค้า
        $ReceiptPlanFilter = $this->GetReceiptPlan($request->input('date'), $request->input('shift'));

        // ประมวลผลข้อมูลและเพิ่ม remaining_quantity
        $ReceiptPlanFilter = $ReceiptPlanFilter->map(function ($receiptPlan) use ($request) {
            $productTransactions = $this->GetProductTransaction($request->input('date'), 'IN', $receiptPlan->product_id, $request->input('shift'));

            // คำนวณ remaining_quantity โดยการลบ total_weight ด้วย quantity
            $remainingQuantity = $productTransactions ? $receiptPlan->total_weight - $productTransactions->quantity : $receiptPlan->total_weight - 0;

            // เพิ่ม remaining_quantity เข้าไปในผลลัพธ์
            $receiptPlan->remaining_quantity = $remainingQuantity;

            return $receiptPlan;
        })->filter(function ($receiptPlan) use ($request) {
            // กรองข้อมูลออกถ้า remaining_quantity เท่ากับ 0 (ยกเว้น ShowAll = 1)
            if ($request->input('ShowAll', 0) == 1) {
                return true; // แสดงข้อมูลทั้งหมด
            }
            return $receiptPlan->remaining_quantity != 0; // กรองเฉพาะข้อมูลที่ remaining_quantity ไม่เท่ากับ 0
        });

        $productTransactions = $this->GetProductTransaction($request->input('date'), 'IN', '10900', 1);

        return response()->json([
            'status' => 'success',
            'ReceiptPlanFilter' => $ReceiptPlanFilter->isEmpty() ? [] : $ReceiptPlanFilter->values(),
            'productTransactions' => $productTransactions,
            'ShowAll' => $request->input('ShowAll'),
        ]);
    }


    public function index(Request $request)
    {
        $date = $request->input('date') ?? now()->format('Y-m-d');

        $ReceiptPlan = $this->GetReceiptPlan($date, 1);

        return view('Admin.ReceiptProduct.ReceiptProduct', compact('ReceiptPlan', 'date'));
    }

    public function SaveReceiptProduct(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'shift_id' => 'required',
            'receiptSlipNumber' => 'required',
            'department' => 'nullable|string',
            'productCheckerId' => 'nullable',
            'domesticCheckerId' => 'nullable',
            'receiptData' => 'required|array',
            'receiptData.*.receipt_quantity' => 'required|numeric|min:1',
            'receiptData.*.receipt_quantity2' => 'required|numeric|min:1',
            'receiptData.*.product_id' => 'required|distinct',
            'receiptData.*.note' => 'nullable|string',
            'receiptData.*.warehouse' => 'nullable|string',
        ]);

        try {
            $receiptProductId = Str::uuid();

            DB::transaction(function () use ($validated, $receiptProductId) {
                $timestamp = now();

                // บันทึกข้อมูล receipt_product
                DB::table('receipt_product')->insert([
                    'receipt_product_id' => $receiptProductId,
                    'receipt_slip_number' => $validated['receiptSlipNumber'],
                    'store_datetime' => $timestamp,
                    'department' => $validated['department'],
                    'product_checker_id' => $validated['productCheckerId'],
                    'domestic_checker_id' => $validated['domesticCheckerId'],
                    'status' => 1,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);

                // เตรียมข้อมูล receipt_product_detail
                $insertData = collect($validated['receiptData'])->map(function ($item) use ($receiptProductId) {
                    return [
                        'receipt_product_id' => $receiptProductId,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['receipt_quantity'],
                        'quantity2' => $item['receipt_quantity2'],
                        'note' => $item['note'] ?? null,
                    ];
                })->toArray();

                foreach (array_chunk($insertData, 100) as $chunk) {
                    DB::table('receipt_product_detail')->insert($chunk);
                }

                $insertTransactions = collect($validated['receiptData'])->map(function ($item) use ($timestamp, $validated) {
                    return [
                        'transaction_id' => Str::uuid(),
                        'product_id' => $item['product_id'],
                        'quantity' => $item['receipt_quantity'],
                        'quantity2' => $item['receipt_quantity2'],
                        'transaction_type' => 'IN',
                        'warehouse_id' => $item['warehouse'],
                        'department' => $validated['department'],
                        'datetime' => $timestamp,
                    ];
                })->toArray();

                foreach (array_chunk($insertTransactions, 100) as $chunk) {
                    DB::table('product_transactions')->insert($chunk);
                }

                $insertStock = collect($validated['receiptData'])->map(function ($item) use ($timestamp) {
                    return [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['receipt_quantity'],
                        'quantity2' => $item['receipt_quantity2'],
                        'updated_at' => $timestamp,
                    ];
                })->toArray();

                foreach ($insertStock as $product_stock) {
                    $exists = DB::table('product_stock')
                        ->where('product_id', $product_stock['product_id'])
                        ->exists();

                    if ($exists) {
                        // อัปเดตข้อมูลโดยเพิ่มค่า
                        DB::table('product_stock')
                            ->where('product_id', $product_stock['product_id'])
                            ->update([
                                'quantity' => DB::raw("quantity + {$product_stock['quantity']}"),
                                'quantity2' => DB::raw("quantity2 + {$product_stock['quantity2']}"),
                                'updated_at' => $product_stock['updated_at'],
                            ]);
                    } else {
                        // เพิ่มข้อมูลใหม่
                        DB::table('product_stock')->insert([
                            'product_id' => $product_stock['product_id'],
                            'quantity' => $product_stock['quantity'],
                            'quantity2' => $product_stock['quantity2'],
                            'updated_at' => $product_stock['updated_at'],
                        ]);
                    }
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'บันทึกข้อมูลสำเร็จ',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล' . $e->getMessage(),
            ]);
        }
    }
}