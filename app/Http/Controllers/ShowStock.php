<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowStock extends Controller
{
    //
    public function index()
    {
        $data = DB::table('stock')->get();
        return view('Stock.showstock', compact('data'));
    }
}
