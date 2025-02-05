@extends('layouts.master')

@section('title')
    ค่า Incentive
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Incentive รับสินค้า</span>
                            <span class="info-box-number">
                                <span id="SumReceipt">0.0</span>
                                <small>Kg</small>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Incentive จัดสินค้า</span>
                            <span class="info-box-number">
                                <span id="SumArrange">0.0</span>
                                <small>Kg</small>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Incentive ส่งสินค้า</span>
                            <span class="info-box-number">
                                <span id="SumSend">0.0</span>
                                <small>Kg</small>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Incentive เลือด</span>
                            <span class="info-box-number">
                                <span id="SumBlood">0.0</span>
                                <small>Kg</small>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <div></div>
                                <div>
                                    <div class="input-group">
                                        <input type="month" class="form-control" name="month" id="month"
                                            value="{{ now()->format('Y-m') }}">
                                        <button type="button" class="btn btn-primary" id="btn-search-employee-incentive">
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
                            <table id="EmployeeIncentiveTable" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>ลําดับ</th>
                                        <th>วันที่</th>
                                        <th>หมายเลขคำสั่งซื้อ</th>
                                        <th>น้ำหนัก</th>
                                        <th>ประเภท Incentive</th>
                                        <th>เริ่ม</th>
                                        <th>สิ้นสุด</th>
                                    </tr>
                                </thead>
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
        const EmployeeIncentiveTable = $("#EmployeeIncentiveTable").DataTable({
            // responsive: true,
            // lengthChange: true,
            // autoWidth: true,
            info: false,
            scrollX: true,
            ordering: true,
            paging: true,
            pageLength: 25,
            lengthMenu: [25, 50, 100],
            order: []
        });

        function loadEmployeeIncentiveData(month) {
            document.getElementById('icon-loading').style.display = 'inline-block';
            document.getElementById('icon-search').style.display = 'none';

            const SumReceipt = document.getElementById('SumReceipt');
            const SumArrange = document.getElementById('SumArrange');
            const SumSend = document.getElementById('SumSend');
            const SumBlood = document.getElementById('SumBlood');

            fetch(`{{ route('EmployeeIncentiveData') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        month: month,
                    }),
                })
                .then((response) => {
                    // console.log(data);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.IncentiveTransactions.length > 0) {
                        SumReceipt.innerText = data.SumReceipt;
                        SumArrange.innerText = data.SumArrange;
                        SumSend.innerText = data.SumSend;
                        SumBlood.innerText = data.SumBlood;

                        EmployeeIncentiveTable.clear();

                        const newRows = data.IncentiveTransactions.map((item, index) => [
                            index + 1,
                            formatDate(item.start_time),
                            item.order_number ?? 'N/A',
                            item.weight,
                            item.incentive_type,
                            formatTime(item.start_time),
                            formatTime(item.end_time)
                        ])

                        EmployeeIncentiveTable.rows.add(newRows).draw();

                        document.getElementById('icon-loading').style.display = 'none';
                        document.getElementById('icon-search').style.display = 'inline-block';
                    } else {
                        alert('ไม่พบข้อมูล');

                        document.getElementById('icon-loading').style.display = 'none';
                        document.getElementById('icon-search').style.display = 'inline-block';
                    }
                })
                .catch((error) => {
                    document.getElementById('icon-loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';
                    console.error('Error:', error);
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

        document.getElementById('btn-search-employee-incentive').addEventListener('click', function() {
            const month = document.getElementById('month').value;
            loadEmployeeIncentiveData(month);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const month = document.getElementById('month').value;
            loadEmployeeIncentiveData(month);
        });
    </script>
@endsection
