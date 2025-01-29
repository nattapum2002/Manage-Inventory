<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserWorkController extends Controller
{
    public function index()
    {
        $data = DB::table('pallet')
            ->select(
                'pallet.status as pallet_status',
                'pallet.pallet_id as pallet_id',
                'pallet.id',
                'pallet.pallet_no',
                'pallet.room',
                'pallet.customer_id',
                'pallet.order_date',
                'customer.customer_name',
                'pallet_type.pallet_type',
                'team.team_name',
            )
            ->join('customer', 'pallet.customer_id', '=', 'customer.customer_id')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->join('team', 'pallet.team_id', '=', 'team.team_id')
            ->join('team_user', 'team.team_id', '=', 'team_user.team_id')
            ->where('team_user.user_id', '=', auth()->user()->user_id)
            ->where('dmc_position', '=', 'Arrange')
            ->orderByRaw('CASE WHEN pallet.status = 1 THEN 1 ELSE 0 END ASC')
            ->get();


        // dd($data);
        return view('Employee.Pallet.showpallet', compact('data'));
    }
    public function showPalletDetail($pallet_id)
    {
        $Pallets = DB::table('confirmOrder')
            ->select(
                'customer.customer_name',
                'pallet.id',
                'pallet.pallet_id',
                'pallet.pallet_no',
                'pallet.room',
                'pallet_type.pallet_type',
                'pallet.status',
                'product.product_description',
                'product.product_number',
                'product.product_um',
                'product.product_um2',
                'product_work_desc.product_work_desc',
                'warehouse.warehouse_name as warehouse',
                'confirmOrder.quantity',
            )
            ->join('pallet', 'confirmOrder.pallet_id', '=', 'pallet.id')
            ->join('product', 'confirmOrder.product_id', '=', 'product.product_id')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->join('customer', 'pallet.customer_id', '=', 'customer.customer_id')
            ->join('product_work_desc', 'confirmOrder.product_work_desc', '=', 'product_work_desc.id')
            ->leftJoin('warehouse', 'product.warehouse_id', '=', 'warehouse.id')
            ->where('confirmOrder.pallet_id', '=', $pallet_id)
            ->get();

        $team = DB::table('team')
            ->select('team.team_name', 'users.name', 'users.surname') // เพิ่ม 'users.surname'
            ->join('team_user', 'team.team_id', '=', 'team_user.team_id')
            ->join('users', 'team_user.user_id', '=', 'users.user_id')
            ->join('pallet', 'team.team_id', '=', 'pallet.team_id')
            ->where('pallet.id', '=', $pallet_id) // แก้เงื่อนไข where
            ->get();

        //  dd($team);
        $groupedTeams = [];
        foreach ($team as $teams) {
            $groupedTeams[$teams->team_name][] = [
                'name' => $teams->name,
                'surname' => $teams->surname,
            ];
        }

        // แสดงผลข้อมูล (กรณี Debug)
        //dd($Pallets);
        return view('Employee.Pallet.showpalletDetail', compact('groupedTeams', 'Pallets'));
    }

    public function submitPallet($pallet_id)
    {
        DB::table('pallet')->where('id', $pallet_id)->update(['status' => 1]);

        return redirect()->back()->with('success', 'Data saved successfully');
    }
}