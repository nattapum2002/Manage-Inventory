<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class ProductReceiptPlanController extends Controller
{
    public function index()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $ProductReceiptPlans = DB::table('product_receipt_plan')
            ->join('work_shift', 'product_receipt_plan.shift_id', '=', 'work_shift.shift_id')
            ->get();

        $shifts = DB::table('work_shift')->select('shift_id', 'shift_name')->distinct()->get();

        $ProductDetail = DB::table('product')->select('item_id', 'item_desc1')->distinct()->get();

        return view('Admin.ProductReceiptPlan.ProductReceiptPlan', compact('ProductReceiptPlans', 'shifts', 'ProductDetail'));
    }

    public function GetShifts(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json([
                'status' => 'error',
                'message' => 'วันที่ไม่ถูกต้อง',
                'shifts' => []
            ]);
        }

        // ดึงรายการกะที่ตรงกับวันที่ที่เลือก
        $shifts = DB::table('work_shift')->whereDate('date', $date)->get(['shift_id', 'shift_name']);

        return response()->json([
            'status' => 'success',
            'shifts' => $shifts
        ]);
    }

    public function AddProductReceiptPlan(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        // ตรวจสอบว่าไฟล์ถูกอัปโหลดและมีประเภทที่ถูกต้อง
        $request->validate([
            // 'product_receipt_plan_id' => 'required',
            'date' => 'required',
            'shift_id' => 'required',
            'note' => 'nullable',
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $filteredRequest = $request->except(['_token', 'file']);
        // จัดเก็บไฟล์ในไดเรกทอรีชั่วคราว
        $path = $request->file('file')->store('temp');
        $filePath = storage_path('app/' . $path);

        // อ่านข้อมูลจากไฟล์ Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $detailHeader = $data[1];
        $rows = array_slice($data, 2);

        $ProductReceiptPlans = DB::table('product_receipt_plan')
            ->join('product_receipt_plan_detail', 'product_receipt_plan.product_receipt_plan_id', '=', 'product_receipt_plan_detail.product_receipt_plan_id')
            ->get();

        $shift = DB::table('work_shift')->select('shift_id', 'shift_name')->where('shift_id', $filteredRequest['shift_id'])->first();

        $ProductDetail = DB::table('product')->select('item_id', 'item_desc1')->distinct()->get();

        // ส่งข้อมูลไปยัง View เพื่อแสดงพรีวิว
        return view('Admin.ProductReceiptPlan.AddProductReceiptPlan', [
            'detailHeader' => $detailHeader,
            'rows' => $rows,
            'filePath' => $path, // ส่งพาธไฟล์ไปเพื่อให้ลบได้ภายหลัง
            'ProductReceiptPlans' => $ProductReceiptPlans,
            'filteredRequest' => $filteredRequest,
            'shift' => $shift,
            'ProductDetail' => $ProductDetail
        ]);
    }

    public function SaveAddProductReceiptPlan(Request $request)
    {
        try {
            $shift_id = $request->input('shift_id');
            $date = $request->input('date');

            $shift_data = DB::table('work_shift')->where('shift_id', $shift_id)->first();

            $product_receipt_name = 'กะ ' . $shift_data->shift_name . ' : ' . (new DateTime($date))->format('d/m/Y');
            $product_receipt_plan_id = $request->input('product_receipt_plan_id') ?? Str::uuid();

            DB::table('product_receipt_plan')->insert([
                'product_receipt_plan_id' => $product_receipt_plan_id,
                'product_receipt_plan_name' => $product_receipt_name,
                'date' => $date,
                'shift_id' => $shift_id,
                'note' => $request->input('note'),
                'status' => $request->input('status') ?? true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // รับข้อมูลไฟล์ชั่วคราวจากฟอร์ม
            $filePath = $request->input('filePath');
            $fullFilePath = storage_path('app/' . $filePath);

            if (Storage::exists($filePath)) {
                $spreadsheet = IOFactory::load($fullFilePath);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                $rows = array_slice($data, 2);

                $insertData = [];

                foreach ($rows as $row) {
                    if (!isset($row[0]) || empty($row[0])) {
                        continue; // ข้ามแถวที่ไม่มี product_id
                    }

                    $insertData[] = [
                        'product_id' => $row[0],
                        'weight' => $row[2] ?? 0,
                        'increase_weight' => $row[3] ?? 0,
                        'reduce_weight' => $row[4] ?? 0,
                        'total_weight' => $row[5] ?? 0,
                        'product_receipt_plan_id' => $product_receipt_plan_id,
                        'note' => $row[6] ?? null,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($insertData)) {
                    $chunks = array_chunk($insertData, 100);

                    foreach ($chunks as $chunk) {
                        DB::table('product_receipt_plan_detail')->insert($chunk);
                    }
                }

                Storage::delete($filePath);
            }

            return redirect()->route('ProductReceiptPlan')->with('success', 'Data saved successfully.');
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An unexpected error occurred. Please try again later.",
                "error" => $e->getMessage(),
            ], 500);
        }
    }


    public function EditProductReceiptPlan($product_receipt_plan_id)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $ProductReceiptPlans = DB::table('product_receipt_plan')
            ->join('work_shift', 'product_receipt_plan.shift_id', '=', 'work_shift.shift_id')
            ->select('work_shift.shift_id', 'work_shift.shift_name', 'product_receipt_plan.date', 'product_receipt_plan.note')
            ->where('product_receipt_plan.product_receipt_plan_id', $product_receipt_plan_id)
            ->first();

        $ProductReceiptPlansDetails = DB::table('product_receipt_plan_detail')
            ->join('product', 'product_receipt_plan_detail.product_id', '=', 'product.item_id')
            ->where('product_receipt_plan_id', $product_receipt_plan_id)
            ->select('product_receipt_plan_detail.*', 'product.item_desc1')
            ->get();

        $product_details = DB::table('product')->select('item_id', 'item_desc1')->distinct()->get();

        $shifts = DB::table('work_shift')->select('shift_id', 'shift_name')->where('shift_name', '!=', $ProductReceiptPlans->shift_name)->distinct()->get();

        return view('Admin.ProductReceiptPlan.EditProductReceiptPlan', compact(
            'ProductReceiptPlans',
            'ProductReceiptPlansDetails',
            'product_details',
            'shifts',
            'product_receipt_plan_id'
        ));
    }

    public function AddProduct(Request $request)
    {
        try {
            $data = $request->all(); // รับข้อมูลทั้งหมดจากฟอร์ม

            DB::transaction(function () use ($data) {
                $now = now(); // ลดจำนวนการเรียก now()
                $insertData = []; // สร้างอาร์เรย์สำหรับเก็บข้อมูลที่ต้องบันทึก

                foreach ($data['product_id'] as $key => $productId) {
                    $insertData[] = [
                        'product_id' => $productId,
                        'product_receipt_plan_id' => $data['product_receipt_plan_id'],
                        'weight' => $data['product_quantity'][$key] ?? 0,
                        'increase_weight' => $data['increase_quantity'][$key] ?? 0,
                        'reduce_weight' => $data['reduce_quantity'][$key] ?? 0,
                        'total_weight' => $data['total_quantity'][$key] ?? 0,
                        'note' => $data['note'][$key] ?? 'N/A',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // ทำการบันทึกข้อมูลทั้งหมดในครั้งเดียว
                DB::table('product_receipt_plan_detail')->upsert(
                    $insertData,
                    ['product_id', 'product_receipt_plan_id'], // ระบุคอลัมน์ที่ใช้ในการตรวจสอบ
                    ['weight', 'increase_weight', 'reduce_weight', 'total_weight', 'note', 'updated_at'] // คอลัมน์ที่ต้องการอัปเดตหากพบข้อมูลซ้ำ
                );
            });

            return redirect()->route('EditProductReceiptPlan', $data['product_receipt_plan_id'])
                ->with('success', 'Products added/updated successfully.');
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An unexpected error occurred. Please try again later.",
                "error" => $e->getMessage(),
            ], 500);
        }
    }


    public function SaveEditDetail(Request $request)
    {
        $request->validate([
            'product_receipt_plan_id' => 'required|string|max:255',
            'shift_id' => 'required',
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            $shift_data = DB::table('work_shift')->where('shift_id', $request->input('shift_id'))->first();

            $product_receipt_name = 'กะ ' . $shift_data->shift_name . ' : ' . (new DateTime($request->input('date')))->format('d/m/Y');

            DB::table('product_receipt_plan')
                ->where('product_receipt_plan_id', $request->input('product_receipt_plan_id'))
                ->update([
                    'product_receipt_plan_name' => $product_receipt_name,
                    'shift_id' => $request->input('shift_id'),
                    'date' => $request->input('date'),
                    'note' => $request->input('note'),
                    'updated_at' => now(),
                ]);

            return redirect()->route('EditProductReceiptPlan', $request->input('product_receipt_plan_id'))
                ->with('success', 'Updated successfully.');
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An unexpected error occurred. Please try again later.",
                "error" => $e->getMessage(),
            ], 500);
        }
    }


    public function SaveEditProduct(Request $request)
    {
        try {
            if ($request->input('product_id') === $request->input('old_product_id')) {
                // อัปเดตข้อมูลเมื่อ product_id เดิมยังคงไม่เปลี่ยนแปลง
                DB::table('product_receipt_plan_detail')
                    ->where('product_receipt_plan_id', $request->product_receipt_plan_id)
                    ->where('product_id', $request->product_edit['product_id'])
                    ->update([
                        'weight' => $request->product_edit['product_quantity'],
                        'increase_weight' => $request->product_edit['increase_quantity'],
                        'reduce_weight' => $request->product_edit['reduce_quantity'],
                        'total_weight' => $request->product_edit['total_quantity'],
                        'note' => $request->product_edit['note'],
                        'updated_at' => now(),
                    ]);
            } else {
                // ลบข้อมูลเก่าและเพิ่มข้อมูลใหม่เมื่อ product_id เปลี่ยนแปลง
                DB::table('product_receipt_plan_detail')
                    ->where('product_receipt_plan_id', $request->product_receipt_plan_id)
                    ->where('product_id', $request->product_edit['old_product_id'])
                    ->delete();

                DB::table('product_receipt_plan_detail')->insert([
                    'product_receipt_plan_id' => $request->product_receipt_plan_id,
                    'product_id' => $request->product_edit['product_id'],
                    'weight' => $request->product_edit['product_quantity'],
                    'increase_weight' => $request->product_edit['increase_quantity'],
                    'reduce_weight' => $request->product_edit['reduce_quantity'],
                    'total_weight' => $request->product_edit['total_quantity'],
                    'note' => $request->product_edit['note'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                "status" => true,
                "message" => "The shift assignment has been updated successfully.",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An unexpected error occurred. Please try again later.",
                "error" => $e->getMessage(),
            ], 500);
        }
    }


    public function AutocompleteProduct(Request $request)
    {
        $query = $request->input('query');

        // ค้นหาข้อมูลจากตาราง product_detail
        $data = DB::table('product')
            ->select('item_id', 'item_desc1') // เลือกฟิลด์ที่ต้องการ
            ->where('item_id', 'like', '%' . $query . '%') // ค้นหาข้อมูล
            ->distinct()
            ->limit(10) // จำกัดผลลัพธ์ 10 รายการ
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ jQuery Autocomplete ต้องการ
        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->item_id . ' - ' . $item->item_desc1,
                'value' => $item->item_id,
                'product_name' => $item->item_desc1,
            ];
        }

        return response()->json($results); // ส่งข้อมูลกลับในรูปแบบ JSON
    }
}