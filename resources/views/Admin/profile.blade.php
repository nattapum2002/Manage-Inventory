@extends('layouts.master')

@section('title')
    บัญชีผู้ใช้
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
                                <a href="{{ route('Dashboard.' . Auth::user()->user_type) }}"
                                    class="btn btn-primary">ย้อนกลับ</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('Edituser.update', Auth::user()->user_id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">ชื่อ</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ Auth::user()->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="surname">นามสกุล</label>
                                    <input type="text" class="form-control" id="surname" name="surname"
                                        value="{{ Auth::user()->surname }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="position">ตำแหน่ง</label>
                                    <input type="text" class="form-control" id="position" name="position"
                                        value="{{ Auth::user()->position }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="user_type">ประเภทผู้ใช้</label>
                                    <select name="user_type" class="form-control" id="user_type"
                                        value="{{ Auth::user()->user_type }}" required>
                                        <option value="Admin">Admin</option>
                                        <option value="User">User</option>
                                        <option value="Manager">Manager</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="email">อีเมล</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ Auth::user()->email }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">สถานะ</label>
                                    <select name="status" class="form-control" id="status"
                                        value="{{ Auth::user()->status }}" required>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="password">รหัสผ่าน</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="password-confirm">ยืนยันรหัสผ่าน</label>
                                    <input type="password" class="form-control" id="password-confirm"
                                        name="password_confirmation" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
