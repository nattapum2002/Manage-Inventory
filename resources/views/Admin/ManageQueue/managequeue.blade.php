@extends('layouts.master')

@section('title')
    จัดการคิวลูกค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
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
            </div>
            <div class="card">
                <article class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>รายชื่อคิวลูกค้า</h5>
                        <div>
                            <form action="{{ route('ManageQueueFilterDate') }}" method="post">
                                @csrf
                                <div class="input-group">
                                    <input type="date" class="form-control" name="date" id="date"
                                        value="{{ $CustomerQueues->first()->queue_date ?? now()->format('Y-m-d') }}">
                                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </article>
                <article class="card-body">
                    <table id="CustomerQueueTable" class="table table-striped">
                        <thead>
                            <th>ลำดับ</th>
                            <th>หมายเลขออเดอร์</th>
                            <th>ชื่อลูกค้า</th>
                            <th>เกรด</th>
                            <th>เวลา</th>
                            <th>หมายเหตุ</th>
                            <th></th>
                        </thead>
                        <tbody id="queueTableBody">
                            @foreach ($CustomerQueues as $queue)
                                <tr>
                                    <td>{{ $queue->queue_no }}</td>
                                    <td>{{ number_format($queue->order_number, 0, '.', '') }}</td>
                                    <td>{{ $queue->customer_name ?? 'ไม่มีชื่อ' }}</td>
                                    <td>{{ $queue->customer_grade ?? 'N/A' }}</td>
                                    <td>{{ (new DateTime($queue->queue_time))->format('H:i') }}</td>
                                    <td>{{ $queue->note }}</td>
                                    <td>
                                        <a href="{{ route('DetailCustomerQueue', ['order_number' => $queue->order_number]) }}"
                                            class="btn btn-primary"><i class="fas fa-info-circle"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th>ลำดับ</th>
                            <th>หมายเลขออเดอร์</th>
                            <th>ชื่อลูกค้า</th>
                            <th>เกรด</th>
                            <th>เวลา</th>
                            <th>หมายเหตุ</th>
                            <th></th>
                        </tfoot>
                    </table>
                </article>
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
    </script>
@endsection
