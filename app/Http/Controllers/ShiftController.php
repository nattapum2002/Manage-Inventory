<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = DB::table('work_shift')->get();
        return view('Admin.ManageShift.manageshift', compact('shifts'));
    }

    public function DetailShift($shift_id)
    {
        $shifts = DB::table('work_shift')
            ->join('shift_users', 'work_shift.shift_id', '=', 'shift_users.shift_id')
            ->join('users', 'shift_users.user_id', '=', 'users.user_id')
            ->where('work_shift.shift_id', $shift_id)->get();
        return view('Admin.ManageShift.DetailShift', compact('shifts'));
    }

    public function AddShift(Request $request)
    {
        $users = DB::table('users')->get();
        return view('Admin.ManageShift.AddShift', compact('users'));
    }
}