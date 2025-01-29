@extends('layouts.master')

@section('title')
    รายละเอียดคิวลูกค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">รายละเอียดคิวลูกค้า</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="order_number">หมายเลขออเดอร์</label>
                                    <input type="text" id="order_number" name="order_number" class="form-control"
                                        value="{{ $customer_queue['order_number'] ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <label for="customer_id">รหัสลูกค้า</label>
                                    <input type="text" id="customer_id" name="customer_id" class="form-control"
                                        value="{{ $customer_queue['customer_id'] ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-lg-4 col-md-7 col-sm-12">
                                    <label for="customer_name">ชื่อลูกค้า</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control"
                                        value="{{ $customer_queue['customer_name'] ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-lg-1 col-md-2 col-sm-12">
                                    <label for="customer_grade">เกรด</label>
                                    <input type="text" id="customer_grade" name="customer_grade" class="form-control"
                                        value="{{ $customer_queue['customer_grade'] ?? 'N/A' }}" readonly>
                                </div>
                                {{-- <div class="col-lg-1 col-md-2 col-sm-12">
                                    <label for="status">สถานะ</label>
                                    <input type="text" id="status" name="status" class="form-control"
                                        value="{{ $customer_queue->status ?? 'N/A' }}" readonly>
                                </div> --}}
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="queue_time">เวลาตามคิว</label>
                                    <input type="text" id="queue_time" name="queue_time" class="form-control"
                                        value="{{ $customer_queue['ship_datetime'] ? (new DateTime($customer_queue['ship_datetime']))->format('H:i') : 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="queue_date">วันที่ตามคิว</label>
                                    <input type="text" id="queue_date" name="queue_date" class="form-control"
                                        value="{{ $customer_queue['ship_datetime'] ? (new DateTime($customer_queue['ship_datetime']))->format('d/m/Y') : 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="entry_time">เวลาเข้า</label>
                                    <input type="text" id="entry_time" name="entry_time" class="form-control"
                                        value="{{ $customer_queue['entry_datetime'] ? (new DateTime($customer_queue['entry_datetime']))->format('H:i') : 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="entry_date">วันที่เข้า</label>
                                    <input type="text" id="entry_date" name="entry_date" class="form-control"
                                        value="{{ $customer_queue['entry_datetime'] ? (new DateTime($customer_queue['entry_datetime']))->format('d/m/Y') : 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="release_time">เวลาออก</label>
                                    <input type="text" id="release_time" name="release_time" class="form-control"
                                        value="{{ $customer_queue['release_datetime'] ? (new DateTime($customer_queue['release_datetime']))->format('H:i') : 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="release_date">วันที่ออก</label>
                                    <input type="text" id="release_date" name="release_date" class="form-control"
                                        value="{{ $customer_queue['release_datetime'] ? (new DateTime($customer_queue['release_datetime']))->format('d/m/Y') : 'N/A' }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            รายการการสั่งซื้อ
                        </div>
                        <div class="card-body">
                            <table id="customer-queue-pallet" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th>จำนวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_queue['products'] as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item['product_number'] }}</td>
                                            <td>{{ $item['product_description'] }}</td>
                                            <td>
                                                <p>{{ $item['quantity'] . ' ' . $item['product_um'] }}</p>
                                                <span>{{ $item['quantity2'] . ' ' . $item['product_um2'] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th>จำนวน</th>
                                    </tr>
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
        $("#customer-queue-pallet").DataTable({
            // responsive: true,
            // lengthChange: true,
            // autoWidth: false,
            info: false,
            scrollX: true,
            ordering: true,
            paging: true,
            pageLength: 25,
            lengthMenu: [25, 50, 100],
            order: []
        });
    </script>
@endsection
