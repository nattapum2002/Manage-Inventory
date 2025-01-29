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
        $credentials = $request->only('user_id', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $userType = Auth::user()->user_type;

            // สร้าง mapping ระหว่าง user_type กับ route
            $routes = [
                'Admin' => 'Dashboard.Admin',
                'Manager' => 'Dashboard.Manager',
                'Employee' => 'Dashboard.Employee',
            ];

            // ตรวจสอบและเปลี่ยนเส้นทางตาม user_type
            if (array_key_exists($userType, $routes)) {
                return redirect()->route($routes[$userType]);
            }

            // กรณี user_type ไม่มีใน mapping
            Auth::logout();
            return redirect()->back()->with('error', 'สิทธิ์ไม่เพียงพอ');
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
