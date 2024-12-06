<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShiftAndTeamController extends Controller
{
    public $select_shifts = [
        ['select_name' => 'A'],
        ['select_name' => 'B'],
        ['select_name' => 'C'],
        ['select_name' => 'D'],
        ['select_name' => 'E'],
        ['select_name' => 'F'],
    ];

    private function GetShifts($date)
    {
        return DB::table('work_shift')
            ->whereDate('date', $date)
            ->get();
    }

    private function getFilteredShifts()
    {
        $work_shifts = DB::table('work_shift')->select('shift_name')->distinct()->pluck('shift_name')->toArray();

        return array_filter($this->select_shifts, function ($team) use ($work_shifts) {
            return !in_array($team['select_name'], $work_shifts);
        });
    }

    public function index()
    {
        $filtered_shifts = $this->getFilteredShifts();
        $ShiftFilterDate = $this->GetShifts(now()->format('Y-m-d'));

        return view('Admin.ManageShiftTeam.ManageShiftTeam', compact('filtered_shifts', 'ShiftFilterDate'));
    }

    public function ShiftFilterDate(Request $request)
    {
        $filtered_shifts = $this->getFilteredShifts();
        $ShiftFilterDate = $this->GetShifts($request->input('date') ?? now()->format('Y-m-d'));

        return view('Admin.ManageShiftTeam.ManageShiftTeam', compact('filtered_shifts', 'ShiftFilterDate'));
    }

    public function SaveAddShift(Request $request)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'start_shift' => 'required|date_format:H:i',
            'end_shift' => 'required|date_format:H:i',
            'date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        try {
            DB::table('work_shift')->insert([
                'shift_id' => Str::uuid(),
                'shift_name' => $request->input('shift_name'),
                'start_shift' => $request->input('start_shift'),
                'end_shift' => $request->input('end_shift'),
                'date' => $request->input('date'),
                'note' => $request->input('note'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('ManageShiftTeam')->with('success', 'Shift added successfully!');
        } catch (\Exception $e) {
            return redirect()->route('ManageShiftTeam')->with('error', 'Failed to add shift. Please try again later.');
        }
    }

    public function EditShiftTeam($Shift_id)
    {
        $ShiftTeams = DB::table('work_shift')
            ->leftJoin('lock_team', 'work_shift.shift_id', '=', 'lock_team.shift_id')
            ->leftJoin('lock_team_user', 'lock_team.team_id', '=', 'lock_team_user.team_id')
            ->leftJoin('users', 'lock_team_user.user_id', '=', 'users.user_id')
            ->where('work_shift.shift_id', $Shift_id)
            ->select(
                'work_shift.shift_id',
                'work_shift.shift_name',
                'work_shift.start_shift',
                'work_shift.end_shift',
                'work_shift.date',
                'work_shift.note',
                'lock_team.team_id',
                'lock_team.team_name',
                'lock_team.work',
                'lock_team.note as team_note',
                'users.user_id',
                'users.name',
                'users.surname',
                'users.position',
                'users.note',
                'lock_team_user.dmc_position'
            )
            ->get()
            ->groupBy('shift_id')
            ->map(function ($groupedShifts) {
                $firstShift = $groupedShifts->first();

                return [
                    'shift_id' => $firstShift->shift_id,
                    'shift_name' => $firstShift->shift_name,
                    'start_shift' => $firstShift->start_shift,
                    'end_shift' => $firstShift->end_shift,
                    'date' => $firstShift->date,
                    'note' => $firstShift->note,
                    'teams' => $groupedShifts->groupBy('team_id')->map(function ($teamGroup) {
                        $firstTeam = $teamGroup->first();

                        return [
                            'team_id' => $firstTeam->team_id,
                            'team_name' => $firstTeam->team_name,
                            'work' => $firstTeam->work,
                            'note' => $firstTeam->team_note,
                            'users' => $teamGroup->map(function ($user) {
                                return [
                                    'user_id' => $user->user_id,
                                    'name' => $user->name,
                                    'surname' => $user->surname,
                                    'position' => $user->position,
                                    'dmc_position' => $user->dmc_position,
                                    'note' => $user->note
                                ];
                            })->filter() //กรองข้อมูลเฉพาะ users ที่มีอยู่
                        ];
                    })->filter() //กรองข้อมูลทีมที่ไม่ null
                ];
            })->first();

        // dd($ShiftTeams);

        return view('Admin.ManageShiftTeam.EditShiftTeam', [
            'ShiftTeams' => $ShiftTeams,
        ]);
    }
}
