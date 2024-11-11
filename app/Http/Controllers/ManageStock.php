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
            ->selectRaw('store_date, COUNT(DISTINCT(product_slip_number)) as total_slip') // หรือเลือกฟิลด์ที่คุณต้องการ
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
            ->Join('stock', 'product_store.product_id', '=', 'stock.product_id')
            ->where('product_slip_id', $slip_id)
            ->select('product_store.*', 'stock.product_name', 'stock.product_id')
            ->get();
        return view('Admin.ManageStock.manageslipdetail', compact('show_detail', 'slip_id'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');

        // ดึงข้อมูลเฉพาะฟิลด์ที่ต้องการ เช่น product_name และ product_id
        $data = DB::table('stock')
            ->select('product_name', 'product_id') // เลือกเฉพาะฟิลด์ product_name และ product_id
            ->where('product_name', 'like', '%' . $query . '%')
            ->limit(10) // จำกัดผลลัพธ์ 10 รายการ
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ jQuery autocomplete ต้องการ
        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->product_name,  // ใช้ 'label' สำหรับการแสดงผลในรายการ autocomplete
                'value' => $item->product_name,  // ใช้ 'value' สำหรับการเติมในช่อง input
                'id' => $item->product_id        // ส่ง 'id' สำหรับการใช้รหัสสินค้าเพิ่มเติม
            ];
        }

        return response()->json($results);
    }

    public function create(Request $request)
    {
        $request->validate([
            'slip_id' => 'required',
            'slip_number' => 'required',
            'department' => 'required',
            'date' => 'required',
            'time' => 'required',
            'item_name.*' => 'required',
            'item_amount.*' => 'required',
            'item_weight.*' => 'required',
            'product_checker' => 'required||integer',
        ], [
            'slip_id.required' => 'กรุณากรอกรหัสสลิป',
            'slip_number.required' => 'กรุณากรอกหมายเลขสลิป',
            'department.required' => 'กรุณากรอกแผนก',
            'date.required' => 'กรุณากรอกวันที่',
            'time.required' => 'กรุณากรอกเวลา',
            'item_name.*.required' => 'กรุณากรอกชื่อสินค้า',
            'item_amount.*.required' => 'กรุณากรอกจำนวนสินค้า',
            'item_weight.*.required' => 'กรุณากรอกน้ำหนักสินค้า',
            'product_checker.required' => 'กรุณากรอกรหัสผู้ตรวจสินค้า',
            'product_checker.integer' => 'กรุณากรอกรหัสผู้ตรวจสินค้าเป็นตัวเลข',
            'domestic_checker.integer' => 'กรุณากรอกรหัสผู้ตรวจสินค้าเป็นตัวเลข'
        ]);

        $data = $request->all();

        DB::transaction(function () use ($data) {
            foreach ($data['item_id'] as $key => $value) {
                DB::table('product_store')->insert([
                    'product_slip_id' => $data['slip_id'],
                    'product_slip_number' => $data['slip_number'],
                    'product_id' => $data['item_id'][$key],
                    'amount' => $data['item_amount'][$key],
                    'weight' => $data['item_weight'][$key],
                    'department' => $data['department'],
                    'store_date' => $data['date'],
                    'store_time' => $data['time'],
                    'product_checker' => $data['product_checker'],
                    'domestic_checker' => 'N/A',
                    'note' => $data['item_comment'][$key],
                    'status' => 0,
                ]);

                DB::table('stock')->where('product_id', $data['item_id'][$key])->increment('amount', $data['item_amount'][$key]);
                DB::table('stock')->where('product_id', $data['item_id'][$key])->increment('weight', $data['item_weight'][$key]);
            }
        });

        return redirect()->route('Add item')->with('success', 'Data saved successfully');
    }
    public function edit(Request $request)
    {
        $productId = $request->input('product_id');
        $productData = $request->input('product_edit');
        $product_store = DB::table('product_store')->where('id', $productId)->update([
            'department' => $productData['department'],
            'amount' => $productData['amount'],
            'weight' => $productData['weight'],
            'note' => $productData['comment'],
        ]);
        return response()->json([
            "status" => true,
            "data" => '200'
        ]);
    }
}
