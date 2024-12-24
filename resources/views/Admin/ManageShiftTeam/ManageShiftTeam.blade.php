@extends('layouts.master')

@section('title')
    จัดการกะและทีมพนักงาน
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5>กะ</h5>
                                <div>
                                    <div class="input-group">
                                        <input type="month" class="form-control" name="month" id="month"
                                            value="{{ (new DateTime($ShiftFilterMonth->first()->date))->format('Y-m') ?? now()->format('Y-m') }}">
                                        <button type="button" class="btn btn-primary" id="btn-search-shift">ค้นหา</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('AddShift') }}" method="POST">
                                @csrf
                                <input type="hidden" name="month" id="hidden-month">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="day" class="form-label">วันที่</label>
                                            <select class="form-control" id="day-select" name="day">
                                                <!-- Options will be dynamically added here -->
                                            </select>
                                            @error('day')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_name">ชื่อกะพนักงาน</label>
                                            <select class="form-control" id="shift_name" name="shift_name">
                                                <option selected value="">เลือกชื่อกะพนักงาน</option>
                                            </select>
                                            @error('shift_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="start_shift">เวลาเริ่มกะ</label>
                                            <input type="time" class="form-control" id="start_shift" name="start_shift"
                                                placeholder="เวลาเริ่มกะ"
                                                value="{{ now()->format('H:i') > '12:00' ? '19:00' : '07:00' }}">
                                            @error('start_shift')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="end_shift">เวลาเลิกกะ</label>
                                            <input type="time" class="form-control" id="end_shift" name="end_shift"
                                                placeholder="เวลาเลิกกะ"
                                                value="{{ now()->format('H:i') > '12:00' ? '07:00' : '19:00' }}">
                                            @error('end_shift')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <textarea type="text" class="form-control" id="note" name="note" placeholder="หมายเหตุ"></textarea>
                                        </div>
                                        @error('note')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success">เพิ่มกะ</button>
                                </div>
                            </form>
                            <hr>
                            <table id="ShiftTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>วันที่</th>
                                        <th>ชื่อกะ</th>
                                        <th>เวลาเริ่มกะ</th>
                                        <th>เวลาเลิกกะ</th>
                                        <th>หมายเหตุ</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody id="shift-table-body">
                                    @foreach ($ShiftFilterMonth as $shift)
                                        <tr>
                                            <td>{{ (new DateTime($shift->date))->format('d/m/Y') }}</td>
                                            <td>{{ $shift->shift_name }}</td>
                                            <td>{{ (new DateTime($shift->start_shift))->format('H:i') }}</td>
                                            <td>{{ (new DateTime($shift->end_shift))->format('H:i') }}</td>
                                            <td>{{ $shift->note }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('EditShiftTeam', $shift->shift_id) }}"
                                                        class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('DeleteShiftTeam', $shift->shift_id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('คุณแน่ใจว่าต้องการลบกะนี้หรือไม่?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('duplicate_shift'))
                <div class="modal fade" id="duplicateShiftModal" tabindex="-1" role="dialog"
                    style="display: block; background-color: rgba(0,0,0,0.5);">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">คุณต้องการนำเข้ากะที่คุณเคยกรอกไหม</h5>
                            </div>
                            <div class="modal-body">
                                <h5>รายละเอียดของกะที่ซ้ำกัน</h5>
                                <div class="mb-3">
                                    <strong>ชื่อกะ:</strong> {{ session('ShiftTeams')['shift_name'] ?? 'N/A' }} <br>
                                    <strong>เวลาเริ่ม:</strong>
                                    {{ !empty(session('ShiftTeams')['start_shift'])
                                        ? (new DateTime(session('ShiftTeams')['start_shift']))->format('H:i')
                                        : 'N/A' }}
                                    <br>
                                    <strong>เวลาเลิก:</strong>
                                    {{ !empty(session('ShiftTeams')['end_shift'])
                                        ? (new DateTime(session('ShiftTeams')['end_shift']))->format('H:i')
                                        : 'N/A' }}
                                    <br>
                                    <strong>วันที่:</strong>
                                    {{ !empty(session('ShiftTeams')['date'])
                                        ? (new DateTime(session('ShiftTeams')['date']))->format('d/m/Y')
                                        : 'N/A' }}
                                    <br>
                                    <strong>หมายเหตุ:</strong> {{ session('ShiftTeams')['note'] ?? 'ไม่มีหมายเหตุ' }}
                                </div>

                                @if (!empty(session('ShiftTeams')['teams']))
                                    <h5>ทีมที่เกี่ยวข้องในกะนี้</h5>
                                    @foreach (session('ShiftTeams')['teams'] as $team)
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <strong>ชื่อทีม:</strong> {{ $team['team_name'] ?? 'N/A' }}
                                            </div>
                                            <div class="card-body">
                                                <p><strong>ลักษณะงาน:</strong> {{ $team['work'] ?? 'N/A' }}</p>
                                                <p><strong>หมายเหตุทีม:</strong> {{ $team['note'] ?? 'ไม่มีหมายเหตุ' }}</p>

                                                @if (!empty($team['users']))
                                                    <h6>สมาชิกในทีม</h6>
                                                    <table class="table table-bordered table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>รหัสพนักงาน</th>
                                                                <th>ชื่อพนักงาน</th>
                                                                <th>ตำแหน่งใน DMC</th>
                                                                <th>หมายเหตุ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($team['users'] as $user)
                                                                <tr>
                                                                    <td>{{ $user['user_id'] ?? 'N/A' }}</td>
                                                                    <td>{{ $user['name'] ?? 'N/A' }}</td>
                                                                    <td>{{ $user['dmc_position'] ?? 'N/A' }}</td>
                                                                    <td>{{ $user['note'] ?? 'ไม่มีหมายเหตุ' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <p>ไม่มีสมาชิกในทีม</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p>ไม่มีทีมในกะนี้</p>
                                @endif
                            </div>


                            <div class="modal-footer">
                                <form action="{{ route('CopyShiftAndTeam') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="shift_id"
                                        value="{{ session('duplicate_shift')->shift_id }}">
                                    <input type="hidden" name="date" value="{{ session('Data')['date'] }}">
                                    <button type="submit" class="btn btn-primary">คัดลอกข้อมูล</button>
                                </form>
                                <form action="{{ route('SaveAddShift') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="shift_id"
                                        value="{{ session('duplicate_shift')->shift_id }}">
                                    <input type="hidden" name="shift_name" value="{{ session('Data')['shift_name'] }}">
                                    <input type="hidden" name="start_shift"
                                        value="{{ session('Data')['start_shift'] }}">
                                    <input type="hidden" name="end_shift" value="{{ session('Data')['end_shift'] }}">
                                    <input type="hidden" name="date" value="{{ session('Data')['date'] }}">
                                    <input type="hidden" name="note" value="{{ session('Data')['note'] }}">
                                    <button type="submit" class="btn btn-secondary">กรอกข้อมูลใหม่</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>
@endsection

@section('script')
    <script>
        $("#ShiftTable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            // scrollX: true,
            // layout: {
            //     topStart: {
            //         buttons: [
            //             'copy', 'excel', 'pdf'
            //         ]
            //     }
            // }
        });
    </script>

    <script>
        window.onload = function() {
            $('#duplicateShiftModal').modal('show');
        }
    </script>

    <script>
        document.getElementById('btn-search-shift').addEventListener('click', function() {
            const month = document.getElementById('month').value;
            fetch(`{{ route('ShiftFilterMonth') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        month: month
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data || !data.ShiftFilterMonth) {
                        console.error('Data structure is incorrect or ShiftFilterMonth is missing');
                        return;
                    }

                    const shiftTableBody = document.getElementById('shift-table-body');
                    shiftTableBody.innerHTML = ''; // ล้างข้อมูลเก่าทั้งหมด
                    data.ShiftFilterMonth.forEach(shift => {
                        const row = `
                        <tr>
                            <td>${formatDate(shift.date)}</td>
                            <td>${shift.shift_name}</td>
                            <td>${formatTime(shift.start_shift)}</td>
                            <td>${formatTime(shift.end_shift)}</td>
                            <td>${shift.note}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="ManageShiftAndTeam/EditShiftTeam/${shift.shift_id}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                    <form action="ManageShiftAndTeam/DeleteShiftTeam/${shift.shift_id}" method="POST" onsubmit="return confirm('คุณแน่ใจว่าต้องการลบกะนี้หรือไม่?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    `;
                        shiftTableBody.insertAdjacentHTML('beforeend', row);
                    });
                })
                .catch(error => console.error('Error:', error));

            function formatDate(date) {
                const d = new Date(date);
                return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`;
            }

            function formatTime(time) {
                const d = new Date('1970-01-01T' + time);
                return d.toTimeString().slice(0, 5);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthInput = document.getElementById('month');
            const hiddenMonthInput = document.getElementById('hidden-month');
            const daySelect = document.getElementById('day-select');

            // ฟังก์ชันสำหรับสร้างรายการวันที่
            const populateDays = (year, month) => {
                daySelect.innerHTML = ''; // ลบ option เดิม

                // เพิ่ม option ตัวแรก "เลือกวันที่"
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'เลือกวันที่';
                defaultOption.selected = true;
                defaultOption.disabled = true;
                daySelect.appendChild(defaultOption);

                const daysInMonth = new Date(year, month, 0).getDate();
                for (let day = 1; day <= daysInMonth; day++) {
                    const option = document.createElement('option');
                    option.value = day;
                    option.textContent = day;
                    daySelect.appendChild(option);
                }
            };

            // อัปเดตรายการวันที่เมื่อเปลี่ยนเดือน
            monthInput.addEventListener('change', () => {
                hiddenMonthInput.value = monthInput.value;
                const [year, month] = monthInput.value.split('-'); // แยกปีและเดือน
                populateDays(year, parseInt(month));
            });

            // ตั้งค่าเริ่มต้นเมื่อโหลดหน้า
            const now = new Date();
            const currentYear = now.getFullYear();
            const currentMonth = String(now.getMonth() + 1).padStart(2, '0'); // เดือนเริ่มจาก 0

            // ตั้งค่าค่าเริ่มต้นใน input เดือน
            monthInput.value = `${currentYear}-${currentMonth}`;
            hiddenMonthInput.value = monthInput.value;

            // สร้างรายการวันที่พร้อมตัวเลือก "เลือกวันที่"
            populateDays(currentYear, parseInt(currentMonth));
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthInput = document.getElementById('month'); // ตัวเลือกเดือน
            const daySelect = document.getElementById('day-select'); // ตัวเลือกวันที่
            const shiftSelect = document.getElementById('shift_name'); // ตัวเลือกกะพนักงาน

            // ฟังก์ชันสำหรับส่งข้อมูลวันที่ไปที่เซิร์ฟเวอร์
            const fetchShifts = (date) => {
                fetch(`{{ route('ShiftFilter') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            date: date
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        shiftSelect.innerHTML = '<option selected value="">เลือกชื่อกะพนักงาน</option>';
                        data.forEach(shift => {
                            const option = document.createElement('option');
                            option.value = shift.select_name;
                            option.textContent = shift.select_name;
                            shiftSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching shifts:', error));
            };

            const updateDate = () => {
                const month = monthInput.value;
                const day = daySelect.value;

                if (month && day) {
                    const date = `${month}-${day}`;
                    fetchShifts(date);
                }
            };

            monthInput.addEventListener('change', updateDate);
            daySelect.addEventListener('change', updateDate);
        });
    </script>
@endsection
