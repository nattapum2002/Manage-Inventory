<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

use function Laravel\Prompts\select;

class ProductReceiptPlanController extends Controller
{
    public function index()
    {
        $ProductReceiptPlans = DB::table('product_receipt_plan')
            ->join('work_shift', 'product_receipt_plan.shift_id', '=', 'work_shift.shift_id')
            // ->join('product_receipt_plan_detail', 'product_receipt_plan.product_receipt_plan_id', '=', 'product_receipt_plan_detail.product_receipt_plan_id')
            ->get();

        $shifts = DB::table('work_shift')->select('shift_id', 'shift_name')->distinct()->get();

        $ProductDetail = DB::table('product_detail')->select('product_id', 'product_name')->distinct()->get();

        return view('Admin.ProductReceiptPlan.ProductReceiptPlan', compact('ProductReceiptPlans', 'shifts', 'ProductDetail'));
    }

    public function AddProductReceiptPlan(Request $request)
    {
        // ตรวจสอบว่าไฟล์ถูกอัปโหลดและมีประเภทที่ถูกต้อง
        $request->validate([
            'product_receipt_plan_id' => 'required',
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

        $ProductDetail = DB::table('product_detail')->select('product_id', 'product_name')->distinct()->get();

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
            ->select('work_shift.shift_id', 'work_shift.shift_name', 'product_receipt_plan.date', 'product_receipt_plan.note')
            ->where('product_receipt_plan.product_receipt_plan_id', $product_receipt_plan_id)
            ->first();

        $ProductReceiptPlansDetails = DB::table('product_receipt_plan_detail')
            ->join('product_detail', 'product_receipt_plan_detail.product_id', '=', 'product_detail.product_id')
            ->where('product_receipt_plan_id', $product_receipt_plan_id)
            ->select('product_receipt_plan_detail.*', 'product_detail.product_name')
            ->get();

        $product_details = DB::table('product_detail')->select('product_id', 'product_name')->distinct()->get();

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
                foreach ($data['product_id'] as $key => $productId) {
                    DB::table('product_receipt_plan_detail')->updateOrInsert(
                        ['product_id' => $productId],
                        [
                            'product_receipt_plan_id' => $data['product_receipt_plan_id'],
                            'product_quantity' => $data['product_quantity'][$key] ?? 0,
                            'increase_quantity' => $data['increase_quantity'][$key] ?? 0,
                            'reduce_quantity' => $data['reduce_quantity'][$key] ?? 0,
                            'total_quantity' => $data['total_quantity'][$key] ?? 0,
                            'note' => $data['note'][$key] ?? 'N/A',
                            'updated_at' => now(),
                        ]
                    );
                }
            });

            return redirect()->route('EditProductReceiptPlan', $data['product_receipt_plan_id'])
                ->with('success', 'Products added/updated successfully.');
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Error: " . $e->getMessage(),
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

        $shift_data = DB::table('work_shift')->where('shift_id', $request->input('shift_id'))->first();

        $product_receipt_name = 'กะ ' . $shift_data->shift_name . ' : ' . (new DateTime($request->input('date')))->format('d/m/Y');

        DB::table('product_receipt_plan')->where('product_receipt_plan_id', $request->input('product_receipt_plan_id'))->update([
            'product_receipt_name' => $product_receipt_name,
            'shift_id' => $request->input('shift_id'),
            'date' => $request->input('date'),
            'note' => $request->input('note'),
            'updated_at' => now(),
        ]);
        return redirect()->route('EditProductReceiptPlan', $request->input('product_receipt_plan_id'))->with('success', 'updated successfully.');
    }

    public function SaveEditProduct(Request $request)
    {
        $query = $request->product_edit;
        // การตรวจสอบข้อมูลก่อนการดำเนินการ
        // $query->validate([
        //     'product_id' => 'required|string',
        //     'old_product_id' => 'required|string',
        //     'product_quantity' => 'required|numeric',
        //     'increase_quantity' => 'required|numeric',
        //     'reduce_quantity' => 'required|numeric',
        //     'total_quantity' => 'required|numeric',
        //     'note' => 'nullable|string',
        //     'product_receipt_plan_id' => 'required|string',
        // ]);

        try {
            if ($request->input('product_id') === $request->input('old_product_id')) {
                // อัปเดตข้อมูลเมื่อ product_id เดิมยังคงไม่เปลี่ยนแปลง
                DB::table('product_receipt_plan_detail')
                    ->where('product_receipt_plan_id', $request->product_receipt_plan_id)
                    ->where('product_id', $request->product_edit['product_id'])
                    ->update([
                        'product_quantity' => $request->product_edit['product_quantity'],
                        'increase_quantity' => $request->product_edit['increase_quantity'],
                        'reduce_quantity' => $request->product_edit['reduce_quantity'],
                        'total_quantity' => $request->product_edit['total_quantity'],
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
                    'product_quantity' => $request->product_edit['product_quantity'],
                    'increase_quantity' => $request->product_edit['increase_quantity'],
                    'reduce_quantity' => $request->product_edit['reduce_quantity'],
                    'total_quantity' => $request->product_edit['total_quantity'],
                    'note' => $request->product_edit['note'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                "status" => true,
                "data" => "The shift assignment has been updated successfully.",
            ]);
        } catch (\Exception $e) {
            // หากเกิดข้อผิดพลาดในระหว่างการทำงาน
            return response()->json([
                "status" => false,
                "message" => "Error: " . $e->getMessage(),
            ], 500);
        }
    }


    public function AutocompleteProduct(Request $request)
    {
        $query = $request->input('query');

        // ค้นหาข้อมูลจากตาราง product_detail
        $data = DB::table('product_detail')
            ->select('product_id', 'product_name') // เลือกฟิลด์ที่ต้องการ
            ->where('product_id', 'like', '%' . $query . '%') // ค้นหาข้อมูล
            ->distinct()
            ->limit(10) // จำกัดผลลัพธ์ 10 รายการ
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ jQuery Autocomplete ต้องการ
        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->product_id . ' - ' . $item->product_name,
                'value' => $item->product_id,
                'product_name' => $item->product_name,
            ];
        }

        return response()->json($results); // ส่งข้อมูลกลับในรูปแบบ JSON
    }
}
