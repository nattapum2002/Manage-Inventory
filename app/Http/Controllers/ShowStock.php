<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowStock extends Controller
{
    //
    public function index()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $data = DB::table('stock')
            ->join('warehouse', 'warehouse.id', '=', 'product.warehouse')
            ->get();
        return view('showstock', compact('data'));
    }
    public function stock_coldA()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $data = DB::table('stock')
            ->Join('product', 'product.item_id', '=', 'stock.product_id')
            ->where('warehouse', 'Cold-A')
            ->get();
        return view('stockcold_A', compact('data'));
    }
    public function stock_coldC()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $data = DB::table('stock')
            ->Join('product', 'product.item_id', '=', 'stock.product_id')
            ->where('warehouse', 'Cold-C')
            ->get();
        return view('stockcold_C', compact('data'));
    }
    public function Admin_index()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $data = DB::table('stock')
            ->Join('product', 'product.item_id', '=', 'stock.product_id')
            ->join('warehouse', 'warehouse.id', '=', 'product.warehouse')
            ->get();
        return view('Admin.Stock.showstock', compact('data'));
    }

    public function Detail($item_id)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $data = DB::table('product')
            ->Join('stock', 'product.item_id', '=', 'stock.product_id')
            ->where('product.item_no', $item_id)->get();

        $Warehouse = DB::table('warehouse')->get();

        return view(
            'Admin.Stock.edititem',
            compact(
                'data',
                'Warehouse'
            )
        );
    }

    public function edit_name(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required',
            'product_name' => 'required|string|max:255',
            'room' => 'required|string|max:255',
        ]);

        $data = $request->all();

        try {
            DB::table('product')
                ->where('item_no', $data['product_id'])
                ->update([
                    // 'item_desc1' => $data['product_name'],
                    'warehouse' => $data['room']
                ]);

            return redirect()->route("Edit name", $data['product_id'])->with('success', 'Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route("Edit name", $data['product_id'])->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}