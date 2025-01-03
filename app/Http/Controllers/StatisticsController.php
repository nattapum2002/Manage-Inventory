<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $Users = DB::table('users')->get();
        $product_stores = DB::table('product_store')
            ->join('product_store_detail', 'product_store.product_slip_id', '=', 'product_store_detail.product_slip_id')
            ->get();
        $stocks = DB::table('stock')
            ->join('product', 'stock.product_id', '=', 'product.item_id')
            ->get();
        $customer_orders = DB::table('customer_order')->get();
        $pallets = DB::table('pallet')->get();
        $customer_queues = DB::table('customer_queue')->get();

        if (Auth::check()) {
            return view(Auth::user()->user_type . '.Dashboard.index', compact('Users', 'product_stores', 'stocks', 'customer_orders', 'pallets', 'customer_queues'));
        } else {
            Auth::logout();
            return redirect()->back()->with('error', 'สิทธิ์ไม่เพียงพอ');
        }
    }

    public function ProductStore()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $product_stores = DB::table('product_store')->get();
        return view('Manager.ProductStore.ProductStore', compact('product_stores'));
    }

    public function DetailProductStore($slip_id)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $product_stores = DB::table('product_store')->where('product_slip_id', $slip_id)->get();
        return view('Manager.ProductStore.DetailProductStore', compact('product_stores'));
    }

    public function ProductStock()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $stocks = DB::table('stock')->get();
        return view('Manager.ProductStock.ProductStock', compact('stocks'));
    }

    public function CustomerOrder()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $customer_orders = DB::table('customer_order')->select('order_id', 'customer_id', 'date', 'packer_id')->distinct()->get();
        return view('Manager.CustomerOrder.customerorder', compact('customer_orders'));
    }

    public function DetailCustomerOrder($order_id)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $customer_orders = DB::table('customer_order')->where('order_id', $order_id)->get();
        return view('Manager.CustomerOrder.detailcustomerorder', compact('customer_orders'));
    }

    public function Pallet()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $pallets = DB::table('pallet')->distinct()->get();
        return view('Manager.Pallet.Pallet', compact('pallets'));
    }

    public function DetailPallet($pallet_id)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $pallets = DB::table('pallet')->where('pallet_id', $pallet_id)->get();
        return view('Manager.Pallet.DetailPallet', compact('pallets'));
    }
}