<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncentiveController extends Controller
{
    public function incentiveArrange()
    {
        $palletDate = DB::table('pallet')->select(DB::raw('CONVERT(DATE,created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('CONVERT(DATE, created_at)'))
            ->get();
        // dd($palletDate);
        return view('Admin.ManageIncentive.IncentiveArrange', compact('palletDate'));
    }

    // public function incentiveArrangeWorker($date)
    // {
    //     $worker = DB::table('team_user')
    //         ->select('users.name', 'users.surname', 'users.user_id', 'team.team_name', DB::raw('CONVERT(DATE,pallet.created_at) as date'))
    //         ->join('users', 'users.user_id', '=', 'team_user.user_id')
    //         ->join('team', 'team.team_id', '=', 'team_user.team_id')
    //         ->join('pallet', 'pallet.team_id', '=', 'team.team_id')
    //         ->groupBy('users.name', 'users.surname', 'users.user_id', 'team.team_name', DB::raw('CONVERT(DATE,pallet.created_at)'))
    //         ->whereDate('pallet.created_at', '=', $date)
    //         ->where('team_user.dmc_position', '=', 'Arrange')
    //         ->where('pallet.status', 1)
    //         ->get();
    //     return view('Admin.ManageIncentive.IncentiveArrangeWorker', compact('worker', 'date'));
    // }

    public function incentiveArrangeWorker($date)
    {
        $worker = DB::table('team_user')
            ->select(
                'users.name',
                'users.surname',
                'users.user_id',
                'team.team_name',
                DB::raw('CONVERT(DATE,pallet.created_at) as date')
            )
            ->join('users', 'users.user_id', '=', 'team_user.user_id')
            ->join('team', 'team.team_id', '=', 'team_user.team_id')
            ->join('pallet_team', 'team.team_id', '=', 'pallet_team.team_id')
            ->join('pallet', 'pallet_team.pallet_id', '=', 'pallet.pallet_id')
            ->groupBy('users.name', 'users.surname', 'users.user_id', 'team.team_name', DB::raw('CONVERT(DATE,pallet.created_at)'))
            ->whereDate('pallet.created_at', '=', $date)
            ->where('team_user.dmc_position', '=', 'Arrange')
            ->where('pallet.arrange_pallet_status', 1)
            ->get();
        return view('Admin.ManageIncentive.IncentiveArrangeWorker', compact('worker', 'date'));
    }

    // public function incentiveArrangeWorkerDetail($date, $user_id)
    // {
    //     $arrangeincentive = DB::table('team_user')
    //         ->select(
    //             'users.name',
    //             'users.surname',
    //             'users.user_id',
    //             'product.product_description',
    //             'product.product_number',
    //             'product.product_id',
    //             'product.product_um',
    //             'product.product_um2',
    //             'confirmOrder.quantity',
    //             'confirmOrder.quantity2',
    //             'customer.customer_name',
    //             'warehouse.warehouse',
    //             'product_work_desc.product_work_desc',
    //             'pallet.order_date',
    //         )
    //         ->join('users', 'users.user_id', '=', 'team_user.user_id')
    //         ->join('team', 'team.team_id', '=', 'team_user.team_id')
    //         ->join('pallet', 'pallet.team_id', '=', 'team.team_id')
    //         ->join('confirmOrder', 'pallet.id', '=', 'confirmOrder.pallet_id')
    //         ->join('product_work_desc', 'confirmOrder.product_work_desc', '=', 'product_work_desc.id')
    //         ->join('product', 'confirmOrder.product_id', '=', 'product.product_id')
    //         ->join('warehouse', 'pallet.room', '=', 'warehouse.id')
    //         ->join('customer', 'pallet.customer_id', '=', 'customer.customer_id')
    //         ->whereDate('pallet.created_at', '=', $date)
    //         ->where('users.user_id', '=', $user_id)
    //         ->where('pallet.status', 1)
    //         ->get();

    //     $incentive_data = $this->incentive_Arrange_split($arrangeincentive);
    //     //dd($arrangeincentive);

    //     // dd($quantityKg,$total_incentive_Kg);

    //     return view('Admin.ManageIncentive.IncentiveArrangeWorkerDetail', compact('incentive_data', 'arrangeincentive', 'date'));
    // }

    public function incentiveArrangeWorkerDetail($date, $user_id)
    {
        $arrangeincentive = DB::table('team_user')
            ->select(
                'users.name',
                'users.surname',
                'users.user_id',
                'product.product_description',
                'product.product_number',
                'product.product_id',
                'product.product_um',
                'product.product_um2',
                'pallet_detail.quantity',
                'pallet_detail.quantity2',
                'customer.customer_name',
                'pallet.warehouse',
                'product.product_work_desc',
                'order_date.order_date',
            )
            ->join('users', 'users.user_id', '=', 'team_user.user_id')
            ->join('team', 'team.team_id', '=', 'team_user.team_id')
            ->join('pallet_team', 'team.team_id', '=', 'pallet_team.team_id')
            ->join('pallet', 'pallet_team.pallet_id', '=', 'pallet.pallet_id')
            ->join('pallet_detail', 'pallet_detail.pallet_id', '=', 'pallet.pallet_id')
            ->join('orders', 'pallet.order_number', '=', 'orders.order_number')
            ->join('product', 'orders.product_id', '=', 'product.product_id')
            ->join('customer', 'pallet.customer_id', '=', 'customer.customer_id')
            ->whereDate('pallet.created_at', '=', $date)
            ->where('users.user_id', '=', $user_id)
            ->where('pallet.arrange_pallet_status', 1)
            ->get();

        $incentive_data = $this->incentive_Arrange_split($arrangeincentive);
        //dd($arrangeincentive);

        // dd($quantityKg,$total_incentive_Kg);

        return view('Admin.ManageIncentive.IncentiveArrangeWorkerDetail', compact('incentive_data', 'arrangeincentive', 'date'));
    }

    function incentive_Arrange_split($arrangeincentive)
    {
        $total_incentive_Org = 0;
        $total_incentive_Spl = 0;
        $total_incentive_Bl = 0;
        foreach ($arrangeincentive as $key => $value) {
            if ($value->product_work_desc === 'แยกจ่าย') {
                $this->incentive_cal($value->quantity, $total_incentive_Org, 0.009);
            } else if ($value->product_work_desc === 'รับจัด') {
                $this->incentive_cal($value->quantity, $total_incentive_Spl, 0.01);
            }
        }
        return [
            'total_incentive_Org' => $total_incentive_Org,
            'total_incentive_Spl' => $total_incentive_Spl,
        ];
    }

    function incentive_cal($quantity, &$total, $incentive)
    {
        $total += $quantity * $incentive;
    }

    public function incentiveDrag()
    {
        $palletMonth = DB::table('pallet')->select(
            DB::raw('DATENAME(MONTH, created_at) as month_name'),
            DB::raw('MONTH(created_at) as month_number'),
            DB::raw('YEAR(created_at) as year_number'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy(DB::raw('DATENAME(MONTH, created_at)'), DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();
        // dd($palletMonth);
        return view('Admin.ManageIncentive.IncentiveDrag', compact('palletMonth'));
    }

    // public function incentiveDragWorker($month, $year)
    // {
    //     $worker = DB::table('team_user')
    //         ->select(
    //             'users.name',
    //             'users.surname',
    //             'users.user_id',
    //             'team.team_name',
    //             DB::raw('MONTH(pallet.created_at) as month'),
    //             DB::raw('DATENAME(MONTH, pallet.created_at) as month_name'),
    //             DB::raw('YEAR(pallet.created_at) as year_number')
    //         )
    //         ->join('users', 'users.user_id', '=', 'team_user.user_id')
    //         ->join('team', 'team.team_id', '=', 'team_user.team_id')
    //         ->join('pallet', 'pallet.team_id', '=', 'team.team_id')
    //         ->whereMonth('pallet.created_at', '=', $month)
    //         ->whereYear('pallet.created_at', '=', $year)
    //         ->where('team_user.dmc_position', '=', 'Drag')
    //         ->where('pallet.status', 1)
    //         ->groupBy(
    //             'users.name',
    //             'users.surname',
    //             'users.user_id',
    //             'team.team_name',
    //             DB::raw('MONTH(pallet.created_at)'),
    //             DB::raw('DATENAME(MONTH, pallet.created_at)'),
    //             DB::raw('YEAR(pallet.created_at)')
    //         )
    //         ->get();
    //     // dd($worker);
    //     return view('Admin.ManageIncentive.IncentiveDragWorker', compact('worker', 'month', 'year'));
    // }

    public function incentiveDragWorker($month, $year)
    {
        $worker = DB::table('team_user')
            ->select(
                'users.name',
                'users.surname',
                'users.user_id',
                'team.team_name',
                DB::raw('MONTH(pallet.created_at) as month'),
                DB::raw('DATENAME(MONTH, pallet.created_at) as month_name'),
                DB::raw('YEAR(pallet.created_at) as year_number')
            )
            ->join('users', 'users.user_id', '=', 'team_user.user_id')
            ->join('team', 'team.team_id', '=', 'team_user.team_id')
            ->join('pallet_team', 'team.team_id', '=', 'pallet_team.team_id')
            ->join('pallet', 'pallet_team.pallet_id', '=', 'pallet.pallet_id')
            ->whereMonth('pallet.created_at', '=', $month)
            ->whereYear('pallet.created_at', '=', $year)
            ->where('team_user.dmc_position', '=', 'Drag')
            ->where('pallet.recive_status', 1)
            ->groupBy(
                'users.name',
                'users.surname',
                'users.user_id',
                'team.team_name',
                DB::raw('MONTH(pallet.created_at)'),
                DB::raw('DATENAME(MONTH, pallet.created_at)'),
                DB::raw('YEAR(pallet.created_at)')
            )
            ->get();
        // dd($worker);
        return view('Admin.ManageIncentive.IncentiveDragWorker', compact('worker', 'month', 'year'));
    }

    // public function incentiveDragWorkerDetail($month, $year, $user_id)
    // {
    //     $Dragincentive = DB::table('team_user')
    //         ->select(
    //             'users.name',
    //             'users.surname',
    //             'users.user_id',
    //             'pallet.order_id',
    //             'product.product_description',
    //             'product.product_number',
    //             'pallet_order.product_id',
    //             'product.product_um',
    //             'product.product_um2',
    //             'confirmOrder.quantity',
    //             'confirmOrder.quantity2',
    //             DB::raw('DATENAME(MONTH, pallet.created_at) as month_name'),
    //         )
    //         ->join('users', 'users.user_id', '=', 'team_user.user_id')
    //         ->join('team', 'team.team_id', '=', 'team_user.team_id')
    //         ->join('pallet', 'pallet.team_id', '=', 'team.team_id')
    //         ->join('pallet_order', 'pallet.id', '=', 'pallet_order.pallet_id')
    //         ->join('product', 'pallet_order.product_id', '=', 'product.product_id')
    //         ->join('confirmOrder', 'pallet_order.id', '=', 'confirmOrder.pallet_order_id')
    //         ->whereMonth('pallet.created_at', '=', $month)
    //         ->whereYear('pallet.created_at', '=', $year)
    //         ->where('users.user_id', '=', $user_id)
    //         ->where('pallet.recive_status', 1)
    //         ->get();

    //     $quantityKg = [];
    //     $quantityCtn = [];
    //     $total_incentive_Kg = 0;
    //     $total_incentive_Ctn = 0;
    //     $total_incentive_BAG = 0;
    //     foreach ($Dragincentive as $key => $value) {
    //         if ($value->product_um == 'Kg' || $value->product_um2 == 'Kg') {
    //             $quantityKg[$key] = [
    //                 'product_id' => $value->product_id,
    //                 'product_name' => $value->product_description,
    //                 'quantity' => $value->quantity
    //             ];

    //             if ($value->product_um == 'Kg') {
    //                 $total_incentive_Kg += $value->quantity;
    //             } else if ($value->product_um2 == 'Kg') {
    //                 $total_incentive_Kg += $value->quantity2;
    //             }
    //         }
    //         // elseif ($value->product_um == 'Ctn') {
    //         //     $quantityCtn[$key] = [
    //         //         'product_id' => $value->product_id,
    //         //         'product_name' => $value->product_description,
    //         //         'quantity' => $value->quantity
    //         //     ];

    //         //     $total_incentive_Ctn += $value->quantity;
    //         // }
    //     }
    //     // dd($Dragincentive);
    //     return view('Admin.ManageIncentive.IncentiveDragWorkerDetail', compact('Dragincentive', 'total_incentive_Kg', 'year'));
    // }

    public function incentiveDragWorkerDetail($month, $year, $user_id)
    {
        $Dragincentive = DB::table('team_user')
            ->select(
                'users.name',
                'users.surname',
                'users.user_id',
                'pallet.order_id',
                'product.product_description',
                'product.product_number',
                'pallet_order.product_id',
                'product.product_um',
                'product.product_um2',
                'pallet_detail.quantity',
                'pallet_detail.quantity2',
                DB::raw('DATENAME(MONTH, pallet.created_at) as month_name'),
            )
            ->join('users', 'users.user_id', '=', 'team_user.user_id')
            ->join('team', 'team.team_id', '=', 'team_user.team_id')
            ->join('pallet_team', 'team.team_id', '=', 'pallet_team.team_id')
            ->join('pallet', 'pallet_team.pallet_id', '=', 'pallet.pallet_id')
            ->join('pallet_detail', 'pallet.pallet_id', '=', 'pallet_detail.pallet_id')
            ->join('product', 'pallet_order.product_id', '=', 'product.product_id')
            ->whereMonth('pallet.created_at', '=', $month)
            ->whereYear('pallet.created_at', '=', $year)
            ->where('users.user_id', '=', $user_id)
            ->where('pallet.recive_status', 1)
            ->get();

        $quantityKg = [];
        $quantityCtn = [];
        $total_incentive_Kg = 0;
        $total_incentive_Ctn = 0;
        $total_incentive_BAG = 0;
        foreach ($Dragincentive as $key => $value) {
            if ($value->product_um == 'Kg' || $value->product_um2 == 'Kg') {
                $quantityKg[$key] = [
                    'product_id' => $value->product_id,
                    'product_name' => $value->product_description,
                    'quantity' => $value->quantity
                ];

                if ($value->product_um == 'Kg') {
                    $total_incentive_Kg += $value->quantity;
                } else if ($value->product_um2 == 'Kg') {
                    $total_incentive_Kg += $value->quantity2;
                }
            }
            // elseif ($value->product_um == 'Ctn') {
            //     $quantityCtn[$key] = [
            //         'product_id' => $value->product_id,
            //         'product_name' => $value->product_description,
            //         'quantity' => $value->quantity
            //     ];

            //     $total_incentive_Ctn += $value->quantity;
            // }
        }
        // dd($Dragincentive);
        return view('Admin.ManageIncentive.IncentiveDragWorkerDetail', compact('Dragincentive', 'total_incentive_Kg', 'year'));
    }
}