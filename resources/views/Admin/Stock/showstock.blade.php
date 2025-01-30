@extends('layouts.master')

@section('title')
    จัดการรายการสินค้าในคลัง
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center flex-wrap">
                            <a class="btn btn-primary me-3" href="{{ route('SyncProduct') }}" id="SyncProduct">
                                <span id="SyncProductText">Sync ข้อมูลสินค้า</span>
                                <div class="spinner-border spinner-border-sm text-light" id="loadingSync"
                                    style="display: none;" role="status"></div>
                            </a>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="form-check me-2">
                                <input type="checkbox" class="form-check-input" name="ShowAll" id="ShowAll">
                                <label for="ShowAll" class="form-check-label">แสดงข้อมูลทั้งหมด</label>
                            </div>
                            <div class="input-group">
                                <select name="warehouse" class="form-control" id="warehouse">
                                    <option selected value="All">ทั้งหมด</option>
                                    @foreach ($warehouses as $item)
                                        <option value="{{ $item->warehouse_name }}">{{ $item->warehouse_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-primary" id="btn-search-product">
                                    <i class="fas fa-search" id="icon-search"></i>
                                    <div class="spinner-border spinner-border-sm text-light" id="loading"
                                        style="display: none;" role="status"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <small class="form-text text-muted">**การ Sync ข้อมูลอาจใช้เวลามากกว่า 10 นาที</small>
                </div>

                <div class="card-body">
                    <table id="StockTable" class="table table-bordered table-striped nowrap">
                        <thead>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>จำนวน</th>
                            <th>ห้องเก็บ</th>
                            <th>จัดการ</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        const StockTable = $("#StockTable").DataTable({
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

        function LoadStockData(warehouse, showAll) {
            fetch(`{{ route('StockFilter') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        warehouse: warehouse,
                        ShowAll: showAll
                    }),
                })
                .then((response) => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then((data) => {
                    if (!data.ProductStock || data.ProductStock.length == 0) {
                        alert('ไม่พบข้อมูล');

                        document.getElementById('loading').style.display = 'none';
                        document.getElementById('icon-search').style.display = 'inline-block';

                        return;
                    }

                    StockTable.clear();

                    const newRows = data.ProductStock.map(item => [
                        item.product_number,
                        item.product_description,
                        `
                            <p>${item.quantity} ${item.product_um}</p>
                            <span class="text-secondary">${item.quantity2} ${item.product_um2}</span>
                        `,
                        item.warehouse ?? 'N/A',
                        `<a class="btn btn-primary" href="/ShowStock/ProductDetail/${item.product_number}">แก้ไข</a>`, // ปุ่มแก้ไข
                    ]);
                    StockTable.rows.add(newRows).draw();

                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';
                })
                .catch(error => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';
                    console.error('Error:', error);
                });
        }

        document.getElementById('btn-search-product').addEventListener('click', function() {
            const warehouse = document.getElementById('warehouse').value;
            const showAll = document.getElementById('ShowAll').checked ? true : false;

            document.getElementById('loading').style.display = 'inline-block';
            document.getElementById('icon-search').style.display = 'none';

            LoadStockData(warehouse, showAll);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const warehouse = document.getElementById('warehouse').value;
            const showAll = document.getElementById('ShowAll').checked ? true : false;

            document.getElementById('loading').style.display = 'inline-block';
            document.getElementById('icon-search').style.display = 'none';
            LoadStockData(warehouse, showAll);
        });
    </script>

    <script>
        document.getElementById('SyncProduct').addEventListener('click', function() {
            document.getElementById('loadingSync').style.display = 'inline-block';
            document.getElementById('SyncProductText').style.display = 'none';
            document.getElementById('SyncProduct').disabled = true;
        });

        window.onload = function() {
            @if (session('success'))
                document.getElementById('loadingSync').style.display = 'none';
                document.getElementById('SyncProductText').style.display = 'inline-block';
                document.getElementById('SyncProduct').disabled = false;
            @elseif ($errors->any())
                document.getElementById('loadingSync').style.display = 'none';
                document.getElementById('SyncProductText').style.display = 'inline-block';
                document.getElementById('SyncProduct').disabled = false;
            @endif
        }
    </script>
@endsection
