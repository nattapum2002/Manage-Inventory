@extends('layouts.master')
@section('title')
    Incentive : จัดสินค้า - พนักงาน - รายละเอียด / {{ $arrangeincentive[0]->name . ' ' . $arrangeincentive[0]->surname }} :
    {{ $date }}
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
                                <th>ลักษณะงาน</th>
                                <th>ชื่อลูกค้า</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($arrangeincentive as $item)
                                <tr>
                                    <td>{{ $item->order_date}}</td>
                                    <td>{{ $item->item_no }}</td>
                                    <td>{{ $item->item_desc1 }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Kg.</td>
                                    <td>{{ $item->product_work_desc }}</td>
                                    <td>{{ $item->customer_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="6" class="text-end fw-bold">Incentive แยกจ่าย (KG.)</td>
                                <td>{{ $incentive_data['total_incentive_Org'] }}</td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="6" class="text-end fw-bold">Incentive รับจัด (KG.)</td>
                                <td>{{ $incentive_data['total_incentive_Spl'] }}</td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="6" class="text-end fw-bold">Incentive เลือด (KG.)</td>
                                <td>{{ $incentive_data['total_incentive_Bl'] }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
