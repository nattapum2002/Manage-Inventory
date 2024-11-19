@extends('layouts.master')

@section('title')
    เพิ่มกะพนักงาน
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('AddShift') }}" method="POST">
                                @csrf
                                <article class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_id">รหัสกะพนักงาน</label>
                                            <input type="text" class="form-control" id="shift_id" name="shift_id"
                                                placeholder="รหัสกะพนักงาน">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_name">ชื่อกะพนักงาน</label>
                                            <select class="form-control" id="shift_name" name="shift_name">
                                                <option selected value="">เลือกชื่อกะพนักงาน</option>
                                                @foreach ($filtered_shifts as $shift)
                                                    <option value="{{ $shift['select_name'] }}">{{ $shift['select_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="start_shift">เวลาเริ่มกะ</label>
                                            <input type="time" class="form-control" id="start_shift" name="start_shift"
                                                placeholder="เวลาเริ่มกะ">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="end_shift">เวลาเลิกกะ</label>
                                            <input type="time" class="form-control" id="end_shift" name="end_shift"
                                                placeholder="เวลาเลิกกะ">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <input type="text" class="form-control" id="note" name="note"
                                                placeholder="หมายเหตุ">
                                        </div>
                                    </div>
                                </article>
                                <hr>
                                <article>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="user_id[0]" class="form-label">รหัสพนักงาน</label>
                                                <input type="text" class="form-control" id="user_id0" name="user_id[0]"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="name[0]" class="form-label">ชื่อ</label>
                                                <input type="text" class="form-control" id="name0" name="name[0]">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="surname[0]" class="form-label">นามสกุล</label>
                                                <input type="text" class="form-control" id="surname0" name="surname[0]"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="position[0]" class="form-label">ตำแหน่ง</label>
                                                <input type="text" class="form-control" id="position0" name="position[0]"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                <article id="add-user-shift">

                                </article>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-primary mr-3" id="add-user">เพิ่มพนักงาน</button>
                                    <button type="submit" class="btn btn-success">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
