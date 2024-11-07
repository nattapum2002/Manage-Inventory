@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง : รายละเอียดใบสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="card">
            {{-- <div class="card-header d-flex justify-content-start">
            <a class="btn btn-primary" href="{{route('Add item')}}">เพิ่มข้อมูลสินค้า</a>
        </div> --}}
            <div class="card-body">
                <table id="item_per_slip" class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="6">
                                <div class="row">
                                    <div class="col-3">รหัสสลิป : {{ $slip_id }}</div>
                                    <div class="col-3">ใบสลิปที่ : {{ $show_detail[0]->product_slip_number }}</div>
                                    <div class="col-3">Date : {{ $show_detail[0]->store_date ?? 'N/A' }}</div>
                                    <div class="col-3">Time : {{ $show_detail[0]->store_time ?? 'N/A' }}</div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>รายละเอียดสินค้า</th>
                            <th>จำนวนถุง</th>
                            <th>น้ำหนัก(KG.)</th>
                            <th>หมายเหตุ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($show_detail as $item)
                            <tr>
                                <td>{{ $item->product_id }}</td>
                                <td>{{ $item->department }}</td>
                                <td>{{ $item->amount }}</td>
                                <td>{{ $item->weight }}</td>
                                <td>{{ $item->comment }}</td>
                                <td>
                                    <a class="btn btn-primary" href="">แก้ไข</a>
                                    <a class="btn btn-danger" href="">ลบ</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>รายละเอียดสินค้า</th>
                            <th>จำนวนถุง</th>
                            <th>น้ำหนัก(KG.)</th>
                            <th>หมายเหตุ</th>
                            <th>จัดการ</th>
                        </tr>
                        <tr>
                            <th colspan="6">
                                <div class="row">
                                    <div class="col-6"></div>
                                    <div class="col-3">
                                        Production Checker : {{ $show_detail[0]->product_checker ?? 'N/A' }}</div>
                                    <div class="col-3">
                                        Domestic Checker : {{ $show_detail[0]->domestic_checker ?? 'N/A' }}</div>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

@endsection
