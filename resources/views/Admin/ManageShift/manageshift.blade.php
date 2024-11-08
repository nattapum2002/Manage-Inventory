@extends('layouts.master')

@section('title')
    จัดการกะพนักงาน
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('AddShift') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                        </div>
                        <div class="card-body">
                            <table id="Shifttable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสกะ</th>
                                        <th>ชื่อกะ</th>
                                        <th>เวลาเริ่มกะ</th>
                                        <th>เวลาสิ้นสุดกะ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($shifts as $shift)
                                        <tr>
                                            <td>{{ $shift->shift_id }}</td>
                                            <td>{{ $shift->shift_name }}</td>
                                            <td>{{ $shift->start_shift }}</td>
                                            <td>{{ $shift->end_shift }}</td>
                                            <td>{{ $shift->status ? 'ใช้งาน' : 'ไม่ใช้งาน' }}</td>
                                            <td>
                                                <a href="{{ route('DetailShift', $shift->shift_id) }}"
                                                    class="btn btn-primary"><i class="far fa-file-alt"></i>
                                                </a>
                                                <a href="{{ route('ManageShift.Toggle', [$shift->shift_id, $shift->status ? 0 : 1]) }}"
                                                    class="btn {{ $shift->status ? 'btn-danger' : 'btn-success' }}">
                                                    <i class="fas {{ $shift->status ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th>รหัสกะ</th>
                                    <th>ชื่อกะ</th>
                                    <th>เวลาเริ่มกะ</th>
                                    <th>เวลาสิ้นสุดกะ</th>
                                    <th>สถานะ</th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
