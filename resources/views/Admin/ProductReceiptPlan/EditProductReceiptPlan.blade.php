@extends('layouts.master')

@section('title')
    แก้ไขแผนรับสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('SaveEditProductReceiptPlan') }}" method="POST">
                                @csrf
                                <article class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="product_receipt_plan_id">รหัสแผนรับสินค้า</label>
                                            <input type="text" class="form-control" id="product_receipt_plan_id"
                                                name="product_receipt_plan_id"
                                                value="{{ $ProductReceiptPlans->product_receipt_plan_id }}"
                                                placeholder="รหัสแผนรับสินค้า">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_id">กะพนักงาน</label>
                                            <select class="form-control" id="shift_id" name="shift_id">
                                                <option selected value="{{ $ProductReceiptPlans->shift_id }}">
                                                    {{ $ProductReceiptPlans->shift_name }}</option>
                                                @foreach ($shifts->where('shift_id', '!=', $ProductReceiptPlans->shift_id) as $shift)
                                                    <option value="{{ $shift->shift_id }}">{{ $shift->shift_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="date">วันที่</label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                value="{{ $ProductReceiptPlans->date }}" placeholder="วันที่">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <input type="text" class="form-control" id="note" name="note"
                                                value="{{ $ProductReceiptPlans->note }}" placeholder="หมายเหตุ">
                                        </div>
                                    </div>
                                </article>
                                <hr>
                                <table id="ProductReceiptPlanTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>รหัสสินค้า</th>
                                            <th>รายการสินค้า</th>
                                            <th>จำนวนสินค้าเดิม(กก.)</th>
                                            <th>เพิ่มจำนวนสินค้า(กก.)</th>
                                            <th>ลดจำนวนสินค้า(กก.)</th>
                                            <th>จำนวนสินค้าทั้งหมด(กก.)</th>
                                            <th>หมายเหตุ</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ProductReceiptPlansDetails as $Product)
                                            <tr>
                                                <td>
                                                    <span id="product_id_{{ $Product->product_id }}">
                                                        {{ $Product->product_id }}
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="edit_product_id_{{ $Product->product_id }}" name="product_id"
                                                        value="{{ $Product->product_id }}" style="display:none;" readonly>
                                                </td>
                                                <td>
                                                    <span id="product_name_{{ $Product->product_name }}">
                                                        {{ $Product->product_name }}
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="edit_product_name_{{ $Product->product_name }}"
                                                        name="product_name" value="{{ $Product->product_name }}"
                                                        style="display:none;">
                                                </td>
                                                <td>
                                                    <span id="product_quantity_{{ $Product->product_quantity }}">
                                                        {{ $Product->product_quantity }}
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="edit_product_quantity_{{ $Product->product_quantity }}"
                                                        name="product_quantity" value="{{ $Product->product_quantity }}"
                                                        style="display:none;">
                                                </td>
                                                <td>
                                                    <span id="increase_quantity_{{ $Product->increase_quantity }}">
                                                        {{ $Product->increase_quantity }}
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="edit_increase_quantity_{{ $Product->increase_quantity }}"
                                                        name="increase_quantity" value="{{ $Product->increase_quantity }}"
                                                        style="display:none;">
                                                </td>
                                                <td>
                                                    <span id="reduce_quantity_{{ $Product->reduce_quantity }}">
                                                        {{ $Product->reduce_quantity }}
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="edit_reduce_quantity_{{ $Product->reduce_quantity }}"
                                                        name="reduce_quantity" value="{{ $Product->reduce_quantity }}"
                                                        style="display:none;">
                                                </td>
                                                <td>
                                                    <span id="total_quantity_{{ $Product->total_quantity }}">
                                                        {{ $Product->total_quantity }}
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="edit_total_quantity_{{ $Product->total_quantity }}"
                                                        name="total_quantity" value="{{ $Product->total_quantity }}"
                                                        style="display:none;">
                                                </td>
                                                <td>
                                                    <span id="note_{{ $Product->note }}">
                                                        {{ $Product->note ?? 'N/A' }}
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="edit_note_{{ $Product->note }}" name="note"
                                                        value="{{ $Product->note ?? 'N/A' }}" style="display:none;">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary edit_product"
                                                        data-product-id="{{ $Product->product_id }}">แก้ไข</button>
                                                    <button type="button" class="btn btn-danger"
                                                        id="cancel_edit_product_{{ $Product->product_id }}"
                                                        style="display:none;">ยกเลิก</button>
                                                    {{-- <a href="#" class="btn btn-danger btn-sm"
                                                        onclick="DeleteProductReceiptPlanDetail({{ $Product->product_receipt_plan_detail_id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </a> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>รหัสสินค้า</th>
                                            <th>รายการสินค้า</th>
                                            <th>จำนวนสินค้าเดิม(กก.)</th>
                                            <th>เพิ่มจำนวนสินค้า(กก.)</th>
                                            <th>ลดจำนวนสินค้า(กก.)</th>
                                            <th>จำนวนสินค้าทั้งหมด(กก.)</th>
                                            <th>หมายเหตุ</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="d-flex justify-content-center mt-3">

                                    <button type="submit" class="btn btn-success">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
