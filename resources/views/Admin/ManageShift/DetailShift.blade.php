@extends('layouts.master')

@section('title')
    รายละเอียดกะพนักงาน
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="Shifttable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="5">
                                            <div class="row">
                                                <div class="col-3">รหัสกะ : {{ $shifts[0]->shift_id }}</div>
                                                <div class="col-3">ชื่อกะ : {{ $shifts[0]->shift_name }}</div>
                                                <div class="col-3">เวลาเริ่ม : {{ $shifts[0]->start_shift }}</div>
                                                <div class="col-3">เวลาสิ้นสุด : {{ $shifts[0]->end_shift }}</div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>รหัสพนักงาน</th>
                                        <th>ชื่อ</th>
                                        <th>นามสกุล</th>
                                        <th>ตําแหน่ง</th>
                                        <th>ประเภทผู้ใช้</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($shifts as $shift)
                                        <tr>
                                            <td>{{ $shift->user_id }}</td>
                                            <td>{{ $shift->name }}</td>
                                            <td>{{ $shift->surname }}</td>
                                            <td>{{ $shift->position }}</td>
                                            <td>{{ $shift->user_type }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสพนักงาน</th>
                                        <th>ชื่อ</th>
                                        <th>นามสกุล</th>
                                        <th>ตําแหน่ง</th>
                                        <th>ประเภทผู้ใช้</th>
                                    </tr>
                                    <th colspan="5">
                                        <div class="row">
                                            <div class="col-9"></div>
                                            <div class="col-3">จำนวนพนักงาน : {{ $shifts->count() }}</div>
                                        </div>
                                    </th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
