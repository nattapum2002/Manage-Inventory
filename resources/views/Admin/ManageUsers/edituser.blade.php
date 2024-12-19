@extends('layouts.master')

@section('title')
    แก้ไขข้อมูลผู้ใช้
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title"></h3>
                                <a href="{{ route('ManageUsers') }}" class="btn btn-primary">ย้อนกลับ</a>
                            </div>
                        </div>
                        <form action="{{ route('Edituser.update', $User->user_id) }}" method="POST">
                            <div class="card-body">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="user_id" name="user_id"
                                                value="{{ $User->user_id }}" readonly>
                                            <label for="user_id">รหัสพนักงาน</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $User->name }}" required>
                                            <label for="name">ชื่อ</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="surname" name="surname"
                                                value="{{ $User->surname }}" required>
                                            <label for="surname">นามสกุล</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="position" name="position"
                                                value="{{ $User->position }}" required>
                                            <label for="position">ตำแหน่ง</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                value="{{ (new DateTime($User->start_date))->format('Y-m-d') }}" required>
                                            <label for="start_date">วันเริ่มงาน</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                                        <div class="form-floating">
                                            <select name="user_type" class="form-control" id="user_type"
                                                value="{{ $User->user_type }}" required>
                                                <option value="Admin" {{ $User->user_type == 'Admin' ? 'selected' : '' }}>
                                                    Admin</option>
                                                <option value="User" {{ $User->user_type == 'User' ? 'selected' : '' }}>
                                                    User</option>
                                                <option value="Manager"
                                                    {{ $User->user_type == 'Manager' ? 'selected' : '' }}>Manager</option>
                                            </select>
                                            <label for="user_type">ประเภทผู้ใช้</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                            <label for="password">รหัสผ่าน</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="password-confirm"
                                                name="password_confirmation" required>
                                            <label for="password-confirm">ยืนยันรหัสผ่าน</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-sm-12 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="note" name="note"
                                                value="{{ $User->note }}">
                                            <label for="note">หมายเหตุ</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-floating">
                                            <select name="status" class="form-control" id="status" required>
                                                <option value="0" {{ $User->status == '0' ? 'selected' : '' }}>
                                                    ไม่ใช้งาน</option>
                                                <option value="1" {{ $User->status == '1' ? 'selected' : '' }}>ใช้งาน
                                                </option>
                                            </select>
                                            <label for="status">สถานะ</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('Dashboard.' . $User->user_type) }}"
                                        class="btn btn-danger">ยกเลิก</a>
                                    <button type="submit" class="btn btn-success">บันทึก</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
