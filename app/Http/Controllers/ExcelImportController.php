<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelImportController extends Controller
{
    public function showUploadForm()
    {
        return view('ExcelTest.uploadExcel');
    }

    public function uploadAndPreview(Request $request)
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

        $header = $data[0];
        $detailHeader = $data[1];
        $rows = array_slice($data, 2);

        // ส่งข้อมูลไปยัง View เพื่อแสดงพรีวิว
        return view('ExcelTest.preview', [
            'detailHeader' => $detailHeader,
            'rows' => $rows,
            'filePath' => $path  // ส่งพาธไฟล์ไปเพื่อให้ลบได้ภายหลัง
        ]);
    }

    public function saveExcelData(Request $request)
    {
        // รับข้อมูลไฟล์ชั่วคราวจากฟอร์ม
        $filePath = $request->input('filePath');
        $fullFilePath = storage_path('app/' . $filePath);

        // ตรวจสอบว่าไฟล์ยังอยู่ในเซิร์ฟเวอร์
        if (Storage::exists($filePath)) {
            // ดึงข้อมูลจากไฟล์ Excel อีกครั้งเพื่อบันทึกลงฐานข้อมูล
            $spreadsheet = IOFactory::load($fullFilePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            $header = $data[0];
            $detailHeader = $data[1];
            $rows = array_slice($data, 2);

            // บันทึกข้อมูลลงฐานข้อมูล
            foreach ($rows as $row) {
                DB::table('receipt_plan_detail')->insert([
                    'product_id' => $row[0],
                    'product_quantity' => $row[1] ?? 0,
                    'increase_quantity' => $row[2] ?? 0,
                    'reduce_quantity' => $row[3] ?? 0,
                    'total_quantity' => $row[4] ?? 0,
                    'receipt_plan_id' => $row[5],
                    'note' => $row[6],
                    'status' => $row[7],
                ]);
            }

            // ลบไฟล์ชั่วคราวออกจากระบบ
            Storage::delete($filePath);

            return redirect()->route('excel.form')->with('success', 'Data Imported and file deleted successfully');
        }

        return redirect()->route('excel.form')->with('error', 'File not found for deletion');
    }
}
