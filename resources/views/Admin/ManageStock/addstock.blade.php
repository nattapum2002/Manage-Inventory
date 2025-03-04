@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง : เพิ่มสินค้า
@endsection

@section('content')
    <section class="content">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="add-product_stock" method="POST" action="{{ route('AddSlip') }}">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="row">

                        <aside class="col">
                            <h3 class="mb-0">กรอกข้อมูลชุดการผลิต</h3>
                        </aside>
                        <div class="col">

                        </div>
                        <div class="col d-flex w-50">
                            <aside class="input-group me-3">
                                <!-- เริ่มต้นด้วย span -->
                                <input type="text" class="form-control" id="product_checker" name="product_checker"
                                    value="{{ Auth::user()->user_id }}" placeholder="กรอกรหัสพนักงาน">
                                <span class="input-group-text">Product Checker</span> <!-- ย้ายไปท้ายสุด -->
                            </aside>
                        </div>
                        {{-- <aside class="input-group">
                                <span class="input-group-text">Domestic Checker</span>
                                <input type="text" class="form-control" id="domestic_checker" name="domestic_checker" placeholder="กรอกรหัสพนักงาน">
                            </aside> --}}
                    </div>
                </div>
                <div class="card-body">
                    <article class="row">
                        <div class="col">
                            <label for="slip_id" class="form-label">กรอกหมายเลขสลิป</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="slip_id" name="slip_id">
                            </div>
                        </div>
                        <div class="col">
                            <label for="slip_number" class="form-label">สลิปใบที่</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="slip_number" name="slip_number">
                            </div>
                        </div>
                        <div class="col">
                            <label for="department" class="form-label">หน่วยงาน</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="department" name="department">
                            </div>
                        </div>
                        <div class="col">
                            <label for="date" class="form-label">วันที่</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="date" name="date">
                            </div>
                        </div>
                        <div class="col">
                            <label for="time" class="form-label">เวลา</label>
                            <div class="input-group">
                                <input type="time" class="form-control" id="time" name="time">
                            </div>
                        </div>
                    </article>
                    <hr>

                    <article id="item-row">
                        <div class="row" id="item-1">
                            <div class="col-lg-2 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="room_id_1" class="form-label">ห้องเก็บ</label>
                                    <select name="room[1]" class="form-control" id="room_id_1">
                                        <option selected>เลือกห้องเก็บ</option>
                                        <option value="Cold-A">Cold-A</option>
                                        <option value="Cold-C">Cold-C</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="item_id_1" class="form-label">รหัสสินค้า</label>
                                    <input type="text" class="form-control" id="item_id_1" name="product_id[1]"
                                        readonly="">
                                    <input type="hidden" class="form-control" id="save_item_id_1" name="save_item_id[1]"
                                        readonly="">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="item_name_1" class="form-label">ชื่อสินค้า</label>
                                    <input type="text" class="form-control ui-autocomplete-input" id="item_name_1"
                                        name="item_name[1]" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="item_comment_1" class="form-label">หมายเหตุ</label>
                                    <input type="text" class="form-control" id="item_comment_1"
                                        name="item_comment[1]">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="item_amount_1" class="form-label">จำนวน</label>
                                    <input type="number" class="form-control" id="item_quantity_1"
                                        name="item_quantity[1]">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="item_um_1" class="form-label">หน่วย</label>
                                    <input type="text" class="form-control" id="item_um_1" name="product_um[1]"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="item_weight_1" class="form-label">จำนวน</label>
                                    <input type="number" class="form-control" id="item_quantity2_1"
                                        name="item_quantity2[1]">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="item_um2_1" class="form-label">หน่วย</label>
                                    <input type="text" class="form-control" id="item_um2_1" name="product_um2[1]"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </article>

                    <div class="d-flex justify-content-center mt-3">
                        <button type="button" class="btn btn-primary" id="add-item">เพิ่มช่องสินค้า</button>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </div>
        </form>
    </section>
@endsection
