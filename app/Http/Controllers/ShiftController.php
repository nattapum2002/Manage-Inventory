<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = DB::table('work_shift')->get();
        $usersCounts = DB::table('shift_users')->get();
        return view('Admin.ManageShift.manageshift', compact('shifts', 'usersCounts'));
    }

    public function EditShift($shift_id)
    {
        $shifts = DB::table('work_shift')
            ->join('shift_users', 'work_shift.shift_id', '=', 'shift_users.shift_id')
            ->join('users', 'shift_users.user_id', '=', 'users.user_id')
            ->where('work_shift.shift_id', $shift_id)->get();
        return view('Admin.ManageShift.EditShift', compact('shifts'));
    }

    public function Toggle($shift_id, $status)
    {
        DB::table('work_shift')->where('shift_id', $shift_id)->update([
            'status' => $status,
        ]);

        return redirect()->route('ManageShift')->with('success', 'Shift has been updated successfully.');
    }

    public function AddShift(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('Admin.ManageShift.AddShift');
        }
        // dd($request->all());
        // $request->validate([
        //     'shift_id' => 'required',
        //     'shift_name' => 'required',
        //     'start_shift' => 'required',
        //     'end_shift' => 'required',
        //     // 'name' => 'required',
        // ], [
        //     'shift_id.required' => 'กรุณากรอกรหัสกะ',
        //     'shift_name.required' => 'กรุณากรอกชื่อกะ',
        //     'start_shift.required' => 'กรุณากรอกเวลาเริ่มกะ',
        //     'end_shift.required' => 'กรุณากรอกเวลาสิ้นสุดกะ',
        //     // 'name.required' => 'กรุณากรอกชื่อพนักงาน',
        // ]);

        $data = $request->all();
        DB::transaction(function () use ($data) {
            DB::table('work_shift')->insert([
                'shift_id' => $data['shift_id'],
                'shift_name' => $data['shift_name'],
                'start_shift' => $data['start_shift'],
                'end_shift' => $data['end_shift'],
                'status' => 1,
            ]);
            foreach ($data['user_id'] as $key => $value) {
                DB::table('shift_users')->insert([
                    'shift_id' => $data['shift_id'],
                    'user_id' => $data['user_id'][$key],
                ]);
            }
        });
        return redirect()->route('AddShift')->with('success', 'Data saved successfully');
    }

    public function AutoCompleteAddShift(Request $request)
    {
        $query = $request->get('query');
        // ดึงข้อมูลเฉพาะฟิลด์ที่ต้องการ เช่น product_name และ product_id
        $data = DB::table('users')
            ->select('user_id', 'name', 'surname', 'position') // เลือกเฉพาะฟิลด์ product_name และ product_id
            ->where('name', 'like', '%' . $query . '%')
            ->limit(10) // จำกัดผลลัพธ์ 10 รายการ
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ jQuery autocomplete ต้องการ
        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->name,  // ใช้ 'label' สำหรับการแสดงผลในรายการ autocomplete
                'value' => $item->name,  // ใช้ 'value' สำหรับการเติมในช่อง input
                'user_id' => $item->user_id,     // ส่ง 'id' สำหรับการใช้รหัสสินค้าเพิ่มเติม
                'surname' => $item->surname,      // ส่ง 'id' สำหรับการใช้รหัสสินค้าเพิ่มเติม
                'position' => $item->position       // ส่ง 'id' สำหรับการใช้รหัสสินค้าเพิ่มเติม
            ];
        }

        return response()->json($results);
    }
}
