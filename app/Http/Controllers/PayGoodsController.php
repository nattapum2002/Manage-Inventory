<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function Laravel\Prompts\error;

class PayGoodsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function GetQueues()
    {
        return DB::table('orders')
            ->join('customer', 'orders.customer_id', '=', 'customer.customer_id')
            ->select(
                'orders.ship_datetime',
                'orders.customer_id',
                'customer.customer_name',
                'customer.customer_grade'
            )
            ->whereDate('orders.ship_datetime', now()->format('Y-m-d'))
            ->distinct()
            ->orderBy('orders.ship_datetime', 'asc');
    }

    private function GetPallet()
    {
        return DB::table('pallet')
            // ->join('pallet_detail', 'pallet.pallet_id', '=', 'pallet_detail.pallet_id')
            // ->join('product', 'pallet_detail.product_id', '=', 'product.product_id')
            ->get();
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

    private function getSelectedQueue($customer_queues, $order_number)
    {
        return $customer_queues->firstWhere('order_number', $order_number);
    }

    private function getPalletsWithProducts($order_number)
    {
        return DB::table('pallet')
            ->Join('pallet_detail', 'pallet.pallet_id', '=', 'pallet_detail.pallet_id')
            ->Join('product', 'pallet_detail.product_id', '=', 'product.product_id')
            ->leftJoin('warehouse', 'product.warehouse_id', '=', 'warehouse.id')
            // ->join('pallet_team', 'pallet.pallet_id', '=', 'pallet_team.pallet_id')
            // ->where('pallet.order_number', $order_number)
            ->select(
                'pallet.pallet_id',
                'pallet.order_number',
                'pallet.pallet_name',
                'warehouse.warehouse_name as warehouse',
                // 'pallet_team.team_id',
                'pallet_detail.product_id',
                'product.product_id',
                'product.product_description',
                'pallet_detail.quantity',
                'product.product_um',
                'pallet_detail.quantity2',
                'product.product_um2'
            )
            ->orderBy('pallet.pallet_id')
            ->get()
            ->groupBy('pallet_id')
            ->map(function ($items) {
                $firstItem = $items->first();

                return [
                    'pallet_no' => $firstItem->pallet_name,
                    'order_number' => $firstItem->order_number,
                    'room' => $firstItem->warehouse,
                    // 'team_id' => $firstItem->team_id,
                    'products' => $items->map(fn($item) => [
                        'product_id' => $item->product_id,
                        'product_id' => $item->product_id,
                        'product_description' => $item->product_description,
                        'quantity' => $item->quantity,
                        'product_um' => $item->product_um,
                        'quantity2' => $item->quantity2,
                        'product_um2' => $item->product_um2,
                    ]),
                ];
            });
    }

    private function getTeams()
    {
        return DB::table('team')
            ->Join('team_user', 'team.team_id', '=', 'team_user.team_id')
            ->Join('users', 'team_user.user_id', '=', 'users.user_id')
            ->leftJoin('incentive_transactions', 'incentive_transactions.user_id', '=', 'users.user_id')
            ->where('team.team_name', 'ลากจ่ายหน้าลาน')
            ->select(
                'users.name',
                'users.user_id',
                'incentive_transactions.incentive_id',
                'incentive_transactions.end_time'
            )
            ->get();
    }

    public function index()
    {
        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i:s');

        $customer_queues = $this->getCustomerQueues($today);
        // $select_queue = $this->getCustomerQueues($today, '11512002591');
        // dd($select_queue);
        $teams = $this->getTeams();

        $queue = $this->GetQueues()->get();
        // dd($queue);

        return view('Admin.PayGoods.PayGoods', [
            'customer_queues' => $customer_queues,
            'select_queue' => null,
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

        $pallet = $this->GetPallet();

        return response()->json([
            'queue' => $queue,
            'pallet' => $pallet,
            'all' => $request->all(),
        ]);
    }

    public function SelectPayGoods(Request $request)
    {
        // return response()->json($request->all());
        $queueId = $request->queueId;
        $today = now()->format('Y-m-d');
        $currentTime = now()->toTimeString();

        $select_queue = $this->getCustomerQueues($today, $queueId);
        $pallets_with_products = $this->getPalletsWithProducts($select_queue->order_number);
        $teams = $this->getTeams();

        if (!$select_queue || !$pallets_with_products) {
            return response()->json(['error' => 'ไม่พบข้อมูล'], 404);
        }

        return response()->json([
            'select_queue' => $select_queue,
            'pallets_with_products' => $pallets_with_products,
            'teams' => $teams,
        ]);
    }

    // public function SelectPayGoods(Request $request)
    // {

    //     $customer_queues = DB::table('customer_queue')
    //         ->join('customer_order', 'customer_queue.order_number', '=', 'customer_order.order_number')
    //         ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
    //         ->where('queue_date', now()->format('Y-m-d'))
    //         ->select('customer_queue.queue_time', 'customer_queue.order_number', 'customer.customer_name', 'customer.customer_grade')
    //         ->orderBy('customer_queue.queue_time')
    //         ->get();

    //     $select_queue = $customer_queues
    //         ->firstWhere('order_number', $order_number);

    //     $auto_select_queue = $customer_queues
    //         ->filter(fn($queue) => $queue->queue_time >= now()->toTimeString())
    //         ->sortBy('queue_time')
    //         ->first();

    //     $pallets_with_products = DB::table('pallet')
    //         ->join('pallet_order', 'pallet.pallet_id', '=', 'pallet_order.pallet_id')
    //         ->join('product', 'pallet_order.product_id', '=', 'product.product_id')
    //         ->join('orders', 'pallet_order.pallet_id', '=', 'orders.id')
    //         ->where('pallet.order_number', $order_number)
    //         ->select(
    //             'pallet.pallet_id',
    //             'pallet.pallet_no',
    //             'pallet.room',
    //             'pallet.team_id',
    //             'pallet.towing_staff_id',
    //             'pallet_order.product_id',
    //             'product.product_id',
    //             'product.product_description',
    //             'orders.quantity',
    //             'product.product_um',
    //             'orders.quantity2',
    //             'product.product_um2',
    //         )
    //         ->orderBy('pallet.pallet_id')
    //         ->get()
    //         ->groupBy('pallet_id')
    //         ->map(function ($items, $pallet_id) {
    //             $firstItem = $items->first(); // ใช้สำหรับดึงข้อมูลพาเลท

    //             return [
    //                 'pallet_no' => $firstItem->pallet_no,
    //                 'room' => $firstItem->room,
    //                 'team_id' => $firstItem->team_id,
    //                 'towing_staff_id' => $firstItem->towing_staff_id,
    //                 'products' => $items->map(function ($item) {
    //                     return [
    //                         'product_id' => $item->product_id,
    //                         'product_id' => $item->product_id,
    //                         'product_description' => $item->product_description,
    //                         'quantity' => $item->quantity,
    //                         'product_um' => $item->product_um,
    //                         'quantity2' => $item->quantity2,
    //                         'product_um2' => $item->product_um2,
    //                     ];
    //                 })
    //             ];
    //         });

    //     $total_pallets = $pallets_with_products->count();

    //     // $teams = DB::table('team')
    //     //     ->join('team_user', 'team.team_id', '=', 'team_user.team_id')
    //     //     ->join('users', 'team_user.user_id', '=', 'users.user_id')
    //     //     ->where('team.work', 'DragDropGoods')
    //     //     ->select('users.name', 'users.user_id')
    //     //     ->get();

    //     // $incentives = DB::table('incentive_transactions')->first();

    //     $teams = DB::table('team')
    //         ->join('team_user', 'team.team_id', '=', 'team_user.team_id')
    //         ->join('users', 'team_user.user_id', '=', 'users.user_id')
    //         ->leftJoin('incentive_transactions', 'incentive_transactions.user_id', '=', 'users.user_id') // ดึงข้อมูล incentive_transactions
    //         ->where('team.work', 'DragDropGoods')
    //         ->select('users.name', 'users.user_id', 'incentive_transactions.incentive_id', 'incentive_transactions.end_time')
    //         ->get();

    //     // dd($teams);

    //     return view('Admin.PayGoods.PayGoods', [
    //         'customer_queues' => $customer_queues,
    //         'select_queue' => $select_queue,
    //         'auto_select_queue' => $auto_select_queue,
    //         'total_pallets' => $total_pallets,
    //         'pallets_with_products' => $pallets_with_products,
    //         'teams' => $teams,
    //     ]);
    // }

    public function StartWork(Request $request)
    {
        $incentive_id = Str::uuid();

        DB::table('incentive_transactions')->insert([
            'incentive_id' => $incentive_id,
            'user_id' => $request->input('user_id'),
            'start_time' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('SelectPayGoods', $request->input('order_number'))->with('incentive_id', $incentive_id);
    }

    public function EndWork(Request $request)
    {
        // แปลงค่า products ที่ส่งมาเป็น JSON
        $products = json_decode($request->input('products'), true);

        // ตรวจสอบว่ามีข้อมูลใน products หรือไม่
        if (empty($products)) {
            return redirect()->route('SelectPayGoods')->with('error', 'ไม่มีข้อมูลสินค้า');
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

        // อัพเดตข้อมูลเมื่อจบงาน
        DB::table('incentive_transactions')
            ->where('incentive_id', $request->input('incentive_id'))
            ->update([
                'incentive_value' => $sumQuantity,
                'end_time' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->route('SelectPayGoods', $request->input('order_number'));
    }
}