<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayGoodsController extends Controller
{
    public function index()
    {
        $customer_queues = DB::table('customer_queue')
            ->join('customer_order', 'customer_queue.order_number', '=', 'customer_order.order_number')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->where('queue_date', now()->format('Y-m-d'))
            ->select('customer_queue.queue_time', 'customer_queue.order_number', 'customer.customer_name', 'customer.customer_grade')
            ->orderBy('customer_queue.queue_time')
            ->get();

        $auto_select_queue = $customer_queues
            ->filter(fn($queue) => $queue->queue_time >= now()->toTimeString())
            ->sortBy('queue_time')
            ->first();

        $pallets_with_products = DB::table('pallet')
            ->join('pallet_order', 'pallet.pallet_id', '=', 'pallet_order.pallet_id')
            ->join('product', 'pallet_order.product_id', '=', 'product.item_id')
            ->join('confirmOrder', 'pallet_order.pallet_id', '=', 'confirmOrder.id')
            ->where('pallet.order_id', $auto_select_queue->order_number)
            ->select(
                'pallet.pallet_id',
                'pallet.pallet_no',
                'pallet.room',
                'pallet_order.product_id',
                'product.item_id',
                'product.item_desc1',
                'confirmOrder.quantity',
                'product.item_um',
                'confirmOrder.quantity2',
                'product.item_um2',
            )
            ->orderBy('pallet.pallet_id')
            ->get()
            ->groupBy('pallet_id')
            ->map(function ($items, $pallet_id) {
                $firstItem = $items->first(); // ใช้สำหรับดึงข้อมูลพาเลท

                return [
                    'pallet_no' => $firstItem->pallet_no,
                    'room' => $firstItem->room,
                    'products' => $items->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'item_id' => $item->item_id,
                            'item_desc1' => $item->item_desc1,
                            'quantity' => $item->quantity,
                            'item_um' => $item->item_um,
                            'quantity2' => $item->quantity2,
                            'item_um2' => $item->item_um2,
                        ];
                    })
                ];
            });

        $total_pallets = $pallets_with_products->count();

        return view('Admin.PayGoods.PayGoods', [
            'customer_queues' => $customer_queues,
            'select_queue' => null,
            'auto_select_queue' => $auto_select_queue,
            'pallets_with_products' => $pallets_with_products,
            'total_pallets' => $total_pallets
        ]);
    }

    public function SelectPayGoods($order_number)
    {
        $customer_queues = DB::table('customer_queue')
            ->join('customer_order', 'customer_queue.order_number', '=', 'customer_order.order_number')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->where('queue_date', now()->format('Y-m-d'))
            ->select('customer_queue.queue_time', 'customer_queue.order_number', 'customer.customer_name', 'customer.customer_grade')
            ->orderBy('customer_queue.queue_time')
            ->get();

        $select_queue = $customer_queues
            ->firstWhere('order_number', $order_number);

        $auto_select_queue = $customer_queues
            ->filter(fn($queue) => $queue->queue_time >= now()->toTimeString())
            ->sortBy('queue_time')
            ->first();

        $pallets_with_products = DB::table('pallet')
            ->join('pallet_order', 'pallet.pallet_id', '=', 'pallet_order.pallet_id')
            ->join('product', 'pallet_order.product_id', '=', 'product.item_id')
            ->join('confirmOrder', 'pallet_order.pallet_id', '=', 'confirmOrder.id')
            ->where('pallet.order_id', $order_number)
            ->select(
                'pallet.pallet_id',
                'pallet.pallet_no',
                'pallet.room',
                'pallet_order.product_id',
                'product.item_id',
                'product.item_desc1',
                'confirmOrder.quantity',
                'product.item_um',
                'confirmOrder.quantity2',
                'product.item_um2',
            )
            ->orderBy('pallet.pallet_id')
            ->get()
            ->groupBy('pallet_id')
            ->map(function ($items, $pallet_id) {
                $firstItem = $items->first(); // ใช้สำหรับดึงข้อมูลพาเลท

                return [
                    'pallet_no' => $firstItem->pallet_no,
                    'room' => $firstItem->room,
                    'products' => $items->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'item_id' => $item->item_id,
                            'item_desc1' => $item->item_desc1,
                            'quantity' => $item->quantity,
                            'item_um' => $item->item_um,
                            'quantity2' => $item->quantity2,
                            'item_um2' => $item->item_um2,
                        ];
                    })
                ];
            });


        $total_pallets = $pallets_with_products->count();

        return view('Admin.PayGoods.PayGoods', [
            'customer_queues' => $customer_queues,
            'select_queue' => $select_queue,
            'auto_select_queue' => $auto_select_queue,
            'total_pallets' => $total_pallets,
            'pallets_with_products' => $pallets_with_products
        ]);
    }
}
