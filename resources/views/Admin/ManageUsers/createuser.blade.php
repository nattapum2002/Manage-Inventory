@extends('layouts.master')

@section('title')
    สร้างผู้ใช้
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title"></h3>
                                <a href="{{ route('ManageUsers') }}" class="btn btn-primary">ย้อนกลับ</a>
                            </div>
                        </div>
                        <form action="{{ route('ManageUsers.Createuser') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="user_id">รหัสพนักงาน</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id"
                                        placeholder="รหัสพนักงาน" required>
                                </div>
                                <div class="form-group">
                                    <label for="name">ชื่อ</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="ชื่อ" required>
                                </div>
                                <div class="form-group">
                                    <label for="surname">นามสกุล</label>
                                    <input type="text" class="form-control" id="surname" name="surname"
                                        placeholder="นามสกุล" required>
                                </div>
                                <div class="form-group">
                                    <label for="position">ตำแหน่ง</label>
                                    <input type="text" class="form-control" id="position" name="position"
                                        placeholder="ตำแหน่ง" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">สถานะ</label>
                                    <select name="status" class="form-control" id="status" required>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="user_type">ประเภทผู้ใช้</label>
                                    <select name="user_type" class="form-control" id="user_type" required>
                                        <option value="Admin">Admin</option>
                                        <option value="User">User</option>
                                        <option value="Manager">Manager</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="email">อีเมล</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="อีเมล" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">รหัสผ่าน</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="รหัสผ่าน" required>
                                </div>
                                <div class="form-group">
                                    <label for="password-confirm">ยืนยันรหัสผ่าน</label>
                                    <input type="password" class="form-control" id="password-confirm"
                                        name="password_confirmation" placeholder="ยืนยันรหัสผ่าน" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endSection
