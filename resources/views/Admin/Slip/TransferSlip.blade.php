@extends('layouts.master')

@section('title')
    ใบรับสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="{{ route('AddTransferSlip') }}" method="POST">
                            @csrf
                            <div class="card-header">
                                <h5>เพิ่มใบส่งสินค้า</h5>
                            </div>
                            <div class="card-body">
                                <article>
                                    <div class="row">
                                        <div class="col-lg col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="slip_id" class="form-label">กรอกหมายเลขสลิป</label>
                                                <input type="number" class="form-control" id="slip_id" name="slip_id">
                                            </div>
                                        </div>
                                        <div class="col-lg col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="slip_number" class="form-label">สลิปใบที่</label>
                                                <input type="number" class="form-control" id="slip_number"
                                                    name="slip_number">
                                            </div>
                                        </div>
                                        <div class="col-lg col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="department" class="form-label">หน่วยงาน</label>
                                                <input type="text" class="form-control" id="department"
                                                    name="department">
                                            </div>
                                        </div>
                                        <div class="col-lg col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="date" class="form-label">วันที่</label>
                                                <input type="date" class="form-control" id="date" name="date"
                                                    value="{{ now()->format('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="time" class="form-label">เวลา</label>
                                                <input type="time" class="form-control" id="time" name="time"
                                                    value="{{ now()->format('H:i') }}">
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">เพิ่ม</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
