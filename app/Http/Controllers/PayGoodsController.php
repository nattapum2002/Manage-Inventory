<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Termwind\Components\Raw;

class PayGoodsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function GetQueues()
    {
        $Queues = DB::table('orders')
            ->join('customer', 'orders.customer_id', '=', 'customer.customer_id')
            ->select(
                'orders.ship_datetime',
                'orders.customer_id',
                'customer.customer_name',
                'customer.customer_grade',
            )
            ->whereDate('orders.ship_datetime', now()->format('Y-m-d'))
            ->distinct()
            ->orderBy('orders.ship_datetime', 'asc');

        return $Queues;
    }

    private function GetPalletByCustomer($customer_id)
    {
        return DB::table('pallet')
            ->join('pallet_detail', 'pallet.id', '=', 'pallet_detail.pallet_id')
            ->join('product', 'pallet_detail.product_id', '=', 'product.product_id')
            ->join('orders', 'pallet.order_number', '=', 'orders.order_number')
            ->join('warehouse', 'product.warehouse_id', '=', 'warehouse.id')
            ->where('orders.customer_id', $customer_id)
            ->whereDate('orders.ship_datetime', today()) //today()
            ->select([
                'pallet.id as pallet_id',
                'pallet.order_number',
                'pallet.pallet_name',
                'pallet.pallet_type_id',
                'pallet.warehouse_id',
                'pallet.recipe_status',
                'pallet.arrange_pallet_status',
                'pallet_detail.product_id',
                'pallet_detail.product_number',
                'pallet_detail.quantity',
                'pallet_detail.quantity2',
                'orders.ship_datetime',
                'orders.customer_id',
                'product.product_description',
                'product.product_um',
                'product.product_um2',
                'warehouse.warehouse_name as warehouse',
            ])
            ->get()
            ->groupBy('pallet_id')
            ->map(function ($items) {
                $firstItem = $items->first();

                return [
                    'pallet_id' => $firstItem->pallet_id,
                    'pallet_name' => $firstItem->pallet_name,
                    'order_number' => $firstItem->order_number,
                    'warehouse' => $firstItem->warehouse,
                    'ship_datetime' => $firstItem->ship_datetime,
                    'recipe_status' => $firstItem->recipe_status,
                    'arrange_pallet_status' => $firstItem->arrange_pallet_status,
                    'products' => $items->map(fn($item) => [
                        'product_id' => $item->product_id,
                        'product_number' => $item->product_number,
                        'product_description' => $item->product_description,
                        'quantity' => $item->quantity,
                        'product_um' => $item->product_um,
                        'quantity2' => $item->quantity2,
                        'product_um2' => $item->product_um2,
                    ])->values(),
                ];
            })->values();
    }

    private function getCustomerQueues($date, $order_number = null)
    {
        $queue = DB::table('orders')
            ->join('customer', 'orders.customer_id', '=', 'customer.customer_id')
            ->whereDate('orders.ship_datetime', $date)
            ->when($order_number, fn($query, $order_number) => $query->where('orders.order_number', $order_number))
            ->select(
                'orders.order_number',
                'customer.customer_name',
                'customer.customer_grade',
                'orders.ship_datetime',
                DB::raw("FORMAT(orders.ship_datetime, 'HH:mm') as queue_time")
            )
            ->distinct()
            ->orderBy('orders.ship_datetime');

        if ($order_number) {
            return $queue->first();
        } else {
            return $queue->get();
        }
    }

    private function getTeams()
    {
        return DB::table('team')
            ->Join('team_user', 'team.team_id', '=', 'team_user.team_id')
            ->Join('users', 'team_user.user_id', '=', 'users.user_id')
            ->join('shift', 'team.shift_id', '=', 'shift.shift_id')
            ->leftJoin('incentive_transactions', 'incentive_transactions.user_id', '=', 'users.user_id')
            ->select(
                'shift.date',
                'users.name',
                'users.user_id',
                'incentive_transactions.incentive_id',
                'incentive_transactions.end_time'
            )
            ->where('team.team_name', 'ลากจ่ายหน้าลาน')
            ->whereDate('shift.date', today())
            ->get();
    }

    public function index()
    {
        $customer_queues = $this->getCustomerQueues(today());
        $teams = $this->getTeams();

        $queue = $this->GetQueues()->get();

        return view('Admin.PayGoods.PayGood', [
            'customer_queues' => $customer_queues,
            'teams' => $teams,
            'queue' => $queue,
        ]);
    }

    public function PayGoodsData(Request $request)
    {
        $queue = $this->GetQueues()
            ->where('orders.customer_id', $request->customer_id)
            // ->groupBy('customer_id')
            ->first();

        $pallet = $this->GetPalletByCustomer($request->customer_id);
        $teams = $this->getTeams();

        return response()->json([
            'queue' => $queue,
            'pallet' => $pallet,
            'teams' => $teams
        ]);
    }

    public function EndWork(Request $request)
    {
        // แปลงค่า products ที่ส่งมาเป็น JSON
        $products = collect(request()->input('products'))->map(fn($product) => json_decode($product, true));

        // ตรวจสอบว่ามีข้อมูลใน products หรือไม่
        if (empty($products)) {
            return redirect()->back()->with('error', 'ไม่มีข้อมูลสินค้า');
        }

        // คำนวณ sumQuantity
        $sumQuantity = 0;
        foreach ($products as $product) {
            if (isset($product['product_um'], $product['quantity']) && $product['product_um'] == 'Kg') {
                $sumQuantity += $product['quantity'];
            } elseif (isset($product['product_um2'], $product['quantity2']) && $product['product_um2'] == 'Kg') {
                $sumQuantity += $product['quantity2'];
            }
        }

        DB::table('pallet')
            ->where('id', $request->input('pallet_id'))
            ->update([
                'arrange_pallet_status' => true,
                'updated_at' => now(),
            ]);

        DB::table('incentive_transactions')->insert([
            'incentive_id' => Str::uuid(),
            'user_id' => $request->input('user_id'),
            'incentive_type' => 'Arrange',
            'weight' => $sumQuantity,
            'start_time' => now(),
            'end_time' => now()->addMinutes(1),
        ]);

        return redirect()->back()->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }
}