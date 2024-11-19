<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LockController extends Controller
{
    public function index()
    {
        $CustomerOrders = DB::table('customer_order')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->join('lock_team', 'customer_order.team_id', '=', 'lock_team.team_id')
            ->get();
        // dd($CustomerOrders);
        return view('Admin.ManageLockStock.managelockstock', compact('CustomerOrders'));
    }

    public function DetailLockStock($order_number)
    {
        $CustomerOrders = DB::table('customer_order')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->join('lock_team', 'customer_order.team_id', '=', 'lock_team.team_id')
            ->join('customer_order_detail', 'customer_order.order_number', '=', 'customer_order_detail.order_number')
            ->join('product_detail', 'customer_order_detail.product_id', '=', 'product_detail.product_id')
            ->where('customer_order.order_number', '=', $order_number)
            ->get();
        $LockTeams = DB::table('lock_team')
            ->join('lock_team_user', 'lock_team.team_id', '=', 'lock_team_user.team_id')
            ->join('users', 'lock_team_user.user_id', '=', 'users.user_id')
            ->get();
        $Pallets = DB::table('pallet')->get();
        return view('Admin.ManageLockStock.DetailLockStock', compact('CustomerOrders', 'LockTeams', 'Pallets'));
    }

    public function AddPallet($order_number)
    {
        $Pallets = DB::table('customer_order')
            ->join('pallet', 'customer_order.order_number', '=', 'customer_order.order_number')
            ->join('pallet_order', 'pallet.pallet_id', '=', 'pallet_order.pallet_id')
            ->join('customer_order_detail', 'pallet_order.product_id', '=', 'customer_order_detail.product_id')
            ->where('customer_order.order_number', '=', $order_number)
            ->get();
        return view('Admin.ManageLockStock.AddPallet', compact('Pallets', 'order_number'));
    }

    public function SavePallet($order_number, Request $request)
    {
        $data = $request->all();
        DB::transaction(function () use ($data, $order_number) {
            DB::table('pallet')->updateOrInsert(
                ['pallet_id' => $data['pallet_id']], // Condition to check for existing record
                [
                    'pallet_no' => $data['pallet_no'], // Data to update/insert
                    'room' => $data['room'],
                    'order_number' => $order_number,
                    'note' => $data['note'] ?? null,
                    'status' => 0,
                    'created_at' => now(),
                ]
            );

            DB::table('pallet_order')->where('pallet_id', $data['pallet_id'])->delete();

            foreach ($data['product_id'] as $key => $value) {
                DB::table('pallet_order')->insert([
                    'pallet_id' => $data['pallet_id'],
                    'product_id' => $data['product_id'][$key],
                ]);
            }
        });
        return redirect()->back()->with('success', 'Data saved successfully');
    }

    public function DetailPallets($order_number, $pallet_id)
    {
        $Pallets = DB::table('customer_order')
            ->join('pallet', 'customer_order.order_number', '=', 'customer_order.order_number')
            ->join('pallet_order', 'pallet.pallet_id', '=', 'pallet_order.pallet_id')
            ->join('customer_order_detail', 'pallet_order.product_id', '=', 'customer_order_detail.product_id')
            ->where('customer_order.order_number', '=', $order_number)
            ->where('pallet.pallet_id', '=', $pallet_id)
            ->orderBy('customer_order.order_number', 'asc')
            ->get();
        return view('Admin.ManageLockStock.DetailPellets', compact('Pallets'));
    }

    public function AutoCompleteAddPallet(Request $request, $order_number)
    {
        $query = $request->get('query');
        // ดึงข้อมูลเฉพาะฟิลด์ที่ต้องการ เช่น product_name และ product_id
        $data = DB::table('customer_order_detail')
            ->join('product_detail', 'customer_order_detail.product_id', '=', 'product_detail.product_id')
            ->select('customer_order_detail.product_id', 'product_detail.product_name', 'ordered_quantity', 'product_uom', 'ordered_quantity2', 'product_uom2', 'bag_color', 'product_detail.note', 'product_detail.status') // เลือกเฉพาะฟิลด์ product_name และ product_id
            ->where('product_name', 'like', '%' . $query . '%')
            ->where('order_number', '=', $order_number)
            ->distinct()
            ->limit(10) // จำกัดผลลัพธ์ 10 รายการ
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ jQuery autocomplete ต้องการ
        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->product_name,
                'value' => $item->product_name,
                'product_id' => $item->product_id,
                'ordered_quantity' => $item->ordered_quantity,
                'bag_color' => $item->bag_color,
                'note' => $item->note,
                'status' => $item->status,
            ];
        }

        return response()->json($results);
    }
}
