@extends('layouts.master')

@section('title')
    บัญชีผู้ใช้
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <img src="{{ asset('backend/dist/img/AdminLTELogo.png') }}" alt="User Image" style="width: 100%;">
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>รายละเอียด</h4>
                        </div>
                        <div class="card-body">
                            <label for="name">ชื่อ</label>
                            <input type="text" class="form-control">
                            <label for="surname">นามสกุล</label>
                            <input type="text" class="form-control">
                            <label for="surname">ตำแหน่ง</label>
                            <input type="text" class="form-control" disabled>
                            <label for="surname">ประเภทผู้ใช้</label>
                            <input type="text" class="form-control" disabled>
                            <label for="surname">สถานะ</label>
                            <input type="text" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
