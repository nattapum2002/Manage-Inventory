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
                                <div class="col-lg-1 col-md-2 col-sm-12">
                                    <label for="queue_no">ลําดับคิว</label>
                                    <input type="text" id="queue_no" name="queue_no" class="form-control"
                                        value="{{ $customer_queue->queue_no ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="order_number">หมายเลขออเดอร์</label>
                                    <input type="text" id="order_number" name="order_number" class="form-control"
                                        value="{{ number_format($customer_queue->order_number, 0, '.', '') }}" readonly>
                                </div>
                                <div class="col-lg-1 col-md-2 col-sm-12">
                                    <label for="customer_id">รหัสลูกค้า</label>
                                    <input type="text" id="customer_id" name="customer_id" class="form-control"
                                        value="{{ $customer_queue->customer_id ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="customer_number">หมายเลขลูกค้า</label>
                                    <input type="text" id="customer_number" name="customer_number" class="form-control"
                                        value="{{ $customer_queue->customer_number ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-lg-4 col-md-7 col-sm-12">
                                    <label for="customer_name">ชื่อลูกค้า</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control"
                                        value="{{ $customer_queue->customer_name ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-lg-1 col-md-2 col-sm-12">
                                    <label for="customer_grade">เกรด</label>
                                    <input type="text" id="customer_grade" name="customer_grade" class="form-control"
                                        value="{{ $customer_queue->customer_grade ?? 'N/A' }}" readonly>
                                </div>
                                <div class="col-lg-1 col-md-2 col-sm-12">
                                    <label for="status">สถานะ</label>
                                    <input type="text" id="status" name="status" class="form-control"
                                        value="{{ $customer_queue->status ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="queue_time">เวลาตามคิว</label>
                                    <input type="text" id="queue_time" name="queue_time" class="form-control"
                                        value="{{ (new DateTime($customer_queue->queue_time))->format('H:i') }}" readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="queue_date">วันที่ตามคิว</label>
                                    <input type="text" id="queue_date" name="queue_date" class="form-control"
                                        value="{{ (new DateTime($customer_queue->queue_date))->format('d/m/Y') }}" readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="entry_time">เวลาเข้า</label>
                                    <input type="text" id="entry_time" name="entry_time" class="form-control"
                                        value="{{ $customer_queue->entry_time ? (new DateTime($customer_queue->entry_time))->format('H:i') : 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="entry_date">วันที่เข้า</label>
                                    <input type="text" id="entry_date" name="entry_date" class="form-control"
                                        value="{{ $customer_queue->entry_date ? (new DateTime($customer_queue->entry_date))->format('d/m/Y') : 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="release_time">เวลาออก</label>
                                    <input type="text" id="release_time" name="release_time" class="form-control"
                                        value="{{ $customer_queue->release_time ? (new DateTime($customer_queue->release_time))->format('H:i') : 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <label for="release_date">วันที่ออก</label>
                                    <input type="text" id="release_date" name="release_date" class="form-control"
                                        value="{{ $customer_queue->release_date ? (new DateTime($customer_queue->release_date))->format('d/m/Y') : 'N/A' }}"
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
                            จำนวนพาเลท
                        </div>
                        <div class="card-body">
                            <table id="customer-queue-pallet" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>หมายเลขพาเลท</th>
                                        <th>ห้องเก็บ</th>
                                        <th>ประเภท</th>
                                        <th>สถานะ</th>
                                        <th>สถานะการส่ง</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dd($pallet) --}}
                                    @foreach ($pallet as $item)
                                        <tr>
                                            <td>{{ $item->pallet_no }}</td>
                                            <td>{{ $item->room }}</td>
                                            <td>{{ $item->pallet_type }}</td>
                                            <td>{!! $item->status == 0 ? '<p class="text-danger">ยังไม่จัด</p>' : '<p class="text-success">จัดแล้ว</p>' !!}</td>
                                            <td>{!! $item->recive_status == 0 ? '<p class="text-danger">ยังไม่ส่ง</p>' : '<p class="text-success">ส่งแล้ว</p>' !!}</td>
                                            <td>
                                                <a href="{{ route('QueuePalletDetail', [$item->pallet_id, $item->order_id]) }}"
                                                    class="btn btn-primary">ดู</a>
                                            </td>
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
        $("#CustomerQueueTable").DataTable({
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
        $("#customer-queue-pallet").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            // scrollX: true,
        });
    </script>
@endsection
