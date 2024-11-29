<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlipController extends Controller
{
    public function TransferSlip()
    {
        return view('Admin.Slip.TransferSlip');
    }

    public function AddTransferSlip(Request $request)
    {
        $date = $request->input('date');

        $product_receipt_plans = DB::table('product_receipt_plan')
            ->join('product_receipt_plan_detail', 'product_receipt_plan.product_receipt_plan_id', '=', 'product_receipt_plan_detail.product_receipt_plan_id')
            ->join('product', 'product_receipt_plan_detail.product_id', '=', 'product.item_id')
            ->where('product_receipt_plan.date', $date)
            ->where('product_receipt_plan.status', false)
            ->where('product_receipt_plan_detail.status', false)
            ->select('product_receipt_plan_detail.product_id', 'product_receipt_plan_detail.total_weight', 'product.item_desc1', 'product.item_um', 'product.item_um2')
            ->distinct()
            ->get();

        $product_stores = DB::table('product_store')
            ->join('product_store_detail', 'product_store.product_slip_id', '=', 'product_store_detail.product_slip_id')
            ->where('product_store.store_date', $date)
            ->selectRaw('
            product_store_detail.product_id,
            SUM(product_store_detail.quantity) as total_quantity,
            SUM(product_store_detail.quantity2) as total_quantity2
            ')
            ->groupBy('product_store_detail.product_id')
            ->get();

        $productStoresMapped = [];
        foreach ($product_stores as $store) {
            $productStoresMapped[$store->product_id] = [
                'total_quantity' => $store->total_quantity,
                'total_quantity2' => $store->total_quantity2,
            ];
        }

        $mergedData = [];
        foreach ($product_receipt_plans as $plan) {
            $productId = $plan->product_id;

            $mergedItem = [
                'product_id' => $productId,
                'item_desc1' => $plan->item_desc1,
                'total_weight' => $plan->total_weight,
            ];

            if (isset($productStoresMapped[$productId])) {
                $mergedItem['total_quantity'] = $productStoresMapped[$productId]['total_quantity'];
                $mergedItem['total_quantity2'] = $productStoresMapped[$productId]['total_quantity2'];
            } else {
                $mergedItem['total_quantity'] = 0;
                $mergedItem['total_quantity2'] = 0;
            }

            if ($plan->item_um == 'Kg') {
                $mergedItem += [
                    'item_um' => $plan->item_um,
                    'total_sum' => $plan->total_weight - $mergedItem['total_quantity'],
                ];
            } else {
                $mergedItem += [
                    'item_um' => $plan->item_um2,
                    'total_sum' => $plan->total_weight - $mergedItem['total_quantity2'],
                ];
            }

            $mergedData[] = $mergedItem;
        }

        return view('Admin.Slip.AddTransferSlip', compact(
            'request',
            'mergedData',
        ));
    }

    public function SaveAddTransferSlip(Request $request)
    {
        $shift = ShiftController::AutoSelectShift($request->input('date'), $request->input('time'));

        $quantities = $request->input('quantity', []);
        $notes = $request->input('note', []);

        DB::table('product_store')->Insert(
            [
                'product_slip_id' => $request->input('slip_id'),
                'product_slip_number' => $request->input('slip_number'),
                'department' => $request->input('department'),
                'store_date' => $request->input('date'),
                'store_time' => $request->input('time'),
                'domestic_checker' => auth()->user()->user_id,
                'shift_id' => $shift['shift_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        foreach ($quantities as $productId => $quantity) {
            if ((float) $quantity > 0) {
                DB::table('product_store_detail')->Insert(
                    [
                        'product_id' => $productId,
                        'product_slip_id' => $request->input('slip_id'),
                        'quantity' => $quantity,
                        'quantity2' => null, // ปรับ logic หาก quantity2 มีค่าที่ต้องคำนวณ
                        'note' => $notes[$productId] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        return view('Admin.Slip.TransferSlip')->with('success', 'Transfer Slip has been saved successfully.');
    }

    public function AutoCompleteSlip(Request $request)
    {
        $query = $request->input('query');
        $date = $request->input('date');

        if ($request->input('type') == 'TransferSlip') {
            $data = DB::table('product_receipt_plan')
                ->join('product_receipt_plan_detail', 'product_receipt_plan.product_receipt_plan_id', '=', 'product_receipt_plan_detail.product_receipt_plan_id')
                ->join('product', 'product_receipt_plan.product_id', '=', 'product.item_id')
                ->where('product_receipt_plan.date', $date)
                ->where('item_id', 'like', '%' . $query . '%')
                ->select('product_receipt_plan_detail.product_id', 'item_desc1')
                ->distinct()
                ->limit(10)
                ->get();

            $results = [];
            foreach ($data as $item) {
                $results[] = [
                    'label' => $item->item_id . ' - ' . $item->item_desc1,
                    'value' => $item->item_id,
                    'product_name' => $item->item_desc1,
                ];
            }
        }

        return response()->json($results);
    }
}
