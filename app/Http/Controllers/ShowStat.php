<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowStat extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function GetProductTransactionsByMonth($month)
    {
        $year = substr($month, 0, 4);
        $monthOnly = substr($month, 5, 2);

        return DB::table('product_transactions')
            ->whereYear('datetime', $year)
            ->whereMonth('datetime', $monthOnly)
            ->selectRaw("CONVERT(date, datetime) as date") // แปลง datetime ให้เหลือเฉพาะวันที่
            ->groupBy(DB::raw('CONVERT(date, datetime)')) // groupBy เฉพาะวันที่
            ->get();
    }

    private function GetProductTransactionsByDate($date)
    {
        return DB::table('product_transactions')
            ->join('product', 'product_transactions.product_id', '=', 'product.product_id')
            ->whereDate('datetime', $date)
            ->get();
    }

    public function ProductTransactionsFilterMonth(Request $request)
    {
        $month = $request->input('month') ?? now()->format('Y-m');

        $ProductTransactionsFilterMonth = $this->GetProductTransactionsByMonth($month);

        return response()->json([
            'ProductTransactionsFilterMonth' => $ProductTransactionsFilterMonth
        ]);
    }

    public function show_date()
    {
        $show_per_date = $this->GetProductTransactionsByMonth(now()->format('Y-m'));
        return view('Stat.Statdate', compact('show_per_date'));
    }

    // public function show_stat_imported($date)
    // {
    //     // Fetch the product data in one query for the given date
    //     $data = DB::table('receipt_product')
    //         ->where('store_datetime', $date)
    //         ->where('receipt_product.status', 1)
    //         ->join('receipt_product_detail', 'receipt_product.receipt_product_id', '=', 'receipt_product_detail.receipt_product_id')
    //         ->join('product_stock', 'receipt_product_detail.product_id', '=', 'product_stock.product_id')
    //         ->join('product', 'product_stock.product_id', '=', 'product.product_id')
    //         ->selectRaw('
    //         product.product_um,
    //         product.product_um2,
    //         product_stock.product_id,
    //         product.product_description ,
    //         product.product_number,
    //         MAX(product_stock.quantity) as quantity,
    //         MAX(product_stock.quantity2) as quantity2,
    //         SUM(receipt_product_detail.quantity) as total_quantity,
    //         SUM(receipt_product_detail.quantity2) as total_quantity2
    //     ')
    //         ->groupBy('product_stock.product_id', 'product.product_description', 'product.product_um', 'product.product_um2', 'product.product_number') // เพิ่มการ group โดยใช้ทั้ง product_id และ product_name
    //         ->get();
    //     // dd($data);
    //     // Initialize the summary array
    //     $summary = [];

    //     // Iterate over the data to populate the summary
    //     foreach ($data as $item) {
    //         $productId = $item->product_id;
    //         $productName = $item->product_description;
    //         // Store the aggregated amounts and weights in the summary
    //         if (!isset($summary[$productId])) {
    //             $summary[$productId] = [
    //                 'product_number' => $item->product_number,
    //                 'product_name' => $productName,
    //                 'total_quantity' => $item->total_quantity,
    //                 'product_um' => $item->product_um,
    //                 'total_quantity2' => $item->total_quantity2,
    //                 'product_um2' => $item->product_um2,
    //                 'all_quantity'   => $item->quantity,
    //                 'all_quantity2'   => $item->quantity2
    //             ];
    //         } else {
    //             $summary[$productId]['total_quantity'] += $item->total_quantity;
    //             $summary[$productId]['total_quantity2'] += $item->total_quantity2;
    //         }
    //     }
    //     return view('Stat.ShowimportedStat', ['summary' => $summary, 'date' => $date]);
    // }

    // public function show_stat_dispense($date)
    // {
    //     $data = DB::table('confirmOrder')
    //         ->whereDate('confirmOrder.created_at', $date)
    //         ->join('product_stock', 'confirmOrder.product_id', '=', 'product_stock.product_id')
    //         ->join('product', 'confirmOrder.product_id', '=', 'product.product_id')
    //         ->selectRaw('
    //         product.product_um,
    //         product.product_um2,
    //         product_stock.product_id,
    //         product.product_description,
    //         product.product_number,
    //         MAX(product_stock.quantity) as quantity,
    //         MAX(product_stock.quantity2) as quantity2,
    //         SUM(confirmOrder.quantity) as total_quantity,
    //         SUM(confirmOrder.quantity2) as total_quantity2
    //     ')
    //         ->groupBy(
    //             'product_stock.product_id',
    //             'product.product_description',
    //             'product.product_number',
    //             'product.product_um',
    //             'product.product_um2'
    //         )
    //         ->get();
    //     //
    //     return view('Stat.ShowDispenseStat', compact(['data', 'date']));
    // }

    public function show_stat_imported($date)
    {
        $data = DB::table('product_stock')
            ->join('product_transactions', 'product_stock.product_id', '=', 'product_transactions.product_id')
            ->join('product', 'product_stock.product_id', '=', 'product.product_id')
            ->leftJoin('warehouse', 'product.warehouse_id', '=', 'warehouse.id')
            ->whereDate('product_transactions.datetime', $date)
            ->select(
                'product_stock.product_id',
                'product.product_number',
                'product.product_description',
                'product_stock.quantity',
                DB::raw("SUM(CASE WHEN product_transactions.transaction_type = 'IN' THEN product_transactions.quantity ELSE 0 END) as transaction_quantity_in"),
                DB::raw("SUM(CASE WHEN product_transactions.transaction_type = 'OUT' THEN product_transactions.quantity ELSE 0 END) as transaction_quantity_out"),
                'product.product_um',
                'product_stock.quantity2',
                DB::raw("SUM(CASE WHEN product_transactions.transaction_type = 'IN' THEN product_transactions.quantity2 ELSE 0 END) as transaction_quantity_in2"),
                DB::raw("SUM(CASE WHEN product_transactions.transaction_type = 'OUT' THEN product_transactions.quantity2 ELSE 0 END) as transaction_quantity_out2"),
                'product.product_um2',
                'warehouse.warehouse_name as warehouse',
            )
            ->groupBy(
                'product_stock.product_id',
                'product.product_number',
                'product.product_description',
                'product_stock.quantity',
                'product.product_um',
                'product_stock.quantity2',
                'product.product_um2',
                'warehouse.warehouse_name',
            )
            ->get();

        return view('Stat.ShowimportedStat', compact(['data', 'date']));
    }

    public function show_stat_dispense($date)
    {
        $data = DB::table('product_stock')
            ->join('product_transactions', 'product_stock.product_id', '=', 'product_transactions.product_id')
            ->join('product', 'product_stock.product_id', '=', 'product.product_id')
            ->leftJoin('warehouse', 'product.warehouse_id', '=', 'warehouse.id')
            ->whereDate('product_transactions.datetime', $date)
            ->select(
                'product_stock.product_id',
                'product.product_number',
                'product.product_description',
                'product_stock.quantity',
                DB::raw("SUM(CASE WHEN product_transactions.transaction_type = 'IN' THEN product_transactions.quantity ELSE 0 END) as transaction_quantity_in"),
                DB::raw("SUM(CASE WHEN product_transactions.transaction_type = 'OUT' THEN product_transactions.quantity ELSE 0 END) as transaction_quantity_out"),
                'product.product_um',
                'product_stock.quantity2',
                DB::raw("SUM(CASE WHEN product_transactions.transaction_type = 'IN' THEN product_transactions.quantity2 ELSE 0 END) as transaction_quantity_in2"),
                DB::raw("SUM(CASE WHEN product_transactions.transaction_type = 'OUT' THEN product_transactions.quantity2 ELSE 0 END) as transaction_quantity_out2"),
                'product.product_um2',
                'warehouse.warehouse_name as warehouse',
            )
            ->groupBy(
                'product_stock.product_id',
                'product.product_number',
                'product.product_description',
                'product_stock.quantity',
                'product.product_um',
                'product_stock.quantity2',
                'product.product_um2',
                'warehouse.warehouse_name as warehouse',
            )
            ->get();

        return view('Stat.ShowDispenseStat', compact(['data', 'date']));
    }
}
