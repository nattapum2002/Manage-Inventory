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
    public function stock_coldA()
    {
        $data = DB::table('stock')->where('storage_room', 'Cold-A')->get();
        return view('stockcold_A', compact('data'));
    }
    public function stock_coldC()
    {
        $data = DB::table('stock')->where('storage_room', 'Cold-C')->get();
        return view('stockcold_C', compact('data'));
    }
    public function Admin_index()
    {
        $data = DB::table('stock')
            ->join('product', 'product.item_id', '=', 'stock.product_id')
            ->get();
        return view('Admin.Stock.showstock', compact('data'));
    }

    public function Detail($item_no)
    {
        $data = DB::table('product')
            ->join('stock', 'product.id', '=', 'stock.product_id')
            ->where('item_no', $item_no)->get();
        return view('Admin.Stock.edititem', compact('data'));
    }

    public function edit_name(Request $request)
    {
        $data = $request->all();
        DB::table('stock')->where('product_id', $data['product_id'])->update([
            'product_name' => $data['product_name'],
            'storage_room' => $data['room']
        ]);

        return redirect()->route("Edit name", $data['product_id'])->with('success', 'updated successfully.');
    }
}
