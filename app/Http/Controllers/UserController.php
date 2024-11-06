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
        $Users = DB::table('users')->get();

        return view('Admin.ManageUsers.manageuser', compact('Users'));
    }

    public function profile()
    {
        // if (!Auth::user()) {
        //     return redirect()->route('login');
        // }

        // $user = DB::table('users')->where('user_id', Auth::user()->user_id)->first();

        // return view('admin.ManageUsers.profile', compact('user'));
    }

    public function create(Request $request)
    {
        // if (!Auth::user()) {
        //     return redirect()->route('login');
        // }

        DB::table('users')->insert([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'surname' => $request->surname,
            'position' => $request->position,
            'user_type' => $request->user_type,
            'email' => $request->email,
            'status' => $request->status,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('ManageUsers')->with('success', 'Profile has been created successfully.');
    }

    public function edit($user_id)
    {
        // if (!Auth::user()) {
        //     return redirect()->route('login');
        // }

        $User = DB::table('users')->where('user_id', $user_id)->first();

        return view('Admin.ManageUsers.edituser', compact('User'));
    }

    public function toggle($user_id, $status)
    {
        DB::table('users')->where('user_id', $user_id)->update([
            'status' => $status,
        ]);

        return redirect()->route('ManageUsers')->with('success', 'Profile has been updated successfully.');
    }

    public function update($user_id, Request $request)
    {
        // if (!Auth::user()) {
        //     return redirect()->route('login');
        // }

        DB::table('users')->where('user_id', $user_id)->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'position' => $request->position,
            'user_type' => $request->user_type,
            'email' => $request->email,
            'status' => $request->status,
            'password' => $request->password,
        ]);

        return redirect()->route('ManageUsers')->with('success', 'Profile has been updated successfully.');
    }

    public function delete(Request $request)
    {
        //
    }
}