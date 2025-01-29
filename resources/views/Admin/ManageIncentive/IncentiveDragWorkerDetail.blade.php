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
                                <th>เลขออเดอร์</th>
                                <th>รหัสสินค้า</th>
                                <th>ชื่อสินค้า</th>
                                <th>จำนวน</th>
                                <th>หน่วย</th>
                                <th>จำนวน (เพิ่มเติม)</th>
                                <th>หน่วย (เพิ่มเติม)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Dragincentive as $item)
                                <tr>
                                    <td>{{ intval($item->order_id) }}</td>
                                    <td>{{ $item->product_number }}</td>
                                    <td>{{ $item->product_description }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->product_um }}</td>
                                    <td>{{ $item->quantity2 }}</td>
                                    <td>{{ $item->product_um2 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="6" class="text-end fw-bold">Incentive (KG.)</td>
                                <td>{{ $total_incentive_Kg }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
