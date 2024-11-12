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
        return view('showstock', compact('data'));
    }

    public function Admin_index(){
        $data = DB::table('stock')->get();
        return view('Admin.Stock.showstock', compact('data'));
    }

    public function Detail($product_id)
    {
        $data = DB::table('stock')->where('product_id', $product_id)->get();
        return view('Admin.Stock.edititem', compact('data'));
    }

    public function edit_name( Request $request){
        $data = $request->all();
        DB::table('stock')->where('product_id', $data['product_id'])->update([
            'product_name' => $data['product_name']
        ]);

        return redirect()->route("Edit name", $data['product_id'])->with('success', 'Name has been updated successfully.');
    
    }
}
