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
                            <th colspan="7">
                                <div class="row">
                                    <div class="col-3">รหัสสลิป : {{ $slip_id }}</div>
                                    <div class="col-3">ใบสลิปที่ : {{ $show_detail[0]->product_slip_number }}</div>
                                    <div class="col-3">Date : {{ $show_detail[0]->store_date ?? 'N/A' }}</div>
                                    <time class="col-3">Time : {{ $show_detail[0]->store_time ?? 'N/A' }}</time>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>รายละเอียดสินค้า</th>
                            <th>แผนก</th>
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
                                <td>
                                    <span id="product_name_{{ $item->id }}">{{ $item->product_name }}</span>
                                </td>
                                <td>
                                    <span id="department_{{ $item->id }}">{{ $item->department }}</span>
                                    <input type="text" id="edit_department_{{ $item->id }}" value="{{ $item->department }}" style="display:none;">
                                </td>
                                <td>
                                    <span id="amount_{{ $item->id }}">{{ $item->amount }}</span>
                                    <input type="number" id="edit_amount_{{ $item->id }}" value="{{ $item->amount }}" style="display:none;">
                                </td>
                                <td>
                                    <span id="weight_{{ $item->id }}">{{ $item->weight }}</span>
                                    <input type="number" name="" id="edit_weight_{{ $item->id }}" value="{{ $item->weight }}" style="display:none;">
                                </td>
                                <td>
                                    <span id="comment_{{ $item->id }}">{{ $item->comment }}</span>
                                    <input type="text" name="" id="edit_comment_{{ $item->id }}" value="{{ $item->comment }}" style="display:none;">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary edit_slip" data-product-id="{{ $item->id }}">แก้ไข</button>
                                    <button type="button" class="btn btn-danger" id="cancel_edit_{{ $item->id }}" style="display:none;">ยกเลิก</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>รายละเอียดสินค้า</th>
                            <th>แผนก</th>
                            <th>จำนวนถุง</th>
                            <th>น้ำหนัก(KG.)</th>
                            <th>หมายเหตุ</th>
                            <th>จัดการ</th>
                        </tr>
                        <tr>
                            <th colspan="7">
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
