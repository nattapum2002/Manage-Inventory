@extends('layouts.master')

@section('title')
    แผนรับสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <div></div>
                                <div>
                                    <div class="input-group">
                                        <input type="month" class="form-control" name="month" id="month"
                                            value="{{ $ReceiptPlanFilterMonth->first()?->date ? (new DateTime($ReceiptPlanFilterMonth->first()->date))->format('Y-m') : now()->format('Y-m') }}">
                                        <button type="button" class="btn btn-primary" id="btn-search-receipt-plan">
                                            <i class="fas fa-search" id="icon-search"></i>
                                            <div class="spinner-border spinner-border-sm text-light" id="icon-loading"
                                                style="display: none;" role="status">
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('AddReceiptPlan') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="month" id="hidden-month">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="day" class="form-label">วันที่</label>
                                            <select class="form-control" id="day-select" name="day">
                                                <!-- Options will be dynamically added here -->
                                            </select>
                                            <small class="form-text text-muted">**กรุณาเลือกวันที่ก่อนเลือกกะพนักงาน</small>
                                            @error('day')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_id">กะพนักงาน</label>
                                            <select class="form-control" id="shift_id" name="shift_id">
                                                <option value="">เลือกกะพนักงาน</option>
                                            </select>
                                            <span class="text-danger" id="shift_id-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-10 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <textarea class="form-control" id="note" name="note" placeholder="หมายเหตุ" rows="1"></textarea>
                                            @error('note')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="file">ไฟล์แผนรับสินค้า</label>
                                            <ol>
                                                <li>กรุณาเลือกไฟล์ .xlsx, .xls, .csv</li>
                                                <li>กรุณาใช้ไฟล์ <a
                                                        href="{{ url('storage/FormExcel/FormReceiptPlan.xlsx') }}"
                                                        download>แบบฟอร์มแผนรับสินค้า</a>
                                                </li>
                                            </ol>
                                            <input type="file" class="form-control" name="file"
                                                accept=".xlsx, .xls, .csv">
                                            @error('file')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row-reverse">
                                        <button type="submit" class="btn btn-success">เพิ่มแผนรับสินค้า</button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <table id="ReceiptPlanTable" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>วันที่</th>
                                        <th>กะ</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ReceiptPlanFilterMonth as $ReceiptPlan)
                                        <tr>
                                            <td>{{ (new DateTime($ReceiptPlan->date))->format('d/m/Y') }}</td>
                                            <td>{{ $ReceiptPlan->shift_name }}</td>
                                            <td>{{ $ReceiptPlan->note ?? 'N/A' }}</td>
                                            <td>{{ $ReceiptPlan->status ? 'ใช้งาน' : 'ไม่ใช้งาน' }}</td>
                                            <td>
                                                <a href="{{ route('EditReceiptPlan', $ReceiptPlan->receipt_plan_id) }}"
                                                    class="btn btn-primary"><i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('ShiftToggle', [$ReceiptPlan->shift_id, $ReceiptPlan->status ? 0 : 1]) }}"
                                                    class="btn {{ $ReceiptPlan->status ? 'btn-danger' : 'btn-success' }}">
                                                    <i
                                                        class="fas {{ $ReceiptPlan->status ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th>วันที่</th>
                                    <th>กะ</th>
                                    <th>หมายเหตุ</th>
                                    <th>สถานะ</th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        const ReceiptPlanDataTable = $("#ReceiptPlanTable").DataTable({
            // responsive: true,
            // lengthChange: true,
            // autoWidth: false,
            info: false,
            scrollX: true,
            ordering: true,
            paging: true,
            pageLength: 40,
            lengthMenu: [10, 20, 40],
            order: [
                [0, 'desc']
            ]
        });

        document.getElementById('btn-search-receipt-plan').addEventListener('click', function() {
            document.getElementById('icon-loading').style.display = 'inline-block';
            document.getElementById('icon-search').style.display = 'none';

            const month = document.getElementById('month').value;

            fetch(`{{ route('ReceiptPlanFilterMonth') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        month: month
                    }),
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    if (!data || !data.ReceiptPlanFilterMonth || data.ReceiptPlanFilterMonth.length === 0) {
                        alert('ไม่พบข้อมูล');
                        document.getElementById('icon-loading').style.display = 'none';
                        document.getElementById('icon-search').style.display = 'inline-block';
                        return;
                    }

                    // ล้างข้อมูลใน DataTable
                    ReceiptPlanDataTable.clear();

                    // เพิ่มข้อมูลใหม่ใน DataTable
                    const newRows = data.ReceiptPlanFilterMonth.map((plan) => [
                        plan.receipt_plan_id,
                        plan.shift_name,
                        formatDate(plan.date),
                        plan.note ?? 'ไม่มีหมายเหตุ',
                        plan.status ? 'ใช้งาน' : 'ไม่ใช้งาน',
                        `
                    <div class="d-flex">
                        <a href="{{ route('EditReceiptPlan', '') }}/${plan.receipt_plan_id}" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    `
                    ]);

                    // เพิ่มข้อมูลใหม่และรีเฟรช DataTable
                    ReceiptPlanDataTable.rows.add(newRows).draw();
                    document.getElementById('icon-loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';
                })
                .catch((error) => {
                    console.error('Error:', error);
                    document.getElementById('icon-loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';
                });

            // ฟังก์ชันสำหรับจัดรูปแบบวันที่
            function formatDate(date) {
                const d = new Date(date);
                return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`;
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const monthInput = document.getElementById('month');
            const daySelect = document.getElementById('day-select');
            const shiftSelect = document.getElementById('shift_id');

            // ฟังก์ชันสร้างรายการวันที่
            const populateDays = (year, month) => {
                daySelect.innerHTML = '<option value="">เลือกวันที่</option>';
                const daysInMonth = new Date(year, month, 0).getDate();
                for (let day = 1; day <= daysInMonth; day++) {
                    const option = document.createElement('option');
                    option.value = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    option.textContent = day;
                    daySelect.appendChild(option);
                }
            };

            // ฟังก์ชันดึงข้อมูลกะพนักงาน
            const fetchShifts = (date) => {
                fetch(`{{ route('GetShifts') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            date: date
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch shifts');
                        }
                        return response.json();
                    })
                    .then(data => {
                        shiftSelect.innerHTML = '<option selected value="">เลือกกะพนักงาน</option>';

                        if (data.shifts && data.shifts.length > 0) {
                            data.shifts.forEach(shift => {
                                const option = document.createElement('option');
                                option.value = shift.shift_id;
                                option.textContent =
                                    `${shift.shift_name} (${formatTime(shift.start_shift)} - ${formatTime(shift.end_shift)})`;
                                shiftSelect.appendChild(option);
                            });
                        } else {
                            const noDataOption = document.createElement('option');
                            noDataOption.value = '';
                            noDataOption.textContent = 'ไม่มีข้อมูล';
                            shiftSelect.appendChild(noDataOption);
                        }

                    })
                    .catch(error => {
                        console.error('Error fetching shifts:', error);
                        shiftSelect.innerHTML = '<option value="">เกิดข้อผิดพลาดในการโหลดข้อมูล</option>';
                    });
            };

            function formatTime(time) {
                const [hour, minute] = time.split(':');
                return `${hour}:${minute} น.`;
            }

            // ตั้งค่าเริ่มต้นเมื่อหน้าโหลด
            const now = new Date();
            populateDays(now.getFullYear(), now.getMonth() + 1);

            // อัปเดตรายการวันที่เมื่อเปลี่ยนเดือน
            monthInput.addEventListener('change', () => {
                const [year, month] = monthInput.value.split('-');
                populateDays(parseInt(year), parseInt(month));
            });

            // ดึงข้อมูลกะพนักงานเมื่อเลือกวันที่
            daySelect.addEventListener('change', (event) => {
                const selectedDate = event.target.value;
                if (selectedDate) {
                    fetchShifts(selectedDate);
                } else {
                    shiftSelect.innerHTML = '<option value="">เลือกกะพนักงาน</option>';
                }
            });
        });
    </script>
@endsection
