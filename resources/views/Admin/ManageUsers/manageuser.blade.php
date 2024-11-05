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
                                            <td>{{ $user->status }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>name</th>
                                        <th>surname</th>
                                        <th>position</th>
                                        <th>user_type</th>
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
