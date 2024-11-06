@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง : เพิ่มสินค้า
@endsection

@section('content')
    <section class="content">
        <form class="add-stock" method="POST" action="{{ route('AddSlip') }}">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="">กรอกข้อมูลชุดการผลิต</h3>
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
