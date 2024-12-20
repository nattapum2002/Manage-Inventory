@extends('layouts.master')

@section('title')
    จัดการผู้ใช้
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5>เพิ่มผู้ใช้</h5>
                            </div>
                        </div>
                        <form action="{{ route('ManageUsers.Createuser') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="user_id">รหัสพนักงาน</label>
                                            <input type="text" class="form-control" id="user_id" name="user_id"
                                                placeholder="รหัสพนักงาน" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="name">ชื่อ</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="ชื่อ" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="surname">นามสกุล</label>
                                            <input type="text" class="form-control" id="surname" name="surname"
                                                placeholder="นามสกุล" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="position">ตำแหน่ง</label>
                                            <input type="text" class="form-control" id="position" name="position"
                                                placeholder="ตำแหน่ง" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="start_date">วันเริ่มงาน</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                placeholder="วันเริ่มงาน" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="user_type">ประเภทผู้ใช้</label>
                                            <select name="user_type" class="form-control" id="user_type" required>
                                                <option selected>ประเภทผู้ใช้</option>
                                                <option value="Admin">Admin</option>
                                                <option value="User">User</option>
                                                <option value="Manager">Manager</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="password">รหัสผ่าน</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="รหัสผ่าน" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="password-confirm">ยืนยันรหัสผ่าน</label>
                                            <input type="password" class="form-control" id="password-confirm"
                                                name="password_confirmation" placeholder="ยืนยันรหัสผ่าน" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <textarea type="text" class="form-control" id="note" name="note" placeholder="หมายเหตุ"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="status">สถานะ</label>
                                            <select name="status" class="form-control" id="status" required>
                                                <option value="0">ระงับ</option>
                                                <option selected value="1">ใช้งาน</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success float-right">เพิ่มผู้ใช้</button>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5>จัดการผู้ใช้</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="userstable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>user id</th>
                                        <th>name</th>
                                        <th>surname</th>
                                        <th>position</th>
                                        <th>user type</th>
                                        <th>status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Users as $user)
                                        <tr>
                                            <td>{{ $user->user_id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->surname }}</td>
                                            <td>{{ $user->position }}</td>
                                            <td>{{ $user->user_type }}</td>
                                            <td>{{ $user->status ? 'ใช้งาน' : 'ไม่ใช้งาน' }}</td>
                                            <td>
                                                <a href="{{ route('Edituser', $user->user_id) }}" class="btn btn-primary">
                                                    <i class="fas fa-edit"></i></a>
                                                <a href="{{ route('ManageUsers.Toggle', [$user->user_id, $user->status ? 0 : 1]) }}"
                                                    class="btn {{ $user->status ? 'btn-danger' : 'btn-success' }}"><i
                                                        class="fas {{ $user->status ? 'fa-eye-slash' : 'fa-eye' }}"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>user id</th>
                                        <th>name</th>
                                        <th>surname</th>
                                        <th>position</th>
                                        <th>user type</th>
                                        <th>status</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
