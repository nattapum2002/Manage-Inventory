<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserWorkController extends Controller
{
    public function index()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $data = DB::table('pallet')
            ->select(
                'pallet.status as pallet_status',
                'pallet.pallet_id as pallet_id',
                'pallet.id',
                'pallet.pallet_no',
                'pallet.order_id',
                'pallet.room',
                'customer.customer_name',
                'pallet_type.pallet_type',
                'lock_team.team_name',
            )
            ->join('customer_order', 'pallet.order_id', '=', 'customer_order.order_number')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->join('lock_team', 'pallet.team_id', '=', 'lock_team.team_id')
            ->join('lock_team_user', 'lock_team.team_id', '=', 'lock_team_user.team_id')
            ->where('lock_team_user.user_id', '=', auth()->user()->user_id)
            ->where('dmc_position', '=', 'Arrange')
            ->orderByRaw('CASE WHEN pallet.status = 1 THEN 1 ELSE 0 END ASC')
            ->get();

        // dd($data);
        return view('Employee.Pallet.showpallet', compact('data'));
    }
    public function showPalletDetail($pallet_id, $order_id)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $Pallets = DB::table('pallet_order')
            ->select(
                'customer.customer_name',
                'pallet.id',
                'pallet.pallet_no',
                'pallet.room',
                'pallet_type.pallet_type',
                'pallet.status',
                'product.item_desc1',
                'product.item_no',
                'product.item_um',
                'product.item_um2',
                'confirmOrder.quantity',
                'confirmOrder.quantity2',
            )
            ->join('product', 'pallet_order.product_id', '=', 'product.item_id')
            ->join('pallet', 'pallet_order.pallet_id', '=', 'pallet.id')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->join('confirmOrder', 'pallet_order.id', '=', 'confirmOrder.pallet_order_id')
            ->join('customer_order', 'confirmOrder.order_id', '=', 'customer_order.order_number')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->leftJoin('customer_order_detail', function ($join) use ($order_id) {
                $join->on('pallet_order.product_id', '=', 'customer_order_detail.product_id')
                    ->where('customer_order_detail.order_number', '=', $order_id);
            })
            ->where('pallet_order.pallet_id', '=', $pallet_id)
            ->get();

        $team = DB::table('lock_team')
            ->select('lock_team.team_name', 'users.name', 'users.surname') // เพิ่ม 'users.surname'
            ->join('lock_team_user', 'lock_team.team_id', '=', 'lock_team_user.team_id')
            ->join('users', 'lock_team_user.user_id', '=', 'users.user_id')
            ->join('pallet', 'lock_team.team_id', '=', 'pallet.team_id')
            ->where('pallet.id', '=', $pallet_id) // แก้เงื่อนไข where
            ->get();

        // dd($team);
        $groupedTeams = [];
        foreach ($team as $teams) {
            $groupedTeams[$teams->team_name][] = [
                'name' => $teams->name,
                'surname' => $teams->surname,
            ];
        }

        // แสดงผลข้อมูล (กรณี Debug)
        // dd($Pallets);
        return view('Employee.Pallet.showpalletDetail', compact('groupedTeams', 'Pallets'));
    }

    public function submitPallet($pallet_id)
    {
        DB::table('pallet')->where('id', $pallet_id)->update(['status' => 1]);

        return redirect()->back()->with('success', 'Data saved successfully');
    }
}