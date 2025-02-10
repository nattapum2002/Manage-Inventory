<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function GetProductTransactions()
    {
        return DB::table('product_transactions')
            ->join('product', 'product_transactions.product_id', '=', 'product.product_id')
            ->get();
    }

    private function GetProductReceipts()
    {
        return DB::table('receipt_product')
            ->join('receipt_product_detail', 'receipt_product.receipt_product_id', '=', 'receipt_product_detail.receipt_product_id')
            ->join('product', 'receipt_product_detail.product_id', '=', 'product.product_id')
            ->get();
    }

    private function GetProductStocks()
    {
        return DB::table('product_stock')
            ->join('product', 'product_stock.product_id', '=', 'product.product_id')
            ->where('quantity', '>', 0)
            ->orderBy('product_stock.updated_at', 'desc')
            ->get();
    }

    private function GetOrders()
    {
        return DB::table('orders')
            ->join('customer', 'orders.customer_id', '=', 'customer.customer_id')
            ->get();
    }

    private function GetEmployee()
    {
        return DB::table('users')
            ->get();
    }

    public function index()
    {
        $ProductTransactions = $this->GetProductTransactions();
        $product_stores = $this->GetProductReceipts();
        $stocks = $this->GetProductStocks();
        $customer_orders = $this->GetOrders();

        $Users = DB::table('users')->get();

        if (Auth::check()) {
            return view(Auth::user()->user_type . '.Dashboard.index', compact(
                'ProductTransactions',
                'Users',
                'product_stores',
                'stocks',
                'customer_orders'
            ));
        } else {
            Auth::logout();
            return redirect()->back()->with('error', 'สิทธิ์ไม่เพียงพอ');
        }
    }

    public function ProductStore()
    {
        $product_stores = DB::table('receipt_product')->get();
        return view('Manager.ProductStore.ProductStore', compact('product_stores'));
    }

    public function DetailProductStore($slip_id)
    {
        $product_stores = DB::table('receipt_product')->where('receipt_product_id', $slip_id)->get();
        return view('Manager.ProductStore.DetailProductStore', compact('product_stores'));
    }

    public function ProductStock()
    {
        $stocks = $this->GetProductStocks();
        return view('Manager.ProductStock.ProductStock', compact('stocks'));
    }

    public function CustomerOrder()
    {
        $customer_orders = $this->GetOrders();
        return view('Manager.CustomerOrder.customerorder', compact('customer_orders'));
    }

    public function DetailCustomerOrder($order_number)
    {
        $customer_orders = DB::table('orders')
            ->join('customer', 'orders.customer_id', '=', 'customer.customer_id')
            ->join('order_detail', 'orders.order_number', '=', 'order_detail.order_number')
            ->join('product', 'order_detail.product_id', '=', 'product.product_id')
            ->select(
                'orders.order_number',
                'orders.queue_number',
                'orders.ship_datetime',
                'orders.entry_datetime',
                'orders.release_datetime',
                'orders.order_number',
                'customer.customer_id',
                'customer.customer_name',
                'customer.customer_grade',
                'product.product_id',
                'product.product_number',
                'product.product_description',
                'order_detail.quantity',
                'product.product_um',
                'order_detail.quantity2',
                'product.product_um2'
            )
            ->where('orders.order_number', $order_number)
            ->get()
            ->groupBy('order_number')->map(function ($groupOrder) {
                $firstOrder = $groupOrder->first();
                return [
                    'order_number' => $firstOrder->order_number,
                    'customer_id' => $firstOrder->customer_id,
                    'customer_name' => $firstOrder->customer_name,
                    'customer_grade' => $firstOrder->customer_grade,
                    'ship_datetime' => $firstOrder->ship_datetime,
                    'entry_datetime' => $firstOrder->entry_datetime,
                    'release_datetime' => $firstOrder->release_datetime,
                    'products' => $groupOrder->groupBy('product_id')->map(function ($groupItem) {
                        $firstItem = $groupItem->first();
                        return [
                            'product_id' => $firstItem->product_id,
                            'product_number' => $firstItem->product_number,
                            'product_description' => $firstItem->product_description,
                            'quantity' => $groupItem->sum('quantity'),
                            'product_um' => $firstItem->product_um,
                            'quantity2' => $groupItem->sum('quantity2'),
                            'product_um2' => $firstItem->product_um2,
                        ];
                    })->filter(),
                ];
            })->first();

        return view('Manager.CustomerOrder.detailcustomerorder', compact('customer_orders'));
    }

    public function Employee()
    {
        $users = $this->GetEmployee();
        return view('Manager.Employee.Employee', compact('users'));
    }
}
