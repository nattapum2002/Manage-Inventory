<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PayGoodsController extends Controller
{
    private function getCustomerQueues($today)
    {
        $queues = DB::table('customer_queue')
            ->leftJoin('customer_order', 'customer_queue.order_number', '=', 'customer_order.order_number')
            ->leftJoin('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->where('queue_date', $today)
            ->select(
                'customer_queue.queue_time',
                'customer_queue.order_number',
                'customer.customer_name',
                'customer.customer_grade'
            )
            ->orderBy('customer_queue.queue_time')
            ->get();

        $formattedQueues = $queues->map(function ($queue) {
            return [
                'queue_time' => \Carbon\Carbon::parse($queue->queue_time)->format('H:i'),
                'order_number' => intval($queue->order_number),
                'customer_name' => $queue->customer_name,
                'customer_grade' => $queue->customer_grade ?? 'N/A', // กรณีค่าเป็น null
            ];
        });

        return $formattedQueues;
    }

    private function getSelectedQueue($customer_queues, $order_number)
    {
        return $customer_queues->firstWhere('order_number', $order_number);
    }

    private function getAutoSelectQueue($customer_queues, $currentTime)
    {
        return $customer_queues
            ->filter(fn($queue) => $queue['queue_time'] >= $currentTime)
            ->sortBy('queue_time')
            ->first();
    }

    private function getPalletsWithProducts($order_number)
    {
        return DB::table('pallet')
            ->leftJoin('pallet_order', 'pallet.pallet_id', '=', 'pallet_order.pallet_id')
            ->leftJoin('product', 'pallet_order.product_id', '=', 'product.item_id')
            ->leftJoin('confirmOrder', 'pallet_order.pallet_id', '=', 'confirmOrder.id')
            ->where('confirmOrder.order_id', $order_number)
            ->select(
                'pallet.pallet_id',
                'pallet.pallet_no',
                'pallet.room',
                'pallet.team_id',
                'pallet_order.product_id',
                'product.item_id',
                'product.item_desc1',
                'confirmOrder.quantity',
                'product.item_um',
                'confirmOrder.quantity2',
                'product.item_um2'
            )
            ->orderBy('pallet.pallet_id')
            ->get()
            ->groupBy('pallet_id')
            ->map(function ($items) {
                $firstItem = $items->first();

                return [
                    'pallet_no' => $firstItem->pallet_no,
                    'room' => $firstItem->room,
                    'team_id' => $firstItem->team_id,
                    'products' => $items->map(fn($item) => [
                        'product_id' => $item->product_id,
                        'item_id' => $item->item_id,
                        'item_desc1' => $item->item_desc1,
                        'quantity' => $item->quantity,
                        'item_um' => $item->item_um,
                        'quantity2' => $item->quantity2,
                        'item_um2' => $item->item_um2,
                    ]),
                ];
            });
    }

    private function getTeams()
    {
        return DB::table('lock_team')
            ->leftJoin('lock_team_user', 'lock_team.team_id', '=', 'lock_team_user.team_id')
            ->leftJoin('users', 'lock_team_user.user_id', '=', 'users.user_id')
            ->leftJoin('incentive_log', 'incentive_log.user_id', '=', 'users.user_id')
            ->where('lock_team.work', 'DragDropGoods')
            ->select(
                'users.name',
                'users.user_id',
                'incentive_log.incentive_id',
                'incentive_log.end_time'
            )
            ->get();
    }

    public function index()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $today = now()->format('Y-m-d');
        $currentTime = now()->toTimeString();

        $customer_queues = $this->getCustomerQueues($today);
        $auto_select_queue = $this->getAutoSelectQueue($customer_queues, $currentTime);
        $pallets_with_products = $this->getPalletsWithProducts(optional($auto_select_queue)->order_number);
        $teams = $this->getTeams();

        return view('Admin.PayGoods.PayGoods', [
            'customer_queues' => $customer_queues,
            'select_queue' => null,
            'auto_select_queue' => $auto_select_queue,
            'pallets_with_products' => $pallets_with_products,
            'total_pallets' => $pallets_with_products->count(),
            'teams' => $teams,
        ]);
    }

    public function SelectPayGoods(Request $request)
    {
        $queueId = $request->id;

        $today = now()->format('Y-m-d');
        $currentTime = now()->toTimeString();

        $customer_queues = $this->getCustomerQueues($today);
        $select_queue = $this->getSelectedQueue($customer_queues, $queueId);

        if (!$select_queue) {
            return response()->json(['error' => 'ไม่พบข้อมูล'], 404);
        }

        return response()->json(['select_queue' => $select_queue]);
    }

    // public function SelectPayGoods(Request $request)
    // {
    //     // Ensure the user is authenticated
    //     if (!Auth::user()) {
    //         return redirect()->route('Login.index');
    //     }

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
    //         ->join('product', 'pallet_order.product_id', '=', 'product.item_id')
    //         ->join('confirmOrder', 'pallet_order.pallet_id', '=', 'confirmOrder.id')
    //         ->where('pallet.order_id', $order_number)
    //         ->select(
    //             'pallet.pallet_id',
    //             'pallet.pallet_no',
    //             'pallet.room',
    //             'pallet.team_id',
    //             'pallet.towing_staff_id',
    //             'pallet_order.product_id',
    //             'product.item_id',
    //             'product.item_desc1',
    //             'confirmOrder.quantity',
    //             'product.item_um',
    //             'confirmOrder.quantity2',
    //             'product.item_um2',
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
    //                         'item_id' => $item->item_id,
    //                         'item_desc1' => $item->item_desc1,
    //                         'quantity' => $item->quantity,
    //                         'item_um' => $item->item_um,
    //                         'quantity2' => $item->quantity2,
    //                         'item_um2' => $item->item_um2,
    //                     ];
    //                 })
    //             ];
    //         });

    //     $total_pallets = $pallets_with_products->count();

    //     // $teams = DB::table('lock_team')
    //     //     ->join('lock_team_user', 'lock_team.team_id', '=', 'lock_team_user.team_id')
    //     //     ->join('users', 'lock_team_user.user_id', '=', 'users.user_id')
    //     //     ->where('lock_team.work', 'DragDropGoods')
    //     //     ->select('users.name', 'users.user_id')
    //     //     ->get();

    //     // $incentives = DB::table('incentive_log')->first();

    //     $teams = DB::table('lock_team')
    //         ->join('lock_team_user', 'lock_team.team_id', '=', 'lock_team_user.team_id')
    //         ->join('users', 'lock_team_user.user_id', '=', 'users.user_id')
    //         ->leftJoin('incentive_log', 'incentive_log.user_id', '=', 'users.user_id') // ดึงข้อมูล incentive_log
    //         ->where('lock_team.work', 'DragDropGoods')
    //         ->select('users.name', 'users.user_id', 'incentive_log.incentive_id', 'incentive_log.end_time')
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

        DB::table('incentive_log')->insert([
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
            if (isset($product['item_um'], $product['quantity']) && $product['item_um'] == 'Kg') {
                $sumQuantity += $product['quantity'];
            } elseif (isset($product['item_um2'], $product['quantity2']) && $product['item_um2'] == 'Kg') {
                $sumQuantity += $product['quantity2'];
            }
        }

        // อัพเดตข้อมูลเมื่อจบงาน
        DB::table('incentive_log')
            ->where('incentive_id', $request->input('incentive_id'))
            ->update([
                'incentive_value' => $sumQuantity,
                'end_time' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->route('SelectPayGoods', $request->input('order_number'));
    }
}
