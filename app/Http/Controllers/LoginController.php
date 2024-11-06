<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected function username()
    {
        return 'user_id';
    }

    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // $request->validate([
        //     'user_id' => 'required',
        //     'password' => 'required',
        // ]);

        if (Auth::attempt(['user_id' => $request->user_id, 'password' => $request->password])) {
            if (Auth::user()->user_type == 'Admin') {
                return redirect()->route('Dashboard.Admin');
            } else if (Auth::user()->user_type == 'Manager') {
                return redirect()->route('Dashboard.Manager');
            } else if (Auth::user()->user_type == 'User') {
                return redirect()->route('Dashboard.User');
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'สิทธิ์ไม่เพียงพอ');
            }
        }

        return redirect()->back()->with('error', 'ข้อมูลการเข้าสู่ระบบไม่ถูกต้อง');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}