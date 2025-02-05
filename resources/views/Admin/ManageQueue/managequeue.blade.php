@extends('layouts.master')

@section('title')
    จัดการคิวลูกค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            {{-- <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>เพิ่มคิวลูกค้า</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('AddCustomerQueue') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="file">ไฟล์คิวลูกค้า</label>
                                    <ol>
                                        <li>กรุณาเลือกไฟล์ .xlsx, .xls, .csv</li>
                                        <li>กรุณาใช้ไฟล์ <a href="{{ url('storage/FormExcel/FormCustomerQueue.xlsx') }}"
                                                download>แบบฟอร์มคิวลูกค้า</a>
                                        </li>
                                    </ol>
                                    <input type="file" class="form-control" name="file" accept=".xlsx, .xls, .csv">
                                    @error('file')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-success">เพิ่ม</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> --}}
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('syncQueue') }}" class="btn btn-primary" id="syncQueue">
                                <span id="syncQueueText">syncQueue</span>
                                <div class="spinner-border spinner-border-sm text-light" id="loading"
                                    style="display: none;" role="status">
                                </div>
                            </a>
                        </div>
                        <div>
                            <div class="input-group">
                                <input type="date" class="form-control" name="date" id="date"
                                    value="{{ now()->format('Y-m-d') }}">
                                <button id="btn-search-date" type="button" class="btn btn-primary">
                                    <i class="fas fa-search" id="icon-search"></i>
                                    <div class="spinner-border spinner-border-sm text-light" id="icon-loading"
                                        role="status" style="display: none;"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <small class="form-text text-muted">**การ Sync ข้อมูลอาจใช้เวลามากกว่า 10 นาที</small>
                </div>
                <div class="card-body">
                    <table id="CustomerQueueTable" class="table table-striped table-bordered nowrap">
                        <thead>
                            <th>ลำดับ</th>
                            <th>หมายเลขออเดอร์</th>
                            <th>ชื่อลูกค้า</th>
                            <th>เกรด</th>
                            <th>เวลา</th>
                            <th>วันที่</th>
                            <th></th>
                        </thead>
                        <tbody id="queueTableBody">
                        </tbody>
                        <tfoot>
                            <th>ลำดับ</th>
                            <th>หมายเลขออเดอร์</th>
                            <th>ชื่อลูกค้า</th>
                            <th>เกรด</th>
                            <th>เวลา</th>
                            <th>วันที่</th>
                            <th></th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        // กำหนด DataTable
        const dataTable = $("#CustomerQueueTable").DataTable({
            // responsive: true,
            // lengthChange: true,
            // autoWidth: false
            info: false,
            scrollX: true,
            ordering: true,
            paging: true,
            pageLength: 25,
            lengthMenu: [25, 50, 100],
            order: [
                [0, 'asc']
            ]
        });

        function loadManageQueueData(date) {
            fetch(`{{ route('ManageQueueFilterDate') }}`, {
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
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('icon-loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';
                    if (!data || !data.CustomerQueues || data.CustomerQueues.length === 0) {
                        alert('ไม่พบข้อมูล');
                        return;
                    }

                    // ล้างข้อมูลใน DataTable
                    dataTable.clear();

                    // เพิ่มข้อมูลใหม่ใน DataTable
                    const newRows = data.CustomerQueues.map((queue) => [
                        queue.queue_number,
                        queue.order_number,
                        queue.customer_name || 'ไม่มีชื่อ',
                        queue.customer_grade || 'N/A',
                        formatTime(queue.ship_datetime) || 'N/A',
                        formatDate(queue.ship_datetime) || 'N/A',
                        `<a href="{{ url('ManageQueue/Detail') }}/${queue.order_number}" class="btn btn-primary">
                        <i class="fas fa-info-circle"></i>
                    </a>`
                    ]);

                    // อัปเดต DataTable
                    dataTable.rows.add(newRows).draw();
                })
                .catch(error => {
                    document.getElementById('icon-loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';
                    console.error('Error:', error)
                });
        }

        function formatDate(date) {
            const d = new Date(date);
            return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`;
        }

        function formatTime(datetime) {
            const d = new Date(datetime);
            if (isNaN(d)) return "Invalid Date";
            const hours = d.getHours().toString().padStart(2, '0');
            const minutes = d.getMinutes().toString().padStart(2, '0');
            return `${hours}:${minutes}`;
        }

        document.getElementById('btn-search-date').addEventListener('click', function() {
            document.getElementById('icon-loading').style.display = 'inline-block';
            document.getElementById('icon-search').style.display = 'none';
            const date = document.getElementById('date').value;

            loadManageQueueData(date);
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('icon-loading').style.display = 'inline-block';
            document.getElementById('icon-search').style.display = 'none';
            const date = document.getElementById('date').value;

            loadManageQueueData(date);
        });
    </script>

    <script>
        document.getElementById('syncQueue').addEventListener('click', function() {
            document.getElementById('loading').style.display = 'inline-block';
            document.getElementById('syncQueueText').style.display = 'none';
            document.getElementById('syncQueue').disabled = true;
        });

        window.onload = function() {
            @if (session('success'))
                document.getElementById('loading').style.display = 'none';
                document.getElementById('syncQueueText').style.display = 'inline-block';
                document.getElementById('syncQueue').disabled = false;
            @elseif ($errors->any())
                document.getElementById('loading').style.display = 'none';
                document.getElementById('syncQueueText').style.display = 'inline-block';
                document.getElementById('syncQueue').disabled = false;
            @endif
        }
    </script>
@endsection
