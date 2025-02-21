<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ReceiptPlanController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    private function GetReceiptPlanByMonth($month)
    {
        $year = substr($month, 0, 4);
        $monthOnly = substr($month, 5, 2);

        return DB::table('receipt_plan')
            ->join('shift', 'receipt_plan.shift_id', '=', 'shift.shift_id')
            ->join('shift_time', 'shift.shift_time_id', '=', 'shift_time.shift_time_id')
            ->whereYear('receipt_plan.date', $year)
            ->whereMonth('receipt_plan.date', $monthOnly)
            ->get();
    }

    private function GetProductID($product_number)
    {
        return DB::table('product')
            ->where('product_number', $product_number)
            ->value('product_id');
    }


    public function index(Request $request)
    {
        $ReceiptPlans = DB::table('receipt_plan')
            ->join('shift', 'receipt_plan.shift_id', '=', 'shift.shift_id')
            ->get();

        $shifts = DB::table('shift')
            ->join('shift_time', 'shift.shift_time_id', '=', 'shift_time.shift_time_id')
            ->select(
                'shift.shift_id',
                'shift_time.shift_name'
            )
            ->distinct()
            ->get();

        $ProductDetail = DB::table('product')->select('product_id', 'product_description')->distinct()->get();

        $ReceiptPlanFilterMonth = $this->GetReceiptPlanByMonth($request->input('date') ?? now()->format('Y-m'));

        return view('Admin.ReceiptPlan.ReceiptPlan', compact('ReceiptPlans', 'shifts', 'ProductDetail', 'ReceiptPlanFilterMonth'));
    }

    public function ReceiptPlanFilterMonth(Request $request)
    {
        // รับค่าเดือนจากคำขอ หรือใช้เดือนปัจจุบันหากไม่ได้ระบุ
        $month = $request->input('month') ?? now()->format('Y-m');

        // ดึงข้อมูลกะตามเดือน
        $ReceiptPlanFilterMonth = $this->GetReceiptPlanByMonth($month);

        // ส่งคืน JSON
        return response()->json([
            'ReceiptPlanFilterMonth' => $ReceiptPlanFilterMonth
        ]);
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
        $shifts = DB::table('shift')
            ->join('shift_time', 'shift.shift_time_id', '=', 'shift_time.shift_time_id')
            ->whereDate('date', $date)
            ->get();

        return response()->json([
            'status' => 'success',
            'shifts' => $shifts
        ]);
    }

    public function AddReceiptPlan(Request $request)
    {
        // ตรวจสอบว่าไฟล์ถูกอัปโหลดและมีประเภทที่ถูกต้อง
        $request->validate([
            // 'receipt_plan_id' => 'required',
            'day' => 'required',
            'shift_id' => 'required',
            'note' => 'nullable',
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $data = $request->all();

        if (array_key_exists('day', $data)) {
            $data['date'] = $data['day'];
            unset($data['day']);
        }

        $filteredRequest = Arr::except($data, ['_token', 'file']);

        // จัดเก็บไฟล์ในไดเรกทอรีชั่วคราว
        $path = $request->file('file')->store('temp');
        $filePath = storage_path('app/' . $path);

        // อ่านข้อมูลจากไฟล์ Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $detailHeader = $data[1];
        $rows = array_slice($data, 2);

        $ReceiptPlans = DB::table('receipt_plan')
            ->join('receipt_plan_detail', 'receipt_plan.receipt_plan_id', '=', 'receipt_plan_detail.receipt_plan_id')
            ->get();

        $shift = DB::table('shift')
            ->join('shift_time', 'shift.shift_time_id', '=', 'shift_time.shift_time_id')
            ->select(
                'shift.shift_id',
                'shift_time.shift_name'
            )
            ->where('shift_id', $filteredRequest['shift_id'])
            ->first();

        $ProductDetail = DB::table('product')->select('product_id', 'product_description')->distinct()->get();

        // ส่งข้อมูลไปยัง View เพื่อแสดงพรีวิว
        return view('Admin.ReceiptPlan.AddReceiptPlan', [
            'detailHeader' => $detailHeader,
            'rows' => $rows,
            'filePath' => $path, // ส่งพาธไฟล์ไปเพื่อให้ลบได้ภายหลัง
            'ReceiptPlans' => $ReceiptPlans,
            'filteredRequest' => $filteredRequest,
            'shift' => $shift,
            'ProductDetail' => $ProductDetail
        ]);
    }

    public function SaveAddReceiptPlan(Request $request)
    {
        try {
            $receipt_plan_id = $request->input('receipt_plan_id') ?? Str::uuid();

            DB::transaction(function () use ($request, $receipt_plan_id) {
                // Insert data into `receipt_plan` table
                DB::table('receipt_plan')->insert([
                    'receipt_plan_id' => $receipt_plan_id,
                    'date' => $request->input('date'),
                    'shift_id' => $request->input('shift_id'),
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
                            'product_id' => $this->getProductID($row[0]),
                            'weight' => $row[2] ?? 0,
                            'increase_weight' => $row[3] ?? 0,
                            'reduce_weight' => $row[4] ?? 0,
                            'total_weight' => $row[5] ?? 0,
                            'receipt_plan_id' => $receipt_plan_id,
                        ];
                    }

                    if (!empty($insertData)) {
                        foreach (array_chunk($insertData, 100) as $chunk) {
                            DB::table('receipt_plan_detail')->insert($chunk);
                        }
                    }
                    $files = Storage::files('temp');

                    foreach ($files as $file) {
                        Storage::delete($file);
                    }
                }
            });

            return redirect()->route('ReceiptPlan')->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "มีข้อผิดพลาดในการบันทึกข้อมูล",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    public function CancelAddReceiptPlan()
    {
        return redirect()->route('ReceiptPlan');
    }

    public function EditReceiptPlan($receipt_plan_id)
    {
        $ReceiptPlans = DB::table('receipt_plan')
            ->join('shift', 'receipt_plan.shift_id', '=', 'shift.shift_id')
            ->join('shift_time', 'shift.shift_time_id', '=', 'shift_time.shift_time_id')
            ->select('shift.shift_id', 'shift_time.shift_name', 'receipt_plan.date', 'receipt_plan.note')
            ->where('receipt_plan.receipt_plan_id', $receipt_plan_id)
            ->first();

        $ReceiptPlansDetails = DB::table('receipt_plan_detail')
            ->join('product', 'receipt_plan_detail.product_id', '=', 'product.product_id')
            ->where('receipt_plan_id', $receipt_plan_id)
            ->select('receipt_plan_detail.*', 'product.product_number', 'product.product_description')
            ->get();

        $product_details = DB::table('product')->select('product_id', 'product_number', 'product_description')->distinct()->get();

        $shifts = DB::table('shift')
            ->join('shift_time', 'shift.shift_time_id', '=', 'shift_time.shift_time_id')
            ->select(
                'shift.shift_id',
                'shift_time.shift_name'
            )
            ->where('shift_name', '!=', $ReceiptPlans->shift_name)
            ->distinct()
            ->get();

        return view('Admin.ReceiptPlan.EditReceiptPlan', compact(
            'ReceiptPlans',
            'ReceiptPlansDetails',
            'product_details',
            'shifts',
            'receipt_plan_id'
        ));
    }

    public function AddProduct(Request $request)
    {
        $request->validate([
            // 'receipt_plan_id' => 'required|uuid',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:product,product_number',
            'product_quantity' => 'required|array',
            'product_quantity.*' => 'required|numeric|min:0',
            'increase_quantity' => 'required|array',
            'increase_quantity.*' => 'required|numeric|min:0',
            'reduce_quantity' => 'required|array',
            'reduce_quantity.*' => 'required|numeric|min:0',
            'total_quantity' => 'nullable|array',
            'total_quantity.*' => 'nullable|numeric|min:0',
        ]);

        try {
            $data = $request->all(); // รับข้อมูลทั้งหมดจากฟอร์ม

            DB::transaction(function () use ($data) {
                $insertData = []; // สร้างอาร์เรย์สำหรับเก็บข้อมูลที่ต้องบันทึก

                foreach ($data['product_id'] as $key => $productId) {
                    $insertData[] = [
                        'product_id' => $this->GetProductID($productId),
                        'receipt_plan_id' => $data['receipt_plan_id'],
                        'weight' => $data['product_quantity'][$key] ?? 0,
                        'increase_weight' => $data['increase_quantity'][$key] ?? 0,
                        'reduce_weight' => $data['reduce_quantity'][$key] ?? 0,
                        'total_weight' => $data['total_quantity'][$key] ?? 0,
                    ];
                }

                // ทำการบันทึกข้อมูลทั้งหมดในครั้งเดียว
                DB::table('receipt_plan_detail')->upsert(
                    $insertData,
                    ['product_id', 'receipt_plan_id'], // ระบุคอลัมน์ที่ใช้ในการตรวจสอบ
                    ['weight', 'increase_weight', 'reduce_weight', 'total_weight'] // คอลัมน์ที่ต้องการอัปเดตหากพบข้อมูลซ้ำ
                );
            });

            // ส่งผู้ใช้กลับไปยังหน้าที่ต้องการพร้อมกับข้อความสำเร็จ
            return redirect()->route('EditReceiptPlan', $data['receipt_plan_id'])
                ->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            // ในกรณีที่เกิดข้อผิดพลาด
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาด : ' . $e->getMessage());
        }
    }

    public function SaveEditDetail(Request $request)
    {
        $request->validate([
            'receipt_plan_id' => 'required|string|max:255',
            'shift_id' => 'required',
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            DB::table('receipt_plan')
                ->where('receipt_plan_id', $request->input('receipt_plan_id'))
                ->update([
                    'shift_id' => $request->input('shift_id'),
                    'date' => $request->input('date'),
                    'note' => $request->input('note'),
                    'updated_at' => now(),
                ]);

            return redirect()->route('EditReceiptPlan', $request->input('receipt_plan_id'))
                ->with('success', 'Updated successfully.');
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "An unexpected error occurred. Please try again later.",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    public function SaveEditProductPlan(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'receipt_plan_id' => 'required|uuid',
            'product_edit.product_id' => 'required|string|max:255',
            'product_edit.old_product_id' => 'nullable|string|max:255',
            'product_edit.product_quantity' => 'required|numeric|min:0',
            'product_edit.increase_quantity' => 'nullable|numeric|min:0',
            'product_edit.reduce_quantity' => 'nullable|numeric|min:0',
            'product_edit.total_quantity' => 'required|numeric|min:0',
        ]);

        $product_id = $this->GetProductID($request->product_edit['product_id']);

        try {
            DB::transaction(function () use ($request, $product_id) {
                if ($request->input('product_id') === $request->input('old_product_id')) {
                    DB::table('receipt_plan_detail')
                        ->where('receipt_plan_id', $request->receipt_plan_id)
                        ->where('product_id', $product_id)
                        ->update([
                            'weight' => $request->product_edit['product_quantity'],
                            'increase_weight' => $request->product_edit['increase_quantity'],
                            'reduce_weight' => $request->product_edit['reduce_quantity'],
                            'total_weight' => $request->product_edit['total_quantity'],
                        ]);
                } else {
                    DB::table('receipt_plan_detail')
                        ->where('receipt_plan_id', $request->receipt_plan_id)
                        ->where('product_id', $request->product_edit['old_product_id'])
                        ->delete();

                    DB::table('receipt_plan_detail')->insert([
                        'receipt_plan_id' => $request->receipt_plan_id,
                        'product_id' => $product_id,
                        'weight' => $request->product_edit['product_quantity'],
                        'increase_weight' => $request->product_edit['increase_quantity'],
                        'reduce_weight' => $request->product_edit['reduce_quantity'],
                        'total_weight' => $request->product_edit['total_quantity'],
                    ]);
                }
            });

            return response()->json([
                "status" => true,
                "message" => "แก้ไขข้อมูลเรียบร้อยแล้ว",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "มีข้อผิดพลาดในการบันทึกข้อมูล",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    public function DeleteProduct($receipt_plan_id, $product_id)
    {
        try {
            $exists = DB::table('receipt_plan_detail')
                ->where('receipt_plan_id', $receipt_plan_id)
                ->where('product_id', $product_id)
                ->exists();

            if (!$exists) {
                return redirect()->route('EditReceiptPlan', $receipt_plan_id)
                    ->with('error', 'ไม่พบสินค้าสำหรับลบ');
            }

            DB::table('receipt_plan_detail')
                ->where('receipt_plan_id', $receipt_plan_id)
                ->where('product_id', $product_id)
                ->delete();

            return redirect()->route('EditReceiptPlan', $receipt_plan_id)
                ->with('success', 'ลบสินค้าเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->route('EditReceiptPlan', $receipt_plan_id)
                ->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }


    public function AutocompleteProduct(Request $request)
    {
        $query = $request->input('query');

        // ค้นหาข้อมูลจากตาราง product_detail
        $data = DB::table('product')
            ->select('product_id', 'product_number', 'product_description') // เลือกฟิลด์ที่ต้องการ
            ->where('product_number', 'like', '%' . $query . '%') // ค้นหาข้อมูล
            ->distinct()
            ->limit(10) // จำกัดผลลัพธ์ 10 รายการ
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ jQuery Autocomplete ต้องการ
        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->product_number . ' - ' . $item->product_description,
                'product_id' => $item->product_id,
                'value' => $item->product_number,
                'product_name' => $item->product_description,
            ];
        }

        return response()->json($results); // ส่งข้อมูลกลับในรูปแบบ JSON
    }
}
