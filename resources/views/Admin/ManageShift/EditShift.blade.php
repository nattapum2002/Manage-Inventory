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
                                        {{-- <th>ประเภทผู้ใช้</th> --}}
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($shifts as $shift)
                                        <tr>
                                            <td>
                                                <span id="span_shift_user_id_{{ $shift->user_id }}">
                                                    {{ $shift->user_id }}
                                                </span>
                                                <input type="text" class="form-control"
                                                    id="edit_shift_user_id_{{ $shift->user_id }}" name="user_id"
                                                    value="{{ $shift->user_id }}" style="display:none;" readonly>
                                            </td>
                                            <td>
                                                <span id="span_shift_name_{{ $shift->user_id }}">
                                                    {{ $shift->name }}
                                                </span>
                                                <input type="text" class="form-control"
                                                    id="edit_shift_name_{{ $shift->user_id }}" name="name"
                                                    value="{{ $shift->name }}" style="display:none;">
                                            </td>
                                            <td>
                                                <span id="span_shift_surname_{{ $shift->user_id }}">
                                                    {{ $shift->surname }}
                                                </span>
                                                <input type="text" class="form-control"
                                                    id="edit_shift_surname_{{ $shift->user_id }}" name="surname"
                                                    value="{{ $shift->surname }}" style="display:none;" disabled>
                                            </td>
                                            <td>
                                                <span id="span_shift_position_{{ $shift->user_id }}">
                                                    {{ $shift->position }}
                                                </span>
                                                <input type="text" class="form-control"
                                                    id="edit_shift_position_{{ $shift->user_id }}" name="position"
                                                    value="{{ $shift->position }}" style="display:none;" disabled>
                                            </td>
                                            {{-- <td>{{ $shift->user_type }}</td> --}}
                                            <td>
                                                <button type="button" class="btn btn-primary edit_shift"
                                                    data-product-id="{{ $shift->user_id }}">แก้ไข</button>
                                                <button type="button" class="btn btn-danger"
                                                    id="cancel_edit_shift_{{ $shift->user_id }}"
                                                    style="display:none;">ยกเลิก</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสพนักงาน</th>
                                        <th>ชื่อ</th>
                                        <th>นามสกุล</th>
                                        <th>ตําแหน่ง</th>
                                        {{-- <th>ประเภทผู้ใช้</th> --}}
                                        <th></th>
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
