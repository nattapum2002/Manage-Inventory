@extends('layouts.master')

@section('title')
    รายการสินค้าออก: {{ $date }}
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <table id="DispenseStatTable" class="table table-striped table-bordered nowrap">
                        <thead>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th class="text-center">ออก</th>
                            <th class="text-center">คงเหลือ</th>
                            <th>หน่วย</th>
                            <th class="text-center">ออก</th>
                            <th class="text-center">คงเหลือ</th>
                            <th>หน่วย</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->product_number }}</td> <!-- แสดง product_id -->
                                    <td>{{ $item->product_description }}</td> <!-- เข้าถึง 'product_name' -->
                                    <td class="text-center text-danger">- {{ $item->transaction_quantity_out }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td>{{ $item->product_um }}</td>
                                    <td class="text-center text-danger ">- {{ $item->transaction_quantity_out2 }}</td>
                                    <td class="text-center">{{ $item->quantity2 }}</td>
                                    <td>{{ $item->product_um2 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        const DispenseStatTable = $('#DispenseStatTable').DataTable({
            info: false,
            ordering: true,
            paging: true,
            scrollX: true,
            // responsive: true,
            // lengthChange: true,
            pageLength: 40,
            lengthMenu: [10, 20, 40],
            order: [
                [0, 'desc']
            ]
        })
    </script>
@endsection
