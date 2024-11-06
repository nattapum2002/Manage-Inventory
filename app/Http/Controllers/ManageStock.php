<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class managestock extends Controller
{
    //
    public function index()
    {
        $show_per_date = DB::table('product_store')
            ->selectRaw('store_date, COUNT(*) as total_times') // หรือเลือกฟิลด์ที่คุณต้องการ
            ->groupBy('store_date')
            ->get();
        return view('admin.ManageStock.managerecivestock', compact('show_per_date'));
    }

    public function show_slip($date)
    {
        $show_slip = DB::table('product_store')
            ->selectRaw('MAX(product_slip_id) as slip_id,MAX(department) as department , MAX(product_slip_number) as slip_number ,MAX(product_checker) as product_checker,MAX(domestic_checker) as domestic_checker')
            ->groupBy('product_slip_id')
            ->where('store_date', $date)
            ->get();
        // dd($show_slip);
        return view('Admin.ManageStock.manageslipstock', compact('show_slip', 'date'));
    }

    public function show_slip_detail($slip_id)
    {
        $show_detail = DB::table('product_store')
            ->where('product_slip_id', $slip_id)
            ->get();
        return view('Admin.ManageStock.manageslipdetail', compact('show_detail', 'slip_id'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');

        // ดึงข้อมูลเฉพาะฟิลด์ที่ต้องการ เช่น product_name
        $data = DB::table('stock')
            ->select('product_name', 'product_id') // เลือกเฉพาะฟิลด์ product_name
            ->where('product_name', 'like', '%' . $query . '%')
            ->limit(10) // จำกัดผลลัพธ์ 10 รายการ
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ jQuery autocomplete ต้องการ
        $suggestions = [];
        foreach ($data as $item) {
            $suggestions[] = $item->product_name;
        }

        return response()->json($suggestions);
    }

    public function create(Request $request)
    {
        $data = $request->all();
        DB::transaction(function () use ($data) {
            foreach ($data['item_id'] as $key => $value) {
                DB::table('product_store')->insert([
                    'product_slip_id' => $data['slip_id'],
                    'product_slip_number' => $data['slip_number'],
                    'product_id' => $data['item_id'][$key],
                    'amount' => $data['item_amount'][$key],
                    'weight' => $data['item_weight'][$key],
                    'comment' => $data['item_comment'][$key],
                    'department' => $data['department'],
                    'store_date' => $data['date'],
                    'store_time' => $data['time'],
                    'check_status' => 1,
                    'product_checker' => 'นาย ก',
                    'domestic_checker' => 'นาย ข'
                ]);
            }
        });

        return redirect()->route('ManageSlip', $data['date'])->with('success', 'Data saved successfully');
    }
}
