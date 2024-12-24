<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $Users = DB::table('users')->get();

        return view('Admin.ManageUsers.manageuser', compact('Users'));
    }

    public function profile()
    {
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $User = DB::table('users')->where('user_id', Auth::user()->user_id)->first();

        // dd($user);
        return view('admin.profile', compact('User'));
    }

    public function create(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        // Validate the incoming request
        $request->validate([
            'user_id' => 'required',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date',
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
                'position' => $request->position,
                'start_date' => $request->start_date,
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
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $User = DB::table('users')->where('user_id', $user_id)->first();

        return view('Admin.ManageUsers.edituser', compact('User'));
    }

    public function toggle($user_id, $status)
    {
        // Ensure the user is authenticated (optional)
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        // Attempt to update the user's status
        try {
            DB::table('users')->where('user_id', $user_id)->update([
                'status' => $status,
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
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date',
            'password' => 'nullable|min:6|confirmed', // Optional password
            'user_type' => 'required',
            'note' => 'nullable|string|max:500',
            'status' => 'required',
        ]);

        // Check if the user exists
        $user = DB::table('users')->where('user_id', $user_id)->first();
        if (!$user) {
            return redirect()->route('ManageUsers')->withErrors(['error' => 'ไม่พบผู้ใช้ที่ต้องการแก้ไข']);
        }

        // Prepare data to update
        $updateData = [
            'name' => $request->name,
            'surname' => $request->surname,
            'position' => $request->position,
            'start_date' => $request->start_date,
            'user_type' => $request->user_type,
            'note' => $request->note,
            'status' => $request->status,
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Update the user data
        try {
            DB::table('users')->where('user_id', $user_id)->update($updateData);
            return redirect()->route('ManageUsers')->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาดขณะบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }


    public function delete(Request $request)
    {
        //
    }
}