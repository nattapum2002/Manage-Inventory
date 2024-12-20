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
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="user_id">รหัสพนักงาน</label>
                                            <input type="text" class="form-control" id="user_id" name="user_id"
                                                value="{{ $User->user_id }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">ชื่อ</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $User->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label for="surname">นามสกุล</label>
                                            <input type="text" class="form-control" id="surname" name="surname"
                                                value="{{ $User->surname }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label for="position">ตำแหน่ง</label>
                                            <input type="text" class="form-control" id="position" name="position"
                                                value="{{ $User->position }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="start_date">วันเริ่มงาน</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                value="{{ (new DateTime($User->start_date))->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="user_type">ประเภทผู้ใช้</label>
                                            <select name="user_type" class="form-control" id="user_type"
                                                value="{{ $User->user_type }}" required>
                                                <option value="Admin" {{ $User->user_type == 'Admin' ? 'selected' : '' }}>
                                                    Admin</option>
                                                <option value="User" {{ $User->user_type == 'User' ? 'selected' : '' }}>
                                                    User</option>
                                                <option value="Manager"
                                                    {{ $User->user_type == 'Manager' ? 'selected' : '' }}>Manager</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="password">รหัสผ่าน</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="password-confirm">ยืนยันรหัสผ่าน</label>
                                            <input type="password" class="form-control" id="password-confirm"
                                                name="password_confirmation" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <input type="text" class="form-control" id="note" name="note"
                                                value="{{ $User->note }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="status">สถานะ</label>
                                            <select name="status" class="form-control" id="status" required>
                                                <option value="0" {{ $User->status == '0' ? 'selected' : '' }}>
                                                    ไม่ใช้งาน</option>
                                                <option value="1" {{ $User->status == '1' ? 'selected' : '' }}>ใช้งาน
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success float-right">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
