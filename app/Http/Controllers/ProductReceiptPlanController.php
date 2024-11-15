<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductReceiptPlanController extends Controller
{
    public function index()
    {
        $ProductReceiptPlans = DB::table('product_receipt_plan')
            ->join('work_shift', 'product_receipt_plan.shift_id', '=', 'work_shift.shift_id')
            // ->join('product_receipt_plan_detail', 'product_receipt_plan.product_receipt_plan_id', '=', 'product_receipt_plan_detail.product_receipt_plan_id')
            ->get();

        return view('Admin.ProductReceiptPlan.ProductReceiptPlan', compact('ProductReceiptPlans'));
    }

    public function AddProductReceiptPlan(Request $request)
    {
        // ตรวจสอบว่าไฟล์ถูกอัปโหลดและมีประเภทที่ถูกต้อง
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

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

        $shifts = DB::table('work_shift')->select('shift_id', 'shift_name')->distinct()->get();

        $ProductDetail = DB::table('product_detail')->select('product_id', 'product_name')->distinct()->get();

        // ส่งข้อมูลไปยัง View เพื่อแสดงพรีวิว
        return view('Admin.ProductReceiptPlan.AddProductReceiptPlan', [
            'detailHeader' => $detailHeader,
            'rows' => $rows,
            'filePath' => $path, // ส่งพาธไฟล์ไปเพื่อให้ลบได้ภายหลัง
            'ProductReceiptPlans' => $ProductReceiptPlans,
            'shifts' => $shifts,
            'ProductDetail' => $ProductDetail
        ]);
    }

    public function SaveProductReceiptPlan(Request $request)
    {
        $shift_id = $request->input('shift_id');
        $date = $request->input('date');

        $shift_data = DB::table('work_shift')->where('shift_id', $shift_id)->first();
        $product_receipt_name = 'กะ ' . $shift_data->shift_name . ' : ' . (new DateTime($date))->format('d/m/Y');

        DB::table('product_receipt_plan')->insert([
            'product_receipt_plan_id' => $request->input('product_receipt_plan_id'),
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

        // ตรวจสอบว่าไฟล์ยังอยู่ในเซิร์ฟเวอร์
        if (Storage::exists($filePath)) {
            // ดึงข้อมูลจากไฟล์ Excel อีกครั้งเพื่อบันทึกลงฐานข้อมูล
            $spreadsheet = IOFactory::load($fullFilePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            $rows = array_slice($data, 2);

            // บันทึกข้อมูลลงฐานข้อมูล
            foreach ($rows as $row) {
                DB::table('product_receipt_plan_detail')->insert([
                    'product_id' => $row[0],
                    'product_quantity' => $row[2] ?? 0,
                    'increase_quantity' => $row[3] ?? 0,
                    'reduce_quantity' => $row[4] ?? 0,
                    'total_quantity' => $row[5] ?? 0,
                    'product_receipt_plan_id' => $request->input('product_receipt_plan_id'),
                    'note' => $row[6],
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ลบไฟล์ชั่วคราวออกจากระบบ
            Storage::delete($filePath);
        }

        return redirect()->route('ProductReceiptPlan');
    }

    public function EditProductReceiptPlan($product_receipt_plan_id)
    {
        $ProductReceiptPlans = DB::table('product_receipt_plan')
            ->join('work_shift', 'product_receipt_plan.shift_id', '=', 'work_shift.shift_id')
            ->where('product_receipt_plan.product_receipt_plan_id', $product_receipt_plan_id)
            ->first();

        $ProductReceiptPlansDetails = DB::table('product_receipt_plan_detail')
            ->join('product_detail', 'product_receipt_plan_detail.product_id', '=', 'product_detail.product_id')
            ->where('product_receipt_plan_id', $product_receipt_plan_id)
            ->get();

        $shifts = DB::table('work_shift')->select('shift_id', 'shift_name')->distinct()->get();

        return view('Admin.ProductReceiptPlan.EditProductReceiptPlan', compact(
            'ProductReceiptPlans',
            'ProductReceiptPlansDetails',
            'shifts',
        ));
    }

    public function SaveEditProductReceiptPlan(Request $request)
    {
        return redirect()->route('EditProductReceiptPlan');
    }
}
