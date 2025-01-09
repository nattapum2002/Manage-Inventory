@extends('layouts.master')
@section('title')
    Incentive : ลากจ่ายสินค้า - พนักงาน - รายละเอียด <br>/ {{ $Dragincentive[0]->name . ' ' . $Dragincentive[0]->surname }}
    :
    {{ $Dragincentive[0]->month_name }} / {{ $year }}
@endsection
@section('content')
    <section class="container">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <table id="incentive-Em-detail-table" class="table table-bordered table-striped text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>ออเดอร์วันที่</th>
                                <th>รหัสสินค้า</th>
                                <th>ชื่อสินค้า</th>
                                <th>จำนวน</th>
                                <th>หน่วย</th>
                                <th>ชื่อลูกค้า</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Dragincentive as $item)
                                <tr>
                                    <td>{{ $item->order_date }}</td>
                                    <td>{{ $item->item_no }}</td>
                                    <td>{{ $item->item_desc1 }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Kg.</td>
                                    <td>{{ $item->customer_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="5" class="text-end fw-bold">น้ำหนักรวม</td>
                                <td>{{ $total['total_weight'] }}</td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="5" class="text-end fw-bold">incentive</td>
                                <td>{{ $total['total_incentive'] }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
