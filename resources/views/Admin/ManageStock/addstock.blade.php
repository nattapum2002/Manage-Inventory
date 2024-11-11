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
        <form class="add-stock" method="POST" action="{{ route('AddSlip') }}">
            @csrf
            <div class="card">
                <div class="card-header row">
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
                            <div class="col">
                                <label for="item_id_1" class="form-label">1 รหัสสินค้า</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="item_id_1" name="item_id[1]"
                                        readonly="">
                                </div>
                            </div>
                            <div class="col">
                                <label for="item_name_1" class="form-label">ชื่อสินค้า</label>
                                <div class="input-group">
                                    <input type="text" class="form-control ui-autocomplete-input" id="item_name_1"
                                        name="item_name[1]" autocomplete="off">
                                </div>
                            </div>
                            <div class="col">
                                <label for="item_amount_1" class="form-label">จำนวน</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="item_amount_1" name="item_amount[1]">
                                </div>
                            </div>
                            <div class="col">
                                <label for="item_weight_1" class="form-label">น้ำหนัก(KG.)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="item_weight_1" name="item_weight[1]">
                                </div>
                            </div>
                            <div class="col">
                                <label for="item_comment_1" class="form-label">หมายเหตุ</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="item_comment_1" name="item_comment[1]">
                                </div>
                            </div>
                            <div class="col">
                                <label for="remove-item" class="form-label">จัดการ</label>
                                {{-- <div class="input-group text-center">
                                    <button type="button" class="btn btn-danger remove-item">ลบ</button>
                                </div> --}}
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
