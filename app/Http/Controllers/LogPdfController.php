<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use function PHPSTORM_META\map;

class LogPdfController extends Controller
{
    //
    public function LogPdfDownload(Request $request)
    {
        $orderNumber = $request->query('order_number');
        $orderDate = $request->query('order_date');
        $cusId = $request->query('cusId');
        $data = $this->FormatData($orderNumber ,$orderDate,$cusId);
        $pdf = Pdf::loadView('pdf/lockPdf', compact('data'));

        return $pdf->stream('LockPdf.pdf');
    }

    public function FormatData($orderNumber,$orderDate,$cusId)
    {
        $data = DB::table('pallet')
            ->leftJoin('orders','orders.order_number','=','pallet.order_number')
            ->join('pallet_detail','pallet_detail.pallet_id','=','pallet.id')
            ->leftJoin('pallet_team', 'pallet_team.pallet_id', '=', 'pallet.id')
            ->leftJoin('team_user', 'team_user.team_id', '=', 'pallet_team.team_id')
            ->leftJoin('users', 'users.user_id', '=', 'team_user.user_id')
            ->leftJoin('order_detail', function ($join) {
                $join->on('order_detail.order_number', '=', 'pallet.order_number')
                     ->on('order_detail.product_number', '=', 'pallet_detail.product_number'); // ✅ ป้องกันข้อมูลซ้ำ
            })
            ->join('product','product.product_number','=','pallet_detail.product_number')
            ->join('customer','customer.customer_id','=','orders.customer_id')
            ->join('warehouse','warehouse.id','=','pallet.warehouse_id')
            ->join('pallet_type','pallet_type.id','=','pallet.pallet_type_id')
            ->whereDate('orders.order_date',$orderDate)
            ->where('pallet.order_number','LIKE', '%'.$orderNumber.'%')
            ->where('orders.customer_id',$cusId)
            ->select(
                'pallet.id as pallet_pr_id',
                'pallet.order_number',
                'pallet.pallet_type_id',
                'pallet.pallet_desc',
                'pallet_detail.product_number',
                'pallet_detail.quantity as pallet_quantity',
                'order_detail.quantity as order_quantity',
                'orders.order_date',
                'product.product_description',
                'customer.customer_name',
                'customer.customer_grade',
                'warehouse.warehouse_name',
                'pallet_type.pallet_type',
                'users.name as user_name'
            )
            ->distinct()
            ->get()
            ->groupBy('pallet_pr_id')
            ->map(function($orders ,$pallet_id){
                return [
                    'pallet_id' => $pallet_id,
                    'pallet_type' => $orders->first()->pallet_type,
                    'order_number' => $orders->first()->order_number,
                    'customer' => [
                        'name' => $orders->first()->customer_name,
                        'grade' => $orders->first()->customer_grade,
                    ],
                    'team' => $orders->pluck('user_name')->unique()->values(),
                    'order_date' => $orders->first()->order_date,
                    'warehouse' => $orders->first()->warehouse_name,
                    'order_details' => $orders->unique('product_number')->map(function($details){
                        return [
                            'product_number' => $details->product_number,
                            'product_name' => $details->product_description,
                            'quantity' => $details->pallet_quantity,
                        ];
                    })->values()
                ];
            })->values();

       //dd($data);
        return $data;
    }
}
