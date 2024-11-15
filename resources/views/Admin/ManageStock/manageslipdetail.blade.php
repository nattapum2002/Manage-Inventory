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
                            <th colspan="9">
                                <div class="row">
                                    <div class="col-2">รหัสสลิป : {{ $slip_id }}</div>
                                    <div class="col-2">ใบสลิปที่ : {{ $show_slip->product_slip_number }}</div>
                                    <div class="col-2">Date : {{ $show_detail[0]->store_date ?? 'N/A' }}</div>
                                    <time class="col-2">Time : {{ $show_detail[0]->store_time ?? 'N/A' }}</time>
                                    <div class="col-2">{!! $show_slip->status == 1 ? '<p class="text-success">ตรวจสอบแล้ว</p>' : '<p class="text-danger">รอตรวจสอบ</p>' !!}</div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>รายละเอียดสินค้า</th>
                            <th>แผนก</th>
                            <th>จำนวน</th>
                            <th>หน่วย</th>
                            <th>จำนวน</th>
                            <th>หน่วย</th>
                            <th>หมายเหตุ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($show_detail as $item)
                            <tr>
                                <td>{{ $item->item_no }}</td>
                                <td>
                                    <span id="product_name_{{ $item->id }}">{{ $item->item_desc1 }}</span>
                                </td>
                                <td>
                                    <span id="department_{{ $item->id }}">{{ $item->department }}</span>
                                    <input type="text" class="form-control" id="edit_department_{{ $item->id }}"
                                        value="{{ $item->department }}" style="display:none;">
                                </td>
                                <td>
                                    <span id="quantity_{{ $item->id }}">{{ $item->quantity }}</span>
                                    <input type="number" class="form-control" id="edit_quantity_{{ $item->id }}"
                                        value="{{ $item->quantity  }}" style="display:none;">
                                </td>
                                <td><span id="unit_{{ $item->id }}">{{ $item->item_um }}</span></td>
                                <td>
                                    <span id="quantity2_{{ $item->id }}">{{ $item->quantity2  }}</span>
                                    <input type="number" class="form-control" name=""
                                        id="edit_quantity2_{{ $item->id }}" value="{{ $item->quantity2  }}"
                                        style="display:none;">
                                </td>
                                <td><span id="unit_{{ $item->id }}">{{ $item->item_um2 }}</span></td>
                                <td>
                                    <span id="comment_{{ $item->id }}">{{ $item->note }}</span>
                                    <input type="text" class="form-control" name=""
                                        id="edit_comment_{{ $item->id }}" value="{{ $item->note }}"
                                        style="display:none;">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary edit_slip" data-product-id="{{ $item->id }}" data-product-code="{{$item->product_id}}">แก้ไข</button>
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
                            <th>จำนวน</th>
                            <th>หน่วย</th>
                            <th>จำนวน</th>
                            <th>หน่วย</th>
                            <th>หมายเหตุ</th>
                            <th>จัดการ</th>
                        </tr>
                        <tr>
                            <th colspan="9">
                                <div class="row">
                                    <div class="col-6"></div>
                                    <div class="col-2">
                                        Production Checker : {{ $show_detail[0]->product_checker ?? 'N/A' }}</div>
                                    <div class="col-2">
                                        Domestic Checker : {{ $show_detail[0]->domestic_checker ?? 'N/A' }}</div>
                                    <div class="col-2">
                                        <a class="btn btn-success" type="button" href="{{route('CheckSlip', $slip_id)}}">ยืนยัน</a>
                                    </div>
                                </div>
                               
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
@endsection
