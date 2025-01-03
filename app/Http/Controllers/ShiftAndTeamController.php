<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShiftAndTeamController extends Controller
{
    private function GetShiftsByMonth($month)
    {
        $year = substr($month, 0, 4);
        $monthOnly = substr($month, 5, 2);

        return DB::table('work_shift')
            ->whereYear('date', $year)
            ->whereMonth('date', $monthOnly)
            ->get();
    }

    public function ShiftFilter(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        $work_shifts = DB::table('work_shift')
            ->whereDate('date', $date)
            ->select('shift_name')
            ->distinct()
            ->pluck('shift_name')
            ->toArray();

        $all_shifts = [
            ['select_name' => 'A'],
            ['select_name' => 'B'],
            ['select_name' => 'C'],
            ['select_name' => 'D'],
            ['select_name' => 'E'],
            ['select_name' => 'F'],
        ];

        $filtered_shifts = array_filter($all_shifts, function ($shift) use ($work_shifts) {
            return !in_array($shift['select_name'], $work_shifts);
        });

        return response()->json(array_values($filtered_shifts));
    }

    public function index(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $ShiftFilterMonth = $this->GetShiftsByMonth($request->input('date') ?? now()->format('Y-m'));

        return view('Admin.ManageShiftTeam.ManageShiftTeam', compact('ShiftFilterMonth'));
    }

    public function ShiftFilterMonth(Request $request)
    {
        // รับค่าเดือนจากคำขอ หรือใช้เดือนปัจจุบันหากไม่ได้ระบุ
        $month = $request->input('month') ?? now()->format('Y-m');

        // ดึงข้อมูลกะตามเดือน
        $ShiftFilterMonth = $this->GetShiftsByMonth($month);

        // ส่งคืน JSON
        return response()->json([
            'ShiftFilterMonth' => $ShiftFilterMonth
        ]);
    }

    public function AddShift(Request $request)
    {
        // 1️⃣ ตรวจสอบความถูกต้องของข้อมูล
        $request->validate([
            'shift_name' => 'required',
            'start_shift' => 'required|date_format:H:i',
            'end_shift' => 'required|date_format:H:i',
            'day' => 'required|date_format:d',
            'month' => 'required|date_format:Y-m',
            'note' => 'nullable|string',
        ], [
            'shift_name.required' => 'กรุณาเลือกชื่อกะ',
            'start_shift.required' => 'กรุณาเลือกเวลาเริ่มกะ',
            'end_shift.required' => 'กรุณาเลือกเวลาสิ้นสุดกะ',
            'day.required' => 'กรุณาเลือกวันที่',
        ]);

        $monthYear = $request->input('month');
        $day = $request->input('day');

        [$year, $month] = explode('-', $monthYear);

        $date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');

        $data = array_merge($request->all(), ['date' => $date]);

        // dd($data);

        // 2️⃣ ตรวจสอบว่ามีกะที่ชื่อเหมือนกันแล้วหรือไม่
        $existingShift = DB::table('work_shift')
            ->where('shift_name', $request->input('shift_name'))
            ->orderBy('date', 'desc')
            ->first();

        if ($existingShift) {
            // ดึงข้อมูลที่เกี่ยวข้องกับกะนี้
            $ShiftTeams = DB::table('work_shift')
                ->leftJoin('lock_team', 'work_shift.shift_id', '=', 'lock_team.shift_id')
                ->leftJoin('lock_team_user', 'lock_team.team_id', '=', 'lock_team_user.team_id')
                ->leftJoin('users', 'lock_team_user.user_id', '=', 'users.user_id')
                ->where('work_shift.shift_id', $existingShift->shift_id)
                ->select(
                    'work_shift.shift_id',
                    'work_shift.shift_name',
                    'work_shift.start_shift',
                    'work_shift.end_shift',
                    'work_shift.date',
                    'work_shift.note as shift_note',
                    'lock_team.team_id',
                    'lock_team.team_name',
                    'lock_team.work',
                    'lock_team.note as team_note',
                    'users.user_id',
                    'users.name',
                    'users.surname',
                    'users.position',
                    'users.note as user_note',
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
                        'note' => $firstShift->shift_note,
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
                                        'note' => $user->user_note
                                    ];
                                })->filter() //กรองข้อมูลเฉพาะ users ที่มีอยู่
                            ];
                        })->filter() //กรองข้อมูลทีมที่ไม่ null
                    ];
                })->first();

            return redirect()->route('ManageShiftTeam')
                ->with('duplicate_shift', $existingShift) // ส่งข้อมูลกะล่าสุดไปเพื่อแสดงใน modal
                ->with('ShiftTeams', $ShiftTeams)
                ->with('Data', $data)
                ->with('info', 'มีกะที่มีชื่อซ้ำกัน คุณต้องการคัดลอกข้อมูลหรือเพิ่มข้อมูลใหม่');
        }

        try {
            DB::table('work_shift')->insert([
                'shift_id' => Str::uuid(),
                'shift_name' => $request->input('shift_name'),
                'start_shift' => $request->input('start_shift'),
                'end_shift' => $request->input('end_shift'),
                'date' => $date,
                'note' => $request->input('note'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('ManageShiftTeam')
                ->with('success', 'ข้อมูลกะได้ถูกเพิ่มเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->route('ManageShiftTeam')
                ->with('error', 'มีข้อผิดพลาดในการเพิ่มข้อมูลกะ');
        }
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

            return redirect()->route('ManageShiftTeam')
                ->with('success', 'ข้อมูลกะได้ถูกเพิ่มเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->route('ManageShiftTeam')
                ->with('error', 'มีข้อผิดพลาดในการเพิ่มข้อมูลกะ');
        }
    }

    public function CopyShiftAndTeam(Request $request)
    {
        // 1️⃣ ตรวจสอบความถูกต้องของข้อมูล
        $request->validate([
            'shift_id' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 2️⃣ ดึงข้อมูลกะต้นฉบับ
                $originalShift = DB::table('work_shift')->where('shift_id', $request->input('shift_id'))->first();

                // 3️⃣ สร้างข้อมูลกะใหม่
                $newShiftId = Str::uuid();
                DB::table('work_shift')->insert([
                    'shift_id' => $newShiftId,
                    'shift_name' => $originalShift->shift_name,
                    'start_shift' => $originalShift->start_shift,
                    'end_shift' => $originalShift->end_shift,
                    'date' => $request->input('date'), // ตั้งวันที่ใหม่
                    'note' => $originalShift->note,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 4️⃣ คัดลอกทีมทั้งหมดในกะต้นฉบับ
                $originalTeams = DB::table('lock_team')->where('shift_id', $originalShift->shift_id)->get();

                foreach ($originalTeams as $team) {
                    $newTeamId = Str::uuid();
                    DB::table('lock_team')->insert([
                        'team_id' => $newTeamId,
                        'team_name' => $team->team_name,
                        'work' => $team->work,
                        'shift_id' => $newShiftId,
                        'note' => $team->note,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // 5️⃣ คัดลอกสมาชิกในทีม
                    $teamUsers = DB::table('lock_team_user')->where('team_id', $team->team_id)->get();

                    $newTeamUsers = $teamUsers->map(function ($teamUser) use ($newTeamId) {
                        return [
                            'team_id' => $newTeamId,
                            'user_id' => $teamUser->user_id,
                            'dmc_position' => $teamUser->dmc_position,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    })->toArray();

                    DB::table('lock_team_user')->insert($newTeamUsers);
                }
            });

            return redirect()->route('ManageShiftTeam')
                ->with('success', 'คัดลอกข้อมูลกะและทีมสำเร็จแล้ว');
        } catch (\Exception $e) {
            return redirect()->route('ManageShiftTeam')
                ->with('error', 'มีข้อผิดพลาดในการคัดลอกข้อมูลกะและทีม');
        }
    }

    public function DeleteShiftTeam($shift_id)
    {
        try {
            DB::transaction(function () use ($shift_id) {
                // 1️⃣ ลบสมาชิกทีมที่เกี่ยวข้อง
                DB::table('lock_team_user')
                    ->whereIn('team_id', function ($query) use ($shift_id) {
                        $query->select('team_id')
                            ->from('lock_team')
                            ->where('shift_id', $shift_id);
                    })
                    ->delete();

                // 2️⃣ ลบทีมที่อยู่ในกะนี้
                DB::table('lock_team')
                    ->where('shift_id', $shift_id)
                    ->delete();

                // 3️⃣ ลบกะ (shift) นี้ออก
                DB::table('work_shift')
                    ->where('shift_id', $shift_id)
                    ->delete();
            });

            return redirect()->route('ManageShiftTeam')
                ->with('success', 'ลบข้อมูลกะและทีมสำเร็จแล้ว');
        } catch (\Exception $e) {
            return redirect()->route('ManageShiftTeam')
                ->with('error', 'มีข้อผิดพลาดในการลบข้อมูลกะและทีม');
        }
    }

    public function EditShiftTeam($Shift_id)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

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
                'work_shift.note as shift_note',
                'lock_team.team_id',
                'lock_team.team_name',
                'lock_team.work',
                'lock_team.note as team_note',
                'users.user_id',
                'users.name',
                'users.surname',
                'users.position',
                'users.note as user_note',
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
                    'note' => $firstShift->shift_note,
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
                                    'note' => $user->user_note
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

    public function SaveEditShift(Request $request, $Shift_id)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'start_shift' => 'required|date_format:H:i',
            'end_shift' => 'required|date_format:H:i|after:start_shift',
            'date' => 'required|date_format:d/m/Y',
            'note' => 'nullable|string',
        ]);

        $formattedDate = \DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        $duplicateShift = DB::table('work_shift')
            ->where('shift_name', $request->shift_name)
            ->where('date', $formattedDate)
            ->where('Shift_id', '!=', $Shift_id)
            ->exists();

        if ($duplicateShift) {
            return back()->withErrors(['shift_name' => 'ชื่อกะนี้มีอยู่ในวันที่ที่เลือกแล้ว กรุณาเลือกชื่อกะใหม่'])
                ->withInput();
        }

        $shiftTeam = DB::table('work_shift')->where('Shift_id', $Shift_id)->first();

        if (!$shiftTeam) {
            abort(404, 'ไม่พบข้อมูลกะที่ต้องการ');
        }

        try {
            DB::table('work_shift')->where('Shift_id', $Shift_id)->update([
                'shift_name' => $request->shift_name,
                'start_shift' => $request->start_shift,
                'end_shift' => $request->end_shift,
                'date' => $formattedDate,
                'note' => $request->note,
                'updated_at' => now(),
            ]);

            return redirect()->route('EditShiftTeam')->with('success', 'แก้ไขข้อมูลกะสำเร็จแล้ว');
        } catch (\Exception $e) {
            // Handle the exception
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาดในการแก้ไขข้อมูลกะ: ' . $e->getMessage()])
                ->withInput();
        }
    }


    public function SaveAddTeam(Request $request)
    {
        // ตรวจสอบความถูกต้องของข้อมูล
        $request->validate([
            'shift_id' => 'required|string|max:255',
            'team_name' => 'required|string|max:255',
            'work' => 'required|string|max:255',
            'note' => 'nullable|string',
            'user_id' => 'required|array',
            'user_id.*' => 'required|string|max:255',
            'dmc_position' => 'required|array',
            'dmc_position.*' => 'required|string|max:255',
        ]);

        $team_id = Str::uuid();

        try {
            DB::transaction(function () use ($request, $team_id) {
                // บันทึกข้อมูลในตาราง lock_team
                DB::table('lock_team')->insert([
                    'team_id' => $team_id,
                    'team_name' => $request->input('team_name'),
                    'work' => $request->input('work'),
                    'shift_id' => $request->input('shift_id'),
                    'note' => $request->input('note'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // เตรียมข้อมูลสำหรับตาราง lock_team_user
                $users = $request->input('user_id');
                $positions = $request->input('dmc_position');

                $teamUsers = [];
                foreach ($users as $index => $userId) {
                    $teamUsers[] = [
                        'team_id' => $team_id,
                        'user_id' => $userId,
                        'dmc_position' => $positions[$index] ?? null, // ใช้ค่า null หากไม่มีค่าใน index นั้น
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('lock_team_user')->insert($teamUsers);
            });

            return redirect()->route('EditShiftTeam', $request->input('shift_id'))
                ->with('success', 'ข้อมูลทีมได้ถูกเพิ่มเรียบร้อยแล้ว');
        } catch (\Exception $e) {

            return redirect()->route('EditShiftTeam', $request->input('shift_id'))
                ->with('error', 'มีข้อผิดพลาดในการเพิ่มข้อมูลทีม');
        }
    }

    public function SaveEditTeam(Request $request)
    {
        // 1️⃣ ตรวจสอบความถูกต้องของข้อมูล
        $request->validate([
            'team_id' => 'required|string|max:255',
            'shift_id' => 'required|string|max:255',
            'team_name' => 'required|string|max:255',
            'work' => 'required|string|max:255',
            'note' => 'nullable|string',
            'user_id' => 'required|array',
            'user_id.*' => 'required|string|max:255',
            'dmc_position' => 'required|array',
            'dmc_position.*' => 'required|string|max:255',
        ]);

        $team_id = $request->input('team_id');

        try {
            DB::transaction(function () use ($request, $team_id) {
                // 2️⃣ อัปเดตข้อมูลในตาราง lock_team
                DB::table('lock_team')
                    ->where('team_id', $team_id)
                    ->update([
                        'team_name' => $request->input('team_name'),
                        'work' => $request->input('work'),
                        'shift_id' => $request->input('shift_id'),
                        'note' => $request->input('note'),
                        'updated_at' => now(),
                    ]);

                // 3️⃣ ดึงข้อมูลเก่าจากตาราง lock_team_user
                $existingUsers = DB::table('lock_team_user')
                    ->where('team_id', $team_id)
                    ->get()
                    ->keyBy('user_id'); // ใช้ user_id เป็น key

                // 4️⃣ เตรียมข้อมูลใหม่จากฟอร์ม
                $users = $request->input('user_id');
                $positions = $request->input('dmc_position');

                $updatedUsers = []; // user_id ที่ถูกเพิ่ม/อัปเดต
                $newUsers = []; // รายการ user_id ที่ต้องเพิ่มเข้าใหม่

                foreach ($users as $index => $userId) {
                    $userData = [
                        'team_id' => $team_id,
                        'user_id' => $userId,
                        'dmc_position' => $positions[$index] ?? null,
                        'updated_at' => now(),
                    ];

                    if ($existingUsers->has($userId)) {
                        // ⚙️ **อัปเดตข้อมูลของ user ที่มีอยู่แล้ว**
                        DB::table('lock_team_user')
                            ->where('team_id', $team_id)
                            ->where('user_id', $userId)
                            ->update($userData);
                    } else {
                        // ⚙️ **เพิ่มข้อมูลใหม่ (ยังไม่มีในตาราง lock_team_user)**
                        $userData['created_at'] = now();
                        $newUsers[] = $userData;
                    }

                    // เพิ่ม user_id นี้ในรายการที่ได้รับการอัปเดต
                    $updatedUsers[] = $userId;
                }

                // 5️⃣ เพิ่มข้อมูลใหม่ในตาราง lock_team_user
                if (!empty($newUsers)) {
                    DB::table('lock_team_user')->insert($newUsers);
                }

                // 6️⃣ ลบข้อมูลเก่าที่ไม่มีในฟอร์ม
                $usersToDelete = $existingUsers->keys()->diff($updatedUsers); // ค้นหาผู้ใช้ที่มีในฐานข้อมูลแต่ไม่มีในฟอร์ม
                if ($usersToDelete->isNotEmpty()) {
                    DB::table('lock_team_user')
                        ->where('team_id', $team_id)
                        ->whereIn('user_id', $usersToDelete)
                        ->delete();
                }
            });

            return redirect()->route('EditShiftTeam', $request->input('shift_id'))
                ->with('success', 'ข้อมูลทีมได้ถูกอัปเดตเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->route('EditShiftTeam', $request->input('shift_id'))
                ->with('error', 'มีข้อผิดพลาดในการอัปเดตข้อมูลทีม');
        }
    }

    public function deleteTeam($shiftId, $teamId)
    {
        try {
            DB::transaction(function () use ($shiftId, $teamId) {
                DB::table('lock_team')
                    ->where('shift_id', $shiftId)
                    ->where('team_id', $teamId)
                    ->delete();

                DB::table('lock_team_user')
                    ->where('team_id', $teamId)
                    ->delete();
            });

            return redirect()->route('EditShiftTeam', $shiftId)
                ->with('success', 'ข้อมูลทีมได้ถูกลบเรียบร้อยแล้ว');
        } catch (\Exception $e) {

            return redirect()->route('EditShiftTeam', $shiftId)
                ->with('error', 'มีข้อผิดพลาดในการลบข้อมูลทีม');
        }
    }

    public function AutoCompleteTeam(Request $request)
    {
        $query = $request->get('query');
        // ดึงข้อมูลเฉพาะฟิลด์ที่ต้องการ เช่น product_name และ product_id
        $data = DB::table('users')
            ->select('user_id', 'name', 'surname', 'position') // เลือกเฉพาะฟิลด์ product_name และ product_id
            ->where('name', 'like', '%' . $query . '%')
            ->limit(10) // จำกัดผลลัพธ์ 10 รายการ
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ jQuery autocomplete ต้องการ
        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->name,  // ใช้ 'label' สำหรับการแสดงผลในรายการ autocomplete
                'value' => $item->name,  // ใช้ 'value' สำหรับการเติมในช่อง input
                'user_id' => $item->user_id,     // ส่ง 'id' สำหรับการใช้รหัสสินค้าเพิ่มเติม
                'surname' => $item->surname,      // ส่ง 'id' สำหรับการใช้รหัสสินค้าเพิ่มเติม
                'position' => $item->position       // ส่ง 'id' สำหรับการใช้รหัสสินค้าเพิ่มเติม
            ];
        }

        return response()->json($results);
    }

    public function AutocompleteSearchTeam(Request $request)
    {
        $query = $request->get('query');

        $data = DB::table('lock_team')
            ->where('team_name', 'like', '%' . $query . '%')
            ->where('work', 'like', '%' . 'จัดสินค้า' . '%')
            ->whereDate('date', now()->format('Y-m-d'))
            ->limit(10) // จำกัดผลลัพธ์ 10 รายการ
            ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->team_name,  // ใช้ 'label' สำหรับการแสดงผลในรายการ autocomplete
                'value' => $item->team_name,  // ใช้ 'value' สำหรับการเติมในช่อง input
                'team_id' => $item->team_id,     // ส่ง 'id' สำหรับการใช้รหัสสินค้าเพิ่มเติม
            ];
        }

        return response()->json($results);
    }
}
