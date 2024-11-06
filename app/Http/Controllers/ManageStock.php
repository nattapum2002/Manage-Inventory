<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class managestock extends Controller
{
    //
    public function index(){
        $show_per_date = DB::table('product_store')
            ->selectRaw('store_date, COUNT(*) as total_times') // หรือเลือกฟิลด์ที่คุณต้องการ
            ->groupBy('store_date')
            ->get();
        return view('admin.ManageStock.managerecivestock', compact('show_per_date'));
    }

    public function show_slip($date){
        $show_slip = DB::table('product_store')
            ->selectRaw('MAX(product_slip_id) as slip_id')
            ->groupBy('product_slip_id')
            ->where('store_date', $date)
            ->get();
        // dd($show_slip);
        return view('Admin.ManageStock.manageslipstock', compact('show_slip'));
    }

    public function create(Request $request){
        $data = $request->all();
        DB::transaction(function() use ($data) {
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
