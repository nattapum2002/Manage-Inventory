<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShiftAndTeamController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    private function GetShiftsByMonth($month)
    {
        $year = substr($month, 0, 4);
        $monthOnly = substr($month, 5, 2);

        return DB::table('shift')
            ->join('shift_time', 'shift.shift_time_id', '=', 'shift_time.shift_time_id')
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

        $shifts = DB::table('shift')
            ->whereDate('date', $date)
            ->select('shift_time_id')
            ->distinct()
            ->pluck('shift_time_id')
            ->toArray();

        $shift_time = DB::table('shift_time')
            ->whereNotIn('shift_time_id', $shifts)
            ->get();

        return response()->json($shift_time);
    }

    public function index(Request $request)
    {
        $ShiftFilterMonth = $this->GetShiftsByMonth($request->input('date') ?? now()->format('Y-m'));

        return view('Admin.ManageShiftTeam.ManageShiftTeam', compact('ShiftFilterMonth'));
    }

    public function ShiftToggle($shift_id, $status)
    {
        DB::table('shift')->where('shift_id', $shift_id)->update([
            'status' => $status,
        ]);

        return redirect()->back()->with('success', 'เปลี่ยนสถานะกะเรียบร้อยแล้ว');
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
            'shift' => 'required',
            'day' => 'required|date_format:d',
            'month' => 'required|date_format:Y-m',
            'note' => 'nullable|string',
        ], [
            'shift.required' => 'กรุณาเลือกกะ',
            'day.required' => 'กรุณาเลือกวันที่',
        ]);

        $shiftFilter = DB::table('shift_time')
            ->where('shift_time_id', $request->input('shift'))
            ->first();

        $monthYear = $request->input('month');
        $day = $request->input('day');

        [$year, $month] = explode('-', $monthYear);

        $date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');

        $data = array_merge($request->all(), ['date' => $date]);

        // 2️⃣ ตรวจสอบว่ามีกะที่ชื่อเหมือนกันแล้วหรือไม่
        $existingShift = DB::table('shift')
            ->where('shift_time_id', $shiftFilter->shift_time_id)
            ->orderBy('date', 'desc')
            ->first();

        if ($existingShift) {
            // ดึงข้อมูลที่เกี่ยวข้องกับกะนี้
            $ShiftTeams = DB::table('shift')
                ->join('shift_time', 'shift.shift_time_id', '=', 'shift_time.shift_time_id')
                ->join('team', 'shift.shift_id', '=', 'team.shift_id')
                ->join('team_user', 'team.team_id', '=', 'team_user.team_id')
                ->join('users', 'team_user.user_id', '=', 'users.user_id')
                ->where('shift.shift_id', $existingShift->shift_id)
                ->select(
                    'shift.shift_id',
                    'shift_time.shift_name',
                    'shift_time.start_shift',
                    'shift_time.end_shift',
                    'shift.date',
                    'shift.note as shift_note',
                    'team.team_id',
                    'team.team_name',
                    'team.note as team_note',
                    'users.user_id',
                    'users.name',
                    'users.surname',
                    'users.department',
                    'users.note as user_note',
                    'team_user.dmc_position',
                    'team_user.work_description'
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
                                'note' => $firstTeam->team_note,
                                'users' => $teamGroup->map(function ($user) {
                                    return [
                                        'user_id' => $user->user_id,
                                        'name' => $user->name,
                                        'surname' => $user->surname,
                                        'department' => $user->department,
                                        'dmc_position' => $user->dmc_position,
                                        'work_description' => $user->work_description,
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
            DB::table('shift')->insert([
                'shift_id' => Str::uuid(),
                'shift_time_id' => $shiftFilter->shift_time_id,
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
            'shift' => 'required|string|max:255',
            'date' => 'required',
            'note' => 'nullable|string',
        ]);

        $shiftFilter = DB::table('shift_time')
            ->where('shift_time_id', $request->input('shift'))
            ->first();

        try {
            DB::table('shift')->insert([
                'shift_id' => Str::uuid(),
                'shift_time_id' => $shiftFilter->shift_time_id,
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
                $originalShift = DB::table('shift')->where('shift_id', $request->input('shift_id'))->first();

                // 3️⃣ สร้างข้อมูลกะใหม่
                $newShiftId = Str::uuid();
                DB::table('shift')->insert([
                    'shift_id' => $newShiftId,
                    'shift_time_id' => $originalShift->shift_time_id,
                    'date' => $request->input('date'), // ตั้งวันที่ใหม่
                    'note' => $originalShift->note,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 4️⃣ คัดลอกทีมทั้งหมดในกะต้นฉบับ
                $originalTeams = DB::table('team')->where('shift_id', $originalShift->shift_id)->get();

                foreach ($originalTeams as $team) {
                    $newTeamId = Str::uuid();
                    DB::table('team')->insert([
                        'team_id' => $newTeamId,
                        'team_name' => $team->team_name,
                        'shift_id' => $newShiftId,
                        'note' => $team->note,
                    ]);

                    // 5️⃣ คัดลอกสมาชิกในทีม
                    $teamUsers = DB::table('team_user')->where('team_id', $team->team_id)->get();

                    $newTeamUsers = $teamUsers->map(function ($teamUser) use ($newTeamId) {
                        return [
                            'team_id' => $newTeamId,
                            'user_id' => $teamUser->user_id,
                            'dmc_position' => $teamUser->dmc_position,
                            'work_description' => $teamUser->work_description,
                        ];
                    })->toArray();

                    DB::table('team_user')->insert($newTeamUsers);
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
                DB::table('team_user')
                    ->whereIn('team_id', function ($query) use ($shift_id) {
                        $query->select('team_id')
                            ->from('team')
                            ->where('shift_id', $shift_id);
                    })
                    ->delete();

                // 2️⃣ ลบทีมที่อยู่ในกะนี้
                DB::table('team')
                    ->where('shift_id', $shift_id)
                    ->delete();

                // 3️⃣ ลบกะ (shift) นี้ออก
                DB::table('shift')
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
        $ShiftTeams = DB::table('shift')
            ->join('shift_time', 'shift.shift_time_id', '=', 'shift_time.shift_time_id')
            ->leftJoin('team', 'shift.shift_id', '=', 'team.shift_id')
            ->leftJoin('team_user', 'team.team_id', '=', 'team_user.team_id')
            ->leftJoin('users', 'team_user.user_id', '=', 'users.user_id')
            ->where('shift.shift_id', $Shift_id)
            ->select(
                'shift.shift_id',
                'shift_time.shift_name',
                'shift_time.start_shift',
                'shift_time.end_shift',
                'shift.date',
                'shift.note as shift_note',
                'team.team_id',
                'team.team_name',
                'team.note as team_note',
                'users.user_id',
                'users.name',
                'users.surname',
                'users.department',
                'users.note as user_note',
                'team_user.dmc_position',
                'team_user.work_description'
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
                            'note' => $firstTeam->team_note,
                            'users' => $teamGroup->map(function ($user) {
                                return [
                                    'user_id' => $user->user_id,
                                    'name' => $user->name,
                                    'surname' => $user->surname,
                                    'department' => $user->department,
                                    'dmc_position' => $user->dmc_position,
                                    'work_description' => $user->work_description,
                                    'note' => $user->user_note
                                ];
                            })->filter() //กรองข้อมูลเฉพาะ users ที่มีอยู่
                        ];
                    })->filter() //กรองข้อมูลทีมที่ไม่ null
                ];
            })->first();

        return view('Admin.ManageShiftTeam.EditShiftTeam', [
            'ShiftTeams' => $ShiftTeams,
        ]);
    }

    public function SaveEditShift(Request $request, $Shift_id)
    {

        // dd($request->all());
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'date' => 'required|date_format:d/m/Y',
            'note' => 'nullable|string',
        ]);

        $shiftFilter = DB::table('shift_time')
            ->where('shift_name', $request->input('shift_name'))
            ->first();

        $formattedDate = \DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        $duplicateShift = DB::table('shift')
            ->where('shift_time_id', $shiftFilter->shift_time_id)
            ->where('date', $formattedDate)
            ->where('Shift_id', '!=', $Shift_id)
            ->exists();

        if ($duplicateShift) {
            return back()->withErrors(['shift_name' => 'ชื่อกะนี้มีอยู่ในวันที่ที่เลือกแล้ว กรุณาเลือกชื่อกะใหม่'])
                ->withInput();
        }

        $shiftTeam = DB::table('shift')->where('Shift_id', $Shift_id)->first();

        if (!$shiftTeam) {
            abort(404, 'ไม่พบข้อมูลกะที่ต้องการ');
        }

        try {
            DB::table('shift')->where('Shift_id', $Shift_id)->update([
                'shift_time_id' => $shiftFilter->shift_time_id,
                'date' => $formattedDate,
                'note' => $request->note,
                'updated_at' => now(),
            ]);

            return redirect()->route('EditShiftTeam')->with('success', 'แก้ไขข้อมูลกะสำเร็จแล้ว');
        } catch (\Exception $e) {
            // Handle the exception
            return back()->withErrors(['error' => 'เกิดข้อผิดพลาด : ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function SaveAddTeam(Request $request, $Shift_id)
    {

        // ตรวจสอบความถูกต้องของข้อมูล
        $request->validate([
            'shift_id' => 'required|string|max:255',
            'team_name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'user_id' => 'required|array',
            'user_id.*' => 'required|string|max:255',
            'dmc_position' => 'required|array',
            'dmc_position.*' => 'required|string|max:255',
        ]);

        //dd($request->all());

        $team_id = Str::uuid();

        try {
            DB::transaction(function () use ($request, $team_id) {
                // บันทึกข้อมูลในตาราง team
                DB::table('team')->insert([
                    'team_id' => $team_id,
                    'shift_id' => $request->input('shift_id'),
                    'team_name' => $request->input('team_name'),
                    'note' => $request->input('note'),
                ]);

                // เตรียมข้อมูลสำหรับตาราง team_user
                $users = $request->input('user_id');
                $positions = $request->input('dmc_position');

                $teamUsers = [];
                foreach ($users as $index => $userId) {
                    $teamUsers[] = [
                        'team_id' => $team_id,
                        'user_id' => $userId,
                        'dmc_position' => $positions[$index] ?? null, // ใช้ค่า null หากไม่มีค่าใน index นั้น
                    ];
                }

                DB::table('team_user')->insert($teamUsers);
            });

            return redirect()->route('EditShiftTeam', $request->input('shift_id'))
                ->with('success', 'ข้อมูลทีมได้ถูกเพิ่มเรียบร้อยแล้ว');
        } catch (\Exception $e) {

            return redirect()->route('EditShiftTeam', $request->input('shift_id'))
                ->with('error', 'มีข้อผิดพลาดในการเพิ่มข้อมูลทีม');
        }
    }

    public function AddTeamForm($Shift_id)
    {
        $teamsData = [];
        $teamUsersData = [];

        // ข้อมูลทีมและสมาชิกที่ต้องการเพิ่ม
        $teams = [
            [
                'team_name' => 'ท้ายสายพาน',
                'team_user' => [
                    ['user_id' => '000001', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000002', 'dmc_position' => 'CH', 'work_description' => 'รับ/เก็บ/จัดห้อง'],
                    ['user_id' => '000003', 'dmc_position' => 'CH', 'work_description' => 'เช็ครับ/ทำรายงาน'],
                    ['user_id' => '000004', 'dmc_position' => 'พนักงาน', 'work_description' => 'รับท้ายสายพาน'],
                    ['user_id' => '000005', 'dmc_position' => 'พนักงาน', 'work_description' => 'ลากเก็บเข้าห้อง'],
                ],
            ],
            [
                'team_name' => 'cold-A',
                'team_user' => [
                    ['user_id' => '000006', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000007', 'dmc_position' => 'CH', 'work_description' => 'รับ/เก็บ'],
                    ['user_id' => '000008', 'dmc_position' => 'CH', 'work_description' => 'จัดสินค้า C-A'],
                ],
            ],
            [
                'team_name' => 'รับ-จัด cold-C',
                'team_user' => [
                    ['user_id' => '000009', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000010', 'dmc_position' => 'CH', 'work_description' => 'ติดตามสินค้า'],
                ],
            ],
            [
                'team_name' => 'หน้าลาน',
                'team_user' => [
                    ['user_id' => '000011', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000012', 'dmc_position' => 'CH', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => 'เช็คนับของเหลือเข้า',
                'team_user' => [
                    ['user_id' => '000013', 'dmc_position' => 'CH', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => 'งาน Stock',
                'team_user' => [
                    ['user_id' => '000014', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => 'ตัดบิลออกINV.',
                'team_user' => [
                    ['user_id' => '000015', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000016', 'dmc_position' => '', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => 'เลือด',
                'team_user' => [
                    ['user_id' => '000017', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000018', 'dmc_position' => 'CH', 'work_description' => ''],
                    ['user_id' => '000019', 'dmc_position' => 'พนักงาน', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => 'จัดจ่ายซันเรน',
                'team_user' => [
                    ['user_id' => '000020', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => 'น้ำแข็ง',
                'team_user' => [
                    ['user_id' => '000021', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000022', 'dmc_position' => 'พนักงาน', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => '5ส.ทำความสะอาด',
                'team_user' => [
                    ['user_id' => '000023', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000024', 'dmc_position' => 'พนักงาน', 'work_description' => 'ห้องANTI - หน้าห้องน้ำแข็ง - ลานจ่ายสมหวังด้านบน'],
                    ['user_id' => '000025', 'dmc_position' => 'พนักงาน', 'work_description' => 'ห้อง CC - หน้าออฟฟิศ - ห้องรับสินค้า CA'],
                    ['user_id' => '000026', 'dmc_position' => 'พนักงาน', 'work_description' => 'ล้างคอก,พาเลท - ด้านลานจ่ายช้างล่างช่อง1-8'],
                    ['user_id' => '000027', 'dmc_position' => 'พนักงาน', 'work_description' => 'ห้องCA - ห้องANTI - หน้าห้องน้ำแข็ง'],
                    ['user_id' => '000028', 'dmc_position' => 'พนักงาน', 'work_description' => 'ลานจ่ายเลือด - ลวนจ่ายสมหวังข้างล่าง - ท้ายสายพาน'],
                ],
            ],
            [
                'team_name' => 'นับคอก/พาเลท/ตระกร้า',
                'team_user' => [
                    ['user_id' => '000029', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000030', 'dmc_position' => 'พนักงาน', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => 'ลากจ่ายหน้าลาน',
                'team_user' => [
                    ['user_id' => '000031', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000032', 'dmc_position' => 'พนักงาน', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => 'ออกแผนล็อคสินค้า',
                'team_user' => [
                    ['user_id' => '000033', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000034', 'dmc_position' => 'CH', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => 'ตรวจวัดอุณหภูมิ',
                'team_user' => [
                    ['user_id' => '000035', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000036', 'dmc_position' => 'CH', 'work_description' => ''],
                    ['user_id' => '000037', 'dmc_position' => 'พนักงาน', 'work_description' => 'เสริมจุดงาน'],
                ],
            ],
            [
                'team_name' => 'จุดทำความสะอาดออฟฟิศ',
                'team_user' => [
                    ['user_id' => '000038', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000039', 'dmc_position' => 'พนักงาน', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => 'จุดทำความสะอาดห้องน้ำข้างล่าง',
                'team_user' => [
                    ['user_id' => '000040', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000041', 'dmc_position' => 'พนักงาน', 'work_description' => ''],
                ],
            ],
            [
                'team_name' => 'จุดงานซ่อมhand lift-pmสายพาน',
                'team_user' => [
                    ['user_id' => '000042', 'dmc_position' => 'โฟร์แมน', 'work_description' => ''],
                    ['user_id' => '000043', 'dmc_position' => 'พนักงาน', 'work_description' => ''],
                ],
            ],
        ];

        foreach ($teams as $teamData) {
            $team_id = Str::uuid(); // สร้าง UUID สำหรับทีม

            // เก็บข้อมูลทีมในอาร์เรย์
            $teamsData[] = [
                'team_id' => $team_id,
                'shift_id' => $Shift_id,
                'team_name' => $teamData['team_name'],
                'note' => '',
            ];

            // เก็บข้อมูลสมาชิกทีมในอาร์เรย์
            foreach ($teamData['team_user'] as $user) {
                $teamUsersData[] = [
                    'team_id' => $team_id,
                    'user_id' => $user['user_id'],
                    'dmc_position' => $user['dmc_position'],
                    'work_description' => $user['work_description'],
                ];
            }
        }

        // Insert ข้อมูลทั้งหมดทีเดียว
        DB::table('team')->insert($teamsData);
        DB::table('team_user')->insert($teamUsersData);

        return redirect()->route('EditShiftTeam', $Shift_id)
            ->with('success', 'ข้อมูลทีมได้ถูกเพิ่มเรียบร้อยแล้ว');
    }

    public function SaveEditTeam(Request $request, $Shift_id)
    {
        // 1️⃣ ตรวจสอบความถูกต้องของข้อมูล
        $request->validate([
            'team_id' => 'required|string',
            'shift_id' => 'required|string',
            'team_name' => 'required|string',
            'note' => 'nullable|string',
            'edit_user_id' => 'required|array',
            'edit_user_id.*' => 'required|string',
            'edit_name' => 'required|array',
            'edit_name.*' => 'required|string',
            'edit_dmc_position' => 'required|array',
            'edit_dmc_position.*' => 'nullable|string',
            'edit_work_description' => 'required|array',
            'edit_work_description.*' => 'nullable|string',
        ]);

        $team_id = $request->input('team_id');

        try {
            DB::transaction(function () use ($request, $team_id) {
                // 2️⃣ อัปเดตข้อมูลในตาราง team
                DB::table('shift')
                    ->where('shift_id', $request->input('shift_id'))
                    ->update([
                        'updated_at' => now(),
                    ]);

                DB::table('team')
                    ->where('team_id', $team_id)
                    ->update([
                        'team_name' => $request->input('team_name'),
                        'shift_id' => $request->input('shift_id'),
                        'note' => $request->input('note'),
                    ]);

                // 3️⃣ ดึงข้อมูลเก่าจากตาราง team_user
                $existingUsers = DB::table('team_user')
                    ->where('team_id', $team_id)
                    ->get()
                    ->keyBy('user_id'); // ใช้ user_id เป็น key

                // 4️⃣ เตรียมข้อมูลใหม่จากฟอร์ม
                $users = $request->input('edit_user_id');
                $positions = $request->input('edit_dmc_position');

                $updatedUsers = []; // user_id ที่ถูกเพิ่ม/อัปเดต
                $newUsers = []; // รายการ user_id ที่ต้องเพิ่มเข้าใหม่

                foreach ($users as $index => $userId) {
                    $userData = [
                        'team_id' => $team_id,
                        'user_id' => $userId,
                        'dmc_position' => $positions[$index] ?? null,
                    ];

                    if ($existingUsers->has($userId)) {
                        // ⚙️ **อัปเดตข้อมูลของ user ที่มีอยู่แล้ว**
                        DB::table('team_user')
                            ->where('team_id', $team_id)
                            ->where('user_id', $userId)
                            ->update($userData);
                    } else {
                        // ⚙️ **เพิ่มข้อมูลใหม่ (ยังไม่มีในตาราง team_user)**
                        $newUsers[] = $userData;
                    }

                    // เพิ่ม user_id นี้ในรายการที่ได้รับการอัปเดต
                    $updatedUsers[] = $userId;
                }

                // 5️⃣ เพิ่มข้อมูลใหม่ในตาราง team_user
                if (!empty($newUsers)) {
                    DB::table('team_user')->insert($newUsers);
                }

                // 6️⃣ ลบข้อมูลเก่าที่ไม่มีในฟอร์ม
                $usersToDelete = $existingUsers->keys()->diff($updatedUsers); // ค้นหาผู้ใช้ที่มีในฐานข้อมูลแต่ไม่มีในฟอร์ม
                if ($usersToDelete->isNotEmpty()) {
                    DB::table('team_user')
                        ->where('team_id', $team_id)
                        ->whereIn('user_id', $usersToDelete)
                        ->delete();
                }
            });

            return redirect()->route('EditShiftTeam', $request->input('shift_id'))
                ->with('success', 'ข้อมูลทีมได้ถูกอัปเดตเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->route('EditShiftTeam', $request->input('shift_id'))
                ->with('error', 'มีข้อผิดพลาดในการอัปเดตข้อมูลทีม' . $e->getMessage());
        }
    }

    public function deleteTeam($shiftId, $teamId)
    {
        try {
            DB::transaction(function () use ($shiftId, $teamId) {
                DB::table('shift')
                    ->where('shift_id', $shiftId)
                    ->update([
                        'updated_at' => now(),
                    ]);

                DB::table('team')
                    ->where('shift_id', $shiftId)
                    ->where('team_id', $teamId)
                    ->delete();

                DB::table('team_user')
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
            ->select('user_id', 'name', 'surname', 'department') // เลือกเฉพาะฟิลด์ product_name และ product_id
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
                'department' => $item->department       // ส่ง 'id' สำหรับการใช้รหัสสินค้าเพิ่มเติม
            ];
        }

        return response()->json($results);
    }

    public function AutocompleteDMCPosition(Request $request)
    {
        $query = $request->get('query');

        $data = DB::table('team_user')
            ->select('dmc_position')
            ->where('dmc_position', 'like', '%' . $query . '%')
            ->distinct()
            ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->dmc_position,
                'value' => $item->dmc_position,
            ];
        }

        return response()->json($results);
    }

    public function AutocompleteWork(Request $request)
    {
        $query = $request->get('query');

        $data = DB::table('team_user')
            ->select('work_description')
            ->where('work_description', 'like', '%' . $query . '%')
            ->distinct()
            ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'label' => $item->work_description,
                'value' => $item->work_description,
            ];
        }

        return response()->json($results);
    }
}