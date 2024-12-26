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
                                        value="{{ $customer_queue['ORDER_NUMBER'] ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <label for="customer_id">รหัสลูกค้า</label>
                                    <input type="text" id="customer_id" name="customer_id" class="form-control"
                                        value="{{ $customer_queue['CUSTOMER_ID'] ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-lg-4 col-md-7 col-sm-12">
                                    <label for="customer_name">ชื่อลูกค้า</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control"
                                        value="{{ $customer_queue['CUSTOMER_NAME'] ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-lg-1 col-md-2 col-sm-12">
                                    <label for="customer_grade">เกรด</label>
                                    <input type="text" id="customer_grade" name="customer_grade" class="form-control"
                                        value="{{ $customer_queue['CUST_GRADE'] ?? 'N/A' }}" readonly>
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
                                    {{-- <input type="text" id="queue_time" name="queue_time" class="form-control"
                                        value="{{ $customer_queue->queue_time ? (new DateTime($customer_queue->queue_time))->format('H:i') : 'N/A' }}"
                                        readonly> --}}
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="queue_date">วันที่ตามคิว</label>
                                    <input type="text" id="queue_date" name="queue_date" class="form-control"
                                        value="{{ $customer_queue['SCHEDULE_SHIP_DATE'] ? (new DateTime($customer_queue['SCHEDULE_SHIP_DATE']))->format('d/m/Y') : 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="entry_time">เวลาเข้า</label>
                                    <input type="text" id="entry_time" name="entry_time" class="form-control"
                                        value="{{ $customer_queue['TIME_QUE'] ? (new DateTime($customer_queue['TIME_QUE']))->format('H:i') : 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="entry_date">วันที่เข้า</label>
                                    {{-- <input type="text" id="entry_date" name="entry_date" class="form-control"
                                        value="{{ $customer_queue->entry_date ? (new DateTime($customer_queue->entry_date))->format('d/m/Y') : 'N/A' }}"
                                        readonly> --}}
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="release_time">เวลาออก</label>
                                    <input type="text" id="release_time" name="release_time" class="form-control"
                                        value="{{ $customer_queue['TIME_EXIT'] ? (new DateTime($customer_queue['TIME_EXIT']))->format('H:i') : 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="release_date">วันที่ออก</label>
                                    {{-- <input type="text" id="release_date" name="release_date" class="form-control"
                                        value="{{ $customer_queue->release_date ? (new DateTime($customer_queue->release_date))->format('d/m/Y') : 'N/A' }}"
                                        readonly> --}}
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
                            <table id="customer-queue-pallet" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>หมายเลขสินค้า</th>
                                        <th>รายการ</th>
                                        <th>จำนวน
                                            <small>(ORDERED_QUANTITY)</small>
                                        </th>
                                        <th>จำนวน
                                            <small>(ORDER_BY_CUS)</small>
                                        </th>
                                        <th>หน่วย <small>(UOM)</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_queue['ITEMS'] as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item['ITEM_ID'] }}</td>
                                            <td>{{ $item['ITEM_DESC1'] }}</td>
                                            <td>{{ $item['ORDERED_QUANTITY'] }}</td>
                                            <td>{{ $item['ORDER_BY_CUS'] }}</td>
                                            <td>{{ $item['QUANTITY_UOM'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
            responsive: true,
            lengthChange: true,
            autoWidth: false,
        });
    </script>
@endsection
