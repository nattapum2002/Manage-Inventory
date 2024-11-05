<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $Users = DB::table('users')->get();

        return view('admin.ManageUsers.manageuser', compact('Users'));
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
        //
    }

    public function update(Request $request)
    {
        // if (!Auth::user()) {
        //     return redirect()->route('login');
        // }

        DB::table('users')->where('user_id', Auth::user()->user_id)->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'position' => $request->position,
            'user_type' => $request->user_type,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return redirect()->route('profile')->with('success', 'Your profile has been updated successfully.');
    }

    public function delete(Request $request)
    {
        //
    }
}