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
                        <form action="{{ route('CreateUser') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="user_id">รหัสพนักงาน</label>
                                            <input type="text" class="form-control" id="user_id" name="user_id"
                                                placeholder="รหัสพนักงาน">
                                            @error('user_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="name">ชื่อ</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="ชื่อ">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="surname">นามสกุล</label>
                                            <input type="text" class="form-control" id="surname" name="surname"
                                                placeholder="นามสกุล">
                                            @error('surname')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="department">แผนก</label>
                                            <input type="text" class="form-control" id="department" name="department"
                                                placeholder="แผนก">
                                            @error('department')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="user_type">ประเภทผู้ใช้</label>
                                            <select name="user_type" class="form-control" id="user_type">
                                                <option selected>ประเภทผู้ใช้</option>
                                                <option value="Admin">ผู้ดูแลระบบ</option>
                                                <option value="Employee">พนักงาน</option>
                                                <option value="Manager">ผู้จัดการ</option>
                                            </select>
                                            @error('user_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="password">รหัสผ่าน</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="รหัสผ่าน">
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="password-confirm">ยืนยันรหัสผ่าน</label>
                                            <input type="password" class="form-control" id="password-confirm"
                                                name="password_confirmation" placeholder="ยืนยันรหัสผ่าน">
                                            @error('password_confirmation')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <textarea type="text" class="form-control" id="note" name="note" rows="1" placeholder="หมายเหตุ"></textarea>
                                            @error('note')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label for="status">สถานะ</label>
                                            <select name="status" class="form-control" id="status">
                                                <option value="0">ระงับ</option>
                                                <option selected value="1">ใช้งาน</option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
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
                                <a href="{{ route('syncUsers') }}" class="btn btn-primary" id="syncUsers">
                                    <span id="syncUsersText">syncUsers</span>
                                    <div class="spinner-border spinner-border-sm text-light" id="loading"
                                        style="display: none;" role="status">
                                    </div>
                                </a>
                            </div>
                            <small class="form-text text-muted text-right">**การ Sync ข้อมูลอาจใช้เวลามากกว่า 5
                                นาที</small>
                        </div>
                        <div class="card-body">
                            <table id="userstable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสพนักงาน</th>
                                        <th>ชื่อ</th>
                                        <th>นามสกุล</th>
                                        <th>แผนก</th>
                                        <th>ประเภทผู้ใช้</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Users as $user)
                                        <tr>
                                            <td>{{ $user->user_id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->surname }}</td>
                                            <td>{{ $user->department }}</td>
                                            <td>
                                                @if ($user->user_type == 'Admin')
                                                    ผู้ดูแลระบบ
                                                @elseif ($user->user_type == 'Employee')
                                                    พนักงาน
                                                @elseif ($user->user_type == 'Manager')
                                                    ผู้จัดการ
                                                @endif
                                            </td>
                                            <td>{{ $user->status ? 'ใช้งาน' : 'ไม่ใช้งาน' }}</td>
                                            <td>
                                                <a href="{{ route('Edituser', $user->user_id) }}"
                                                    class="btn btn-primary">
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
                                        <th>รหัสพนักงาน</th>
                                        <th>ชื่อ</th>
                                        <th>นามสกุล</th>
                                        <th>แผนก</th>
                                        <th>ประเภทผู้ใช้</th>
                                        <th>สถานะ</th>
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

@section('script')
    <script>
        document.getElementById('syncUsers').addEventListener('click', function() {
            document.getElementById('loading').style.display = 'inline-block';
            document.getElementById('syncUsersText').style.display = 'none';
            document.getElementById('syncUsers').disabled = true;
        });

        window.onload = function() {
            @if (session('success'))
                document.getElementById('loading').style.display = 'none';
                document.getElementById('syncUsersText').style.display = 'inline-block';
                document.getElementById('syncUsers').disabled = false;
            @elseif ($errors->any())
                document.getElementById('loading').style.display = 'none';
                document.getElementById('syncUsersText').style.display = 'inline-block';
                document.getElementById('syncUsers').disabled = false;
            @endif
        }
    </script>
@endsection
