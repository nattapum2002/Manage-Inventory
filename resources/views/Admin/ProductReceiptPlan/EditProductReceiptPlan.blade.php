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
                                                <td>{{ $Product->product_id }}</td>
                                                <td>{{ $Product->product_name }}</td>
                                                <td>{{ $Product->product_quantity }}</td>
                                                <td>{{ $Product->increase_quantity }}</td>
                                                <td>{{ $Product->reduce_quantity }}</td>
                                                <td>{{ $Product->total_quantity }}</td>
                                                <td>{{ $Product->note ?? 'N/A' }}</td>
                                                <td>
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
