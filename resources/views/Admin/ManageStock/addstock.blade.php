@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง : เพิ่มสินค้า
@endsection

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="">กรอกข้อมูลชุดการผลิต</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <label for="slip_id" class="form-label">กรอกหมายเลขสลิป</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="slip_id" name="slip_id">
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
                        <input type="text" class="form-control" id="date" name="date">
                    </div>
                </div>
                <div class="col">
                    <label for="time" class="form-label">เวลา</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="time" name="time">
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
   
@endsection
