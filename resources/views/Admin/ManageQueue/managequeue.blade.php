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
                <article class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>รายชื่อคิวลูกค้า</h5>
                        <div>
                            <div class="input-group">
                                <input type="date" class="form-control" name="date" id="date"
                                    value="{{ now()->format('Y-m-d') }}">
                                <button id="btn-search-date" type="button" class="btn btn-primary">ค้นหา</button>
                            </div>
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
                            <th>วันที่</th>
                            <th></th>
                        </thead>
                        <tbody id="queueTableBody">
                            @foreach ($CustomerQueues as $queue)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $queue->ORDER_NUMBER }}</td>
                                    <td>{{ $queue->CUSTOMER_NAME ?? 'ไม่มีชื่อ' }}</td>
                                    <td>{{ $queue->CUST_GRADE ?? 'N/A' }}</td>
                                    <td>{{ $queue->TIME_QUE ?? 'N/A' }}</td>
                                    <td>{{ (new DateTime($queue->SCHEDULE_SHIP_DATE0))->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('DetailCustomerQueue', ['order_number' => $queue->ORDER_NUMBER]) }}"
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
                            <th>วันที่</th>
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
        // กำหนด DataTable
        const dataTable = $("#CustomerQueueTable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false
        });

        document.getElementById('btn-search-date').addEventListener('click', function() {
            const date = document.getElementById('date').value;

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
                    if (!data || !data.CustomerQueues) {
                        console.error('Data structure is incorrect or CustomerQueues is missing');
                        return;
                    }

                    // ล้างข้อมูลใน DataTable
                    dataTable.clear();

                    if (data.CustomerQueues.length === 0) {
                        console.warn('No data available in the result.');
                        return;
                    }

                    // เพิ่มข้อมูลใหม่ใน DataTable
                    const newRows = data.CustomerQueues.map((queue, index) => [
                        index + 1,
                        queue.ORDER_NUMBER,
                        queue.CUSTOMER_NAME || 'ไม่มีชื่อ',
                        queue.CUST_GRADE || 'N/A',
                        queue.TIME_QUE || 'N/A',
                        formatDate(queue.SCHEDULE_SHIP_DATE) || 'N/A',
                        `<a href="{{ url('ManageQueue/Detail') }}/${queue.ORDER_NUMBER}" class="btn btn-primary">
                        <i class="fas fa-info-circle"></i>
                    </a>`
                    ]);

                    // อัปเดต DataTable
                    dataTable.rows.add(newRows).draw();
                })
                .catch(error => console.error('Error:', error));

            function formatDate(date) {
                const d = new Date(date);
                return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`;
            }
        });
    </script>
@endsection
