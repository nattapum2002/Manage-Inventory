<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ManageImportProduct extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $show_per_date = DB::table('receipt_product')
            ->selectRaw('store_datetime, COUNT(DISTINCT(receipt_slip_number)) as total_slip') // หรือเลือกฟิลด์ที่คุณต้องการ
            ->groupBy('store_datetime')
            ->get();
        return view('admin.ManageStock.managerecivestock', compact('show_per_date'));
    }

    public function show_slip($date)
    {
        $show_slip = DB::table('receipt_product')
            ->join('receipt_product_detail', 'receipt_product.receipt_product_id', '=', 'receipt_product_detail.receipt_product_id')
            ->selectRaw('
                MAX(receipt_product_detail.receipt_product_id) as slip_id,
                MAX(department) as department,
                MAX(receipt_slip_number) as slip_number,
                MAX(product_checker_id) as product_checker,
                MAX(domestic_checker_id) as domestic_checker
            ')
            ->groupBy('product_id', 'status')
            ->whereDate('store_datetime', $date)
            ->get();
        // dd($show_slip);
        return view('Admin.ManageStock.manageslipstock', compact('show_slip', 'date'));
    }
    public function check_slip($id)
    {
        DB::table('receipt_product')
            ->where('id', $id)
            ->update(['status' => 1, 'domestic_checker' => auth()->user()->user_id]);
        $this->sum($id);
        return redirect()->back()->with('success', 'Data check successfully');
    }
    function sum($id)
    {
        $sum = DB::table('receipt_product_detail')
            ->where('product_slip_id', $id)
            ->get();
        foreach ($sum as $item) {
            DB::table('product_stock')->where('product_id', $item->product_id)->increment('quantity', $item->quantity);
            DB::table('product_stock')->where('product_id', $item->product_id)->increment('quantity2', $item->quantity2);
        }
    }
    public function show_slip_detail($slip_id)
    {
        $show_detail = DB::table('receipt_product_detail')
            ->join('product', 'receipt_product_detail.product_id', '=', 'product.product_id')
            ->join('receipt_product', 'receipt_product.receipt_product_id', '=', 'receipt_product_detail.receipt_product_id')
            ->where('receipt_product.receipt_product_id', $slip_id)  // ระบุชื่อตารางที่ชัดเจน
            ->select('receipt_product.*', 'receipt_product_detail.*', 'product.product_description', 'product.*')
            ->get();
        $show_slip = DB::table('receipt_product')
            ->where('receipt_product_id', $slip_id)
            ->first();
        // dd($show_slip);
        return view('Admin.ManageStock.manageslipdetail', compact('show_detail', 'slip_id', 'show_slip'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');
        // $room = $request->get('room');
        // ดึงข้อมูลเฉพาะฟิลด์ที่ต้องการ เช่น product_name และ product_id
        $data = DB::table('product')
            ->select('product_description', 'product_number', 'product_um', 'product_um2', 'product_id') // เลือกเฉพาะฟิลด์ product_name และ product_id
            ->where('product_description', 'like', '%' . $query . '%')
            ->limit(10) // จำกัดผลลัพธ์ 10 รายการ
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ jQuery autocomplete ต้องการ
        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->product_description,  // ใช้ 'label' สำหรับการแสดงผลในรายการ autocomplete
                'value' => $item->product_description,  // ใช้ 'value' สำหรับการเติมในช่อง input
                'product_um' => $item->product_um,
                'product_um2' => $item->product_um2,
                'product_no' => $item->product_number,       // ส่ง 'id' สำหรับการใช้รหัสสินค้าเพิ่มเติม
                'id' => $item->product_id
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
            'room' => 'required',
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
        // dd($data);
        DB::transaction(function () use ($data) {
            $id = DB::table('receipt_product')->insertGetId([
                'product_slip_id' => $data['slip_id'],
                'receipt_slip_number' => $data['slip_number'],
                'department' => $data['department'],
                'store_datetime' => $data['date'],
                'store_time' => $data['time'],
                'product_checker' => $data['product_checker'],
                'domestic_checker' => 'N/A',
                'shift_id'   => 1,
                'status' => 0,
            ]);
            foreach ($data['product_id'] as $key => $value) {
                DB::table('receipt_product_detail')->insert([
                    'product_slip_id' => $id,
                    'product_id' => $data['save_item_id'][$key],
                    'quantity' => $data['item_quantity'][$key],
                    'quantity2' => $data['item_quantity2'][$key],
                    'note' => $data['item_comment'][$key],
                    'status' => 0,
                ]);

                // DB::table('product_stock')->where('product_id', $data['save_item_id'][$key])->increment('quantity', $data['item_quantity'][$key]);
                // DB::table('product_stock')->where('product_id', $data['save_item_id'][$key])->increment('quantity2', $data['item_quantity2'][$key]);
            }
        });

        return redirect()->route('Add item')->with('success', 'Data saved successfully');
    }
    public function edit(Request $request)
    {
        $productId = $request->input('product_id');
        $productData = $request->input('product_edit');
        $productCode = $request->input('product_code');
        $receipt_product_detail = DB::table('receipt_product_detail')->where('id', $productId)->update([
            'quantity' => $productData['quantity'],
            'quantity2' => $productData['quantity2'],
            'note' => $productData['comment'],
        ]);
        $receipt_product = DB::table('receipt_product')->where('id', $productId)->update([
            'department' => $productData['department'],
        ]);
        $this->calculateStock($productCode, $productData['quantity'], $productData['quantity2']);
        return response()->json([
            "status" => true,
            "data" => $productCode
        ]);
    }

    public function calculateStock($id, $quantity, $quantity2)
    {
        $getData = DB::table('product_stock')->where('product_id', $id)->first();

        $newquantity = 0;
        $newquantity2 = 0;

        if ($getData->quantity > $quantity) {
            $newquantity = $getData->quantity - $quantity;
        } else if ($getData->quantity < $quantity) {
            $newquantity = $quantity - $getData->quantity;
            $newquantity = $getData->quantity + $newquantity;
        } else {
            $newquantity = $quantity;
        }

        if ($getData->quantity2 > $quantity2) {
            $newquantity2 = $getData->quantity2 - $quantity2;
        } else if ($getData->quantity2 < $quantity2) {
            $newquantity2 = $quantity2 - $getData->quantity2;
            $newquantity2 = $getData->weight + $newquantity2;
        } else {
            $newquantity2 = $quantity2;
        }
        // อัปเดตข้อมูลในตาราง product_stock
        DB::table('product_stock')->where('product_id', $id)->update([
            'quantity' => $newquantity,
            'quantity2' => $newquantity2,
        ]);
        DB::table('receipt_product_detail')->where('product_id', $id)->update([
            'quantity' => $newquantity,
            'quantity2' => $newquantity2,
        ]);
    }
}