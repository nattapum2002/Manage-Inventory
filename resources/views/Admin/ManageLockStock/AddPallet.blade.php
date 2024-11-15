@extends('layouts.master')

@section('title')
    เพิ่มพาเลท
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('SavePallet', ['order_id' => $order_id]) }}" method="POST">
                                @csrf
                                <article class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="pallet_id">รหัสพาเลท</label>
                                            <input type="text" class="form-control" id="pallet_id" name="pallet_id"
                                                placeholder="รหัสพาเลท">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="pallet_no">หมายเลขพาเลท</label>
                                            <input type="text" class="form-control" id="pallet_no" name="pallet_no"
                                                placeholder="หมายเลขพาเลท">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="room">ห้องเก็บ</label>
                                            <select class="form-control" name="room" id="room">
                                                <option selected>เลือกห้อง</option>
                                                <option value="Cold-A">Cold-A</option>
                                                <option value="Cold-C">Cold-C</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <input type="text" class="form-control" id="note" name="note"
                                                placeholder="หมายเหตุ">
                                        </div>
                                    </div>
                                </article>
                                <hr>
                                <article>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="product_id[0]" class="form-label">รหัสสินค้า</label>
                                                <input type="text" class="form-control" id="product_id0"
                                                    name="product_id[0]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="product_name[0]" class="form-label">รายการ</label>
                                                <input type="text" class="form-control" id="product_name0"
                                                    name="product_name[0]">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="amount_order[0]" class="form-label">สั่งจ่าย</label>
                                                <input type="text" class="form-control" id="amount_order0"
                                                    name="amount_order[0]" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="bag_color[0]" class="form-label">สีถุง</label>
                                                <input type="text" class="form-control" id="bag_color0"
                                                    name="bag_color[0]" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="note[0]" class="form-label">หมายเหตุ</label>
                                                <input type="text" class="form-control" id="note0" name="note[0]"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">

                                        </div>
                                    </div>
                                </article>
                                <article id="add-products">

                                </article>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-primary mr-3"
                                        id="add-product">เพิ่มสินค้า</button>
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
