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
        ->select('users.name','users.surname','users.user_id','lock_team.team_name',DB::raw('CONVERT(DATE,pallet.created_at) as date'))
        ->join('users', 'users.user_id', '=', 'lock_team_user.user_id')
        ->join('lock_team', 'lock_team.id', '=', 'lock_team_user.team_id')
        ->join('pallet', 'pallet.team_id', '=', 'lock_team.id')
        ->groupBy('users.name','users.surname','users.user_id','lock_team.team_name',DB::raw('CONVERT(DATE,pallet.created_at)'))
        ->whereDate('pallet.created_at','=',$date)
        ->where('pallet.status',1)
        ->get();
        return view('Admin.ManageIncentive.IncentiveArrangeWorker', compact('worker','date'));
    }
}
