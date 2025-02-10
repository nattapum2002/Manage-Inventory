@extends('layouts.master')

@section('title')
    คำสั่งซื้อ
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <div>
                                </div>
                                <div>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="date" id="date"
                                            value="{{ now()->format('Y-m-d') }}">
                                        <button id="btn-search-date" type="button" class="btn btn-primary">
                                            <i class="fas fa-search" id="icon-search"></i>
                                            <div class="spinner-border spinner-border-sm text-light" id="loading"
                                                role="status" style="display: none;"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="OrdersTable" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <th>ลำดับ</th>
                                    <th>หมายเลขออเดอร์</th>
                                    <th>ชื่อลูกค้า</th>
                                    <th>เกรด</th>
                                    <th>เวลา</th>
                                    <th>วันที่</th>
                                    <th></th>
                                </thead>
                                <tbody id="ordersTableBody">

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
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        function initDataTable(selector) {
            return $(selector).DataTable({
                info: false,
                scrollX: true,
                ordering: true,
                paging: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50],
                order: [
                    [0, 'asc']
                ],
            });
        }

        const OrdersTable = initDataTable("#OrdersTable");

        function loadOrdersData(date) {
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
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';

                    if (!data || !data.CustomerQueues || data.CustomerQueues.length === 0) {
                        alert('ไม่พบข้อมูล');
                        return;
                    }

                    // ล้างข้อมูลใน DataTable
                    OrdersTable.clear();

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
                    OrdersTable.rows.add(newRows).draw();
                })
                .catch(error => {
                    document.getElementById('loading').style.display = 'none';
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
            document.getElementById('loading').style.display = 'inline-block';
            document.getElementById('icon-search').style.display = 'none';
            const date = document.getElementById('date').value;

            loadOrdersData(date);
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('loading').style.display = 'inline-block';
            document.getElementById('icon-search').style.display = 'none';
            const date = document.getElementById('date').value;

            loadOrdersData(date);
        });
    </script>
@endsection
