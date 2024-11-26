<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowStat extends Controller
{
    //
    public function show_date()
    {
        $show_per_date = DB::table('product_store')
            ->selectRaw('store_date') // หรือเลือกฟิลด์ที่คุณต้องการ
            ->groupBy('store_date')
            ->get();
        return view('Stat.Statdate', compact('show_per_date'));
    }

    public function show_stat_imported($date)
    {
        // Fetch the product data in one query for the given date
        $data = DB::table('product_store')
            ->where('store_date', $date)
            ->where('product_store.status', 1)
            ->join('product_store_detail', 'product_store.product_slip_id', '=', 'product_store_detail.product_slip_id')
            ->join('stock', 'product_store_detail.product_id', '=', 'stock.product_id')
            ->join('product', 'stock.product_id', '=', 'product.item_id')
            ->selectRaw('
            product.item_um,
            product.item_um2,
            stock.product_id,
            product.item_desc1 ,
            product.item_no,
            MAX(stock.quantity) as quantity,
            MAX(stock.quantity2) as quantity2,
            SUM(product_store_detail.quantity) as total_quantity,
            SUM(product_store_detail.quantity2) as total_quantity2
        ')
            ->groupBy('stock.product_id', 'product.item_desc1', 'product.item_um', 'product.item_um2', 'product.item_no') // เพิ่มการ group โดยใช้ทั้ง product_id และ product_name
            ->get();
        // dd($data);
        // Initialize the summary array
        $summary = [];

        // Iterate over the data to populate the summary
        foreach ($data as $item) {
            $productId = $item->product_id;
            $productName = $item->item_desc1;
            // Store the aggregated amounts and weights in the summary
            if (!isset($summary[$productId])) {
                $summary[$productId] = [
                    'item_no' => $item->item_no,
                    'product_name' => $productName,
                    'total_quantity' => $item->total_quantity,
                    'item_um' => $item->item_um,
                    'total_quantity2' => $item->total_quantity2,
                    'item_um2' => $item->item_um2,
                    'all_quantity'   => $item->quantity,
                    'all_quantity2'   => $item->quantity2
                ];
            } else {
                $summary[$productId]['total_quantity'] += $item->total_quantity;
                $summary[$productId]['total_quantity2'] += $item->total_quantity2;
            }
        }
        return view('Stat.ShowimportedStat', ['summary' => $summary, 'date' => $date]);
    }
    public function show_stat_dispense($date)
    {

        $data = DB::table('confirmOrder')
        ->whereDate('confirmOrder.created_at', $date)
        ->join('stock', 'confirmOrder.product_id', '=', 'stock.product_id')
        ->join('product', 'confirmOrder.product_id', '=', 'product.item_id')
        ->selectRaw('
            product.item_um,
            product.item_um2,
            stock.product_id,
            product.item_desc1,
            product.item_no,
            MAX(stock.quantity) as quantity,
            MAX(stock.quantity2) as quantity2,
            SUM(confirmOrder.quantity) as total_quantity,
            SUM(confirmOrder.quantity2) as total_quantity2
        ')
        ->groupBy(
            'stock.product_id',
            'product.item_desc1',
            'product.item_no',
            'product.item_um',
            'product.item_um2'
        )
        ->get();
        // dd($data);
        return view('Stat.ShowDispenseStat', compact(['data', 'date']));
    }
}
