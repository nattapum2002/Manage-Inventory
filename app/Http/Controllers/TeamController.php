<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public $select_teams = [
        ['select_name' => 'A'],
        ['select_name' => 'B'],
        ['select_name' => 'C'],
        ['select_name' => 'D'],
        ['select_name' => 'E'],
        ['select_name' => 'F'],
    ];
    public function index()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $teams = DB::table('lock_team')->get();
        $usersCounts = DB::table('lock_team_user')->get();
        return view('Admin.ManageTeam.manageTeam', compact('teams', 'usersCounts'));
    }

    public function EditTeam($team_id)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $teams = DB::table('lock_team')
            ->join('lock_team_user', 'lock_team.team_id', '=', 'lock_team_user.team_id')
            ->join('users', 'lock_team_user.user_id', '=', 'users.user_id')
            ->where('lock_team.team_id', $team_id)->get();
        return view('Admin.ManageTeam.EditTeam', compact('teams'));
    }

    public function SaveEditTeam(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'team_id' => 'required|exists:lock_team,team_id', // ตรวจสอบ team_id ในตาราง lock_team
            'team_edit.user_id' => 'required|exists:users,id', // ตรวจสอบ user_id ในตาราง users
            'team_edit.old_user_id' => 'required|exists:lock_team_user,user_id', // ตรวจสอบ old_user_id
        ]);

        // Extract the validated data
        $team_id = $validated['team_id'];
        $new_user_id = $validated['team_edit']['user_id'];
        $old_user_id = $validated['team_edit']['old_user_id'];

        // หาก user_id เดิมและใหม่เหมือนกัน ไม่ต้องดำเนินการ
        if ($new_user_id === $old_user_id) {
            return response()->json([
                "status" => false,
                "data" => "No changes needed.",
            ]);
        }

        // ตรวจสอบว่าความสัมพันธ์ใหม่มีอยู่แล้วหรือไม่
        $existingRelation = DB::table('lock_team_user')
            ->where('team_id', $team_id)
            ->where('user_id', $new_user_id)
            ->exists();

        if ($existingRelation) {
            return response()->json([
                "status" => false,
                "data" => "The relationship already exists.",
            ]);
        }

        // ใช้ Transaction เพื่อความปลอดภัยของข้อมูล
        DB::transaction(function () use ($team_id, $old_user_id, $new_user_id) {

            DB::table('lock_team_user')
                ->where('team_id', $team_id)
                ->where('user_id', $old_user_id)
                ->update([
                    'user_id' => $new_user_id,
                ]);
        });

        return response()->json([
            "status" => true,
            "data" => "The relationship has been updated successfully.",
        ]);
    }

    public function Toggle($team_id, $status)
    {
        DB::table('lock_team')->where('team_id', $team_id)->update([
            'status' => $status,
        ]);

        return redirect()->route('ManageTeam')->with('success', 'Team has been updated successfully.');
    }

    public function AddTeam()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $team_names = DB::table('lock_team')->select('team_name')->distinct()->pluck('team_name')->toArray();

        $filtered_teams = array_filter($this->select_teams, function ($team) use ($team_names) {
            return !in_array($team['select_name'], $team_names);
        });

        return view('Admin.ManageTeam.AddTeam', compact('filtered_teams'));
    }

    public function SaveAddTeam(Request $request)
    {
        $data = $request->all();
        DB::transaction(function () use ($data) {
            DB::table('lock_team')->updateOrInsert(
                ['team_id' => $data['team_id']], // Condition to check for existing record
                [
                    'team_name' => $data['team_name'], // Data to update/insert
                    'date' => $data['date'],
                    'note' => $data['note'] ?? null,
                    'status' => 1
                ]
            );
            foreach ($data['user_id'] as $key => $value) {
                DB::table('lock_team_user')->insert([
                    'team_id' => $data['team_id'],
                    'user_id' => $data['user_id'][$key],
                ]);
            }
        });
        return redirect()->route('ManageTeam')->with('success', 'Data saved successfully');
    }

    public function DeleteTeam($team_id, $user_id)
    {
        DB::table('lock_team_user')->where('team_id', $team_id)->where('user_id', $user_id)->delete();
        return redirect()->route('ManageTeam')->with('success', 'Team has been deleted successfully.');
    }

    public function AutoCompleteAddTeam(Request $request)
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

    public function AutocompleteSearchTeam(Request $request)
    {
        $query = $request->get('query');

        $data = DB::table('lock_team')
            ->where('team_name', 'like', '%' . $query . '%')
            ->limit(10) // จำกัดผลลัพธ์ 10 รายการ
            ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->team_name,  // ใช้ 'label' สำหรับการแสดงผลในรายการ autocomplete
                'value' => $item->team_name,  // ใช้ 'value' สำหรับการเติมในช่อง input
                'team_id' => $item->team_id,     // ส่ง 'id' สำหรับการใช้รหัสสินค้าเพิ่มเติม
            ];
        }

        return response()->json($results);
    }
}