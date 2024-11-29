<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShiftController extends Controller
{
    public $select_shifts = [
        ['select_name' => 'A'],
        ['select_name' => 'B'],
        ['select_name' => 'C'],
        ['select_name' => 'D'],
        ['select_name' => 'E'],
        ['select_name' => 'F'],
    ];

    public function index()
    {
        $shifts = DB::table('work_shift')->get();
        $usersCounts = DB::table('shift_users')->get();
        return view('Admin.ManageShift.manageshift', compact('shifts', 'usersCounts'));
    }

    public function EditShift($shift_id)
    {
        $work_shifts = DB::table('work_shift')->select('shift_name')->distinct()->pluck('shift_name')->toArray();

        $filtered_shifts = array_filter($this->select_shifts, function ($team) use ($work_shifts) {
            return !in_array($team['select_name'], $work_shifts);
        });

        $shifts = DB::table('work_shift')
            ->join('shift_users', 'work_shift.shift_id', '=', 'shift_users.shift_id')
            ->join('users', 'shift_users.user_id', '=', 'users.user_id')
            ->where('work_shift.shift_id', $shift_id)->get();
        return view('Admin.ManageShift.EditShift', compact('shifts', 'filtered_shifts'));
    }

    public function SaveEditShift(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'shift_id' => 'required|exists:shift_users,shift_id', // ตรวจสอบ shift_id ในตาราง shift_users
            'shift_edit.user_id' => 'required|exists:users,id', // ตรวจสอบ user_id ในตาราง users
            'shift_edit.old_user_id' => 'required|exists:shift_users,user_id', // ตรวจสอบ old_user_id ในตาราง shift_users
        ]);

        // Extract the validated data
        $shift_id = $validated['shift_id'];
        $new_user_id = $validated['shift_edit']['user_id'];
        $old_user_id = $validated['shift_edit']['old_user_id'];

        // หาก user_id เดิมและใหม่เหมือนกัน ไม่ต้องดำเนินการ
        if ($new_user_id === $old_user_id) {
            return response()->json([
                "status" => false,
                "data" => "No changes needed.",
            ]);
        }

        // ตรวจสอบว่าความสัมพันธ์ใหม่มีอยู่แล้วหรือไม่
        $existingRelation = DB::table('shift_users')
            ->where('shift_id', $shift_id)
            ->where('user_id', $new_user_id)
            ->exists();

        if ($existingRelation) {
            return response()->json([
                "status" => false,
                "data" => "The relationship already exists.",
            ]);
        }

        // ใช้ Transaction เพื่อความปลอดภัยของข้อมูล
        DB::transaction(function () use ($shift_id, $old_user_id, $new_user_id) {
            // อัปเดตข้อมูลในตาราง shift_users
            DB::table('shift_users')
                ->where('shift_id', $shift_id)
                ->where('user_id', $old_user_id)
                ->update([
                    'user_id' => $new_user_id,
                ]);
        });

        return response()->json([
            "status" => true,
            "data" => "The shift assignment has been updated successfully.",
        ]);
    }


    public function Toggle($shift_id, $status)
    {
        DB::table('work_shift')->where('shift_id', $shift_id)->update([
            'status' => $status,
        ]);

        return redirect()->back()->with('success', 'Shift has been updated successfully.');
    }

    public function AddShift(Request $request)
    {
        $work_shifts = DB::table('work_shift')->select('shift_name')->distinct()->pluck('shift_name')->toArray();

        $filtered_shifts = array_filter($this->select_shifts, function ($team) use ($work_shifts) {
            return !in_array($team['select_name'], $work_shifts);
        });

        return view('Admin.ManageShift.AddShift', compact('filtered_shifts'));
    }

    public function SaveAddShift(Request $request)
    {
        $data = $request->all();
        DB::transaction(function () use ($data) {
            DB::table('work_shift')->updateOrInsert(
                ['shift_id' => $data['shift_id']], // Condition to check for existing record
                [
                    'shift_name' => $data['shift_name'], // Data to update/insert
                    'start_shift' => $data['start_shift'],
                    'end_shift' => $data['end_shift'],
                    'note' => $data['note'] ?? null,
                    'status' => 1
                ]
            );
            foreach ($data['user_id'] as $key => $value) {
                DB::table('shift_users')->insert([
                    'shift_id' => $data['shift_id'],
                    'user_id' => $data['user_id'][$key],
                ]);
            }
        });
        return redirect()->back()->with('success', 'Data saved successfully');
    }

    public function DeleteShift($shift_id, $user_id)
    {
        DB::table('shift_users')->where('shift_id', $shift_id)->where('user_id', $user_id)->delete();
        return redirect()->back()->with('success', 'Shift has been deleted successfully.');
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

    public static function AutoSelectShift($date, $time)
    {
        $datetime = Carbon::parse("$date $time");

        $shifts = DB::table('work_shift')->get();

        foreach ($shifts as $shift) {
            $shiftStart = Carbon::parse("$date $shift->start_shift");
            $shiftEnd = Carbon::parse("$date $shift->end_shift");

            if ($datetime->between($shiftStart, $shiftEnd)) {
                return [
                    'shift_id' => $shift->shift_id,
                    'shift_name' => $shift->shift_name,
                    'start_time' => $shift->start_shift,
                    'end_time' => $shift->end_shift,
                ];
            }
        }

        // หากไม่พบกะที่ตรงกับช่วงเวลา
        return [
            'shift_id' => null,
            'message' => 'ไม่พบกะที่ตรงกับช่วงเวลาที่ระบุ',
        ];
    }
}
