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

    public function incentiveArrangeWorker($date)
    {
        $worker = DB::table('lock_team_user')
            ->select('users.name', 'users.surname', 'users.user_id', 'lock_team.team_name', DB::raw('CONVERT(DATE,pallet.created_at) as date'))
            ->join('users', 'users.user_id', '=', 'lock_team_user.user_id')
            ->join('lock_team', 'lock_team.id', '=', 'lock_team_user.team_id')
            ->join('pallet', 'pallet.team_id', '=', 'lock_team.id')
            ->groupBy('users.name', 'users.surname', 'users.user_id', 'lock_team.team_name', DB::raw('CONVERT(DATE,pallet.created_at)'))
            ->whereDate('pallet.created_at', '=', $date)
            ->where('lock_team_user.dmc_position', '=', 'Arrange')
            ->where('pallet.status', 1)
            ->get();
        return view('Admin.ManageIncentive.IncentiveArrangeWorker', compact('worker', 'date'));
    }

    public function incentiveArrangeWorkerDetail($date, $user_id)
    {
        $arrangeincentive = DB::table('lock_team_user')
            ->select(
                'users.name',
                'users.surname',
                'users.user_id',
                'pallet.order_id',
                'product.item_desc1',
                'product.item_no',
                'pallet_order.product_id',
                'product.item_um',
                'product.item_um2',
                'confirmOrder.quantity',
                'confirmOrder.quantity2',
                )
            ->join('users', 'users.user_id', '=', 'lock_team_user.user_id')
            ->join('lock_team', 'lock_team.id', '=', 'lock_team_user.team_id')
            ->join('pallet', 'pallet.team_id', '=', 'lock_team.id')
            ->join('pallet_order', 'pallet.id', '=', 'pallet_order.pallet_id')
            ->join('product', 'pallet_order.product_id', '=', 'product.item_id')
            ->join('confirmOrder', 'pallet_order.id', '=', 'confirmOrder.pallet_order_id')
            ->whereDate('pallet.created_at', '=', $date)
            ->where('users.user_id', '=', $user_id)
            ->where('pallet.status', 1)
            ->get();

        $quantityKg = [];
        $quantityCtn = [];
        $total_incentive_Kg = 0;
        $total_incentive_Ctn = 0;
        $total_incentive_BAG = 0;
        foreach ($arrangeincentive as $key => $value) {
            if ($value->item_um == 'Kg' || $value->item_um2 == 'Kg') {
                $quantityKg[$key] = [
                    'product_id' => $value->product_id,
                    'product_name' => $value->item_desc1,
                    'quantity' => $value->quantity
                ];

                if ($value->item_um == 'Kg') {
                    $total_incentive_Kg += $value->quantity;
                } else if ($value->item_um2 == 'Kg') {
                    $total_incentive_Kg += $value->quantity2;
                }
            } 
            // elseif ($value->item_um == 'Ctn') {
            //     $quantityCtn[$key] = [
            //         'product_id' => $value->product_id,
            //         'product_name' => $value->item_desc1,
            //         'quantity' => $value->quantity
            //     ];

            //     $total_incentive_Ctn += $value->quantity;
            // }
        }
        // dd($quantityKg,$total_incentive_Kg);
        // dd($date);
        return view('Admin.ManageIncentive.IncentiveArrangeWorkerDetail',compact('arrangeincentive','total_incentive_Kg' ,'date'));
    }

    public function incentiveDrag()
    {
        $palletMonth = DB::table('pallet') ->select(
            DB::raw('DATENAME(MONTH, created_at) as month_name'),
            DB::raw('MONTH(created_at) as month_number'),
            DB::raw('YEAR(created_at) as year_number'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy(DB::raw('DATENAME(MONTH, created_at)'), DB::raw('MONTH(created_at)'),DB::raw('YEAR(created_at)'))
        ->orderBy(DB::raw('MONTH(created_at)'))
        ->get();
        // dd($palletMonth);
        return view('Admin.ManageIncentive.IncentiveDrag',compact('palletMonth'));
    }
    public function incentiveDragWorker($month , $year)
    {
        $worker = DB::table('lock_team_user')
        ->select(
            'users.name',
            'users.surname',
            'users.user_id',
            'lock_team.team_name',
            DB::raw('MONTH(pallet.created_at) as month'),
            DB::raw('DATENAME(MONTH, pallet.created_at) as month_name'),
            DB::raw('YEAR(pallet.created_at) as year_number')
        )
        ->join('users', 'users.user_id', '=', 'lock_team_user.user_id')
        ->join('lock_team', 'lock_team.id', '=', 'lock_team_user.team_id')
        ->join('pallet', 'pallet.team_id', '=', 'lock_team.id')
        ->whereMonth('pallet.created_at', '=', $month)
        ->whereYear('pallet.created_at', '=', $year)
        ->where('lock_team_user.dmc_position', '=', 'Drag')
        ->where('pallet.status', 1)
        ->groupBy(
            'users.name',
            'users.surname',
            'users.user_id',
            'lock_team.team_name',
            DB::raw('MONTH(pallet.created_at)'),
            DB::raw('DATENAME(MONTH, pallet.created_at)'),
            DB::raw('YEAR(pallet.created_at)')
        )
        ->get();
        // dd($worker);
        return view('Admin.ManageIncentive.IncentiveDragWorker',compact('worker', 'month', 'year'));
    }
    public function incentiveDragWorkerDetail($month, $year, $user_id)
    {
        $Dragincentive = DB::table('lock_team_user')
            ->select(
                'users.name',
                'users.surname',
                'users.user_id',
                'pallet.order_id',
                'product.item_desc1',
                'product.item_no',
                'pallet_order.product_id',
                'product.item_um',
                'product.item_um2',
                'confirmOrder.quantity',
                'confirmOrder.quantity2',
                DB::raw('DATENAME(MONTH, pallet.created_at) as month_name'),
                )
            ->join('users', 'users.user_id', '=', 'lock_team_user.user_id')
            ->join('lock_team', 'lock_team.id', '=', 'lock_team_user.team_id')
            ->join('pallet', 'pallet.team_id', '=', 'lock_team.id')
            ->join('pallet_order', 'pallet.id', '=', 'pallet_order.pallet_id')
            ->join('product', 'pallet_order.product_id', '=', 'product.item_id')
            ->join('confirmOrder', 'pallet_order.id', '=', 'confirmOrder.pallet_order_id')
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
            if ($value->item_um == 'Kg' || $value->item_um2 == 'Kg') {
                $quantityKg[$key] = [
                    'product_id' => $value->product_id,
                    'product_name' => $value->item_desc1,
                    'quantity' => $value->quantity
                ];

                if ($value->item_um == 'Kg') {
                    $total_incentive_Kg += $value->quantity;
                } else if ($value->item_um2 == 'Kg') {
                    $total_incentive_Kg += $value->quantity2;
                }
            } 
            // elseif ($value->item_um == 'Ctn') {
            //     $quantityCtn[$key] = [
            //         'product_id' => $value->product_id,
            //         'product_name' => $value->item_desc1,
            //         'quantity' => $value->quantity
            //     ];

            //     $total_incentive_Ctn += $value->quantity;
            // }
        }
        // dd($Dragincentive);
        return view('Admin.ManageIncentive.IncentiveDragWorkerDetail',compact('Dragincentive','total_incentive_Kg','year'));
    }
}
