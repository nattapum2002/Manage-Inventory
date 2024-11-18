@extends('layouts.master')

@section('title')
    จัดการทีมพนักงาน
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
                                <a href="{{ route('AddTeam') }}" class="btn btn-success"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="Teamtable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสทีม</th>
                                        <th>ชื่อทีม</th>
                                        <th>วีนที่</th>
                                        <th>จำนวนพนักงาน</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teams as $team)
                                        <tr>
                                            <td>{{ $team->team_id }}</td>
                                            <td>{{ $team->team_name }}</td>
                                            <td>{{ $team->date }}</td>
                                            <td>{{ $usersCounts->where('team_id', $team->team_id)->count() }}
                                            </td>
                                            <td>{{ $team->status ? 'ใช้งาน' : 'ไม่ใช้งาน' }}</td>
                                            <td>
                                                <a href="{{ route('EditTeam', $team->team_id) }}" class="btn btn-primary"><i
                                                        class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('ManageTeam.Toggle', [$team->team_id, $team->status ? 0 : 1]) }}"
                                                    class="btn {{ $team->status ? 'btn-danger' : 'btn-success' }}">
                                                    <i class="fas {{ $team->status ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสทีม</th>
                                        <th>ชื่อทีม</th>
                                        <th>วีนที่</th>
                                        <th>จำนวนพนักงาน</th>
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
