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
                                <h3 class="card-title"></h3>
                                <a href="{{ route('Createuser') }}" class="btn btn-success"><i
                                        class="fas fa-user-plus"></i></a>
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
