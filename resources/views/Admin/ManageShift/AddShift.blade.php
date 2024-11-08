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
                                            <select class="form-control" id="shift_name">
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                                <option value="F">F</option>
                                                <option value="G">G</option>
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
                                </article>
                                <hr>
                                <article id="add-user-shift">

                                </article>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-primary" id="add-user">เพิ่มพนักงาน</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
