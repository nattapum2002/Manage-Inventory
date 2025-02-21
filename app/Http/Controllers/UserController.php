<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $Users = DB::table('users')->get();

        return view('Admin.ManageUsers.manageuser', compact('Users'));
    }

    public function profile()
    {
        $User = DB::table('users')->where('user_id', Auth::user()->user_id)->first();

        // dd($user);
        if (Auth::user()->user_type == 'Admin') {
            return view('Admin.profile', compact('User'));
        } elseif (Auth::user()->user_type == 'Manager') {
            return view('Manager.profile', compact('User'));
        } elseif (Auth::user()->user_type == 'Employee') {
            return view('Employee.profile', compact('User'));
        }
    }

    public function syncUsers()
    {
        try {
            set_time_limit(1800);

            // เรียก Stored Procedure
            DB::statement('EXEC dbo.Add_users');

            // ดึงข้อมูลจาก users_temporary
            $temporaryUsers = DB::table('users_temporary')->get();

            // เตรียมข้อมูลสำหรับการแทรก
            $usersData = $temporaryUsers->map(function ($user) {
                return [
                    'user_id'    => $user->user_id,
                    'prefix'     => $user->prefix,
                    'name'       => $user->name,
                    'surname'    => $user->surname,
                    'department' => $user->department,
                    'level'      => $user->level,
                    'password'   => Hash::make($user->user_id),
                ];
            })->toArray();

            // อัปเดตหรือแทรกข้อมูลแบบ Batch
            foreach (array_chunk($usersData, 100) as $batch) {
                DB::table('users')->upsert($batch, ['user_id'], ['prefix', 'name', 'surname', 'department', 'level', 'password']);
            }

            return redirect()->route('ManageUsers')->with('success', 'Sync ข้อมูลผู้ใช้งานเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาดขณะ Sync ข้อมูล : ' . $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
            'user_type' => 'required',
            'note' => 'nullable|string|max:500',
            'status' => 'required',
        ]);

        try {
            // Insert data into the database
            DB::table('users')->insert([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'surname' => $request->surname,
                'department' => $request->department,
                'password' => Hash::make($request->password),
                'user_type' => $request->user_type,
                'note' => $request->note,
                'status' => $request->status,
            ]);

            // Redirect to Manage Users with success message
            return redirect()->route('ManageUsers')->with('success', 'เพิ่มผู้ใช้ ' . $request->name . ' ' . $request->surname . ' งานเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            // Handle any errors
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาดขณะบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }

    public function edit($user_id)
    {
        $User = DB::table('users')->where('user_id', $user_id)->first();

        return view('Admin.ManageUsers.edituser', compact('User'));
    }

    public function toggle($user_id, $status)
    {
        // Attempt to update the user's status
        try {
            DB::table('users')->where('user_id', $user_id)->update([
                'status' => $status,
                'updated_at' => now(),
            ]);

            // Redirect with success message
            return redirect()->route('ManageUsers')->with('success', 'แก้ไขสถานะผู้ใช้งานเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            // Handle any errors and return the error message
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาดขณะบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }

    public function update($user_id, Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('Login.index');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'user_type' => 'required|string',
            'note' => 'nullable|string|max:500',
            'status' => 'required|boolean',
        ]);

        $user = DB::table('users')->where('user_id', $user_id)->first();

        if (!$user) {
            return redirect()->route('ManageUsers')->withErrors(['error' => 'ไม่พบผู้ใช้ที่ต้องการแก้ไข']);
        }

        $updateData = [
            'name' => $validatedData['name'],
            'surname' => $validatedData['surname'],
            'department' => $validatedData['department'],
            'user_type' => $validatedData['user_type'],
            'note' => $validatedData['note'],
            'status' => $validatedData['status'],
            'updated_at' => now(),
        ];

        try {
            DB::table('users')->where('user_id', $user_id)->update($updateData);
            return redirect()->route('ManageUsers')->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาดขณะบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }

    public function SaveProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('Login.index');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'user_type' => 'required|string',
            'old_password' => 'nullable|min:6',
            'password' => 'nullable|min:6|confirmed',
            'note' => 'nullable|string|max:500',
            'status' => 'required|boolean',
        ]);

        $user = DB::table('users')->where('user_id', $validatedData['user_id'])->first();

        if ($request->filled('old_password') && !Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'รหัสผ่านเดิมไม่ถูกต้อง']);
        }

        $updateData = [
            'name' => $validatedData['name'],
            'surname' => $validatedData['surname'],
            'department' => $validatedData['department'],
            'user_type' => $validatedData['user_type'],
            'note' => $validatedData['note'],
            'status' => $validatedData['status'],
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        try {
            DB::table('users')->where('user_id', $validatedData['user_id'])->update($updateData);
            return redirect()->route('Profile')->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาดขณะบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }
}
