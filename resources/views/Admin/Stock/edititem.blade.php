@extends('layouts.master')

@section('title')
    แก้ไขชื่อสินค้า
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('SaveEditProduct') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="product_id">รหัสสินค้า</label>
                                    <input type="text" class="form-control" id="product_id" name="product_id"
                                        value="{{ $data->product_number }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="room_id">ห้องเก็บ</label>
                                    <select name="room" class="form-control" id="">
                                        @foreach ($warehouse as $room)
                                            <option {{ $data->warehouse_id == $room->id ? 'selected' : '' }}
                                                value="{{ $room->id }}">
                                                {{ $room->warehouse_name == 'Blood' ? 'เลือด' : $room->warehouse_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="product_name">ชื่อสินค้า</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name"
                                        value="{{ $data->product_description }}">
                                </div>
                            </div>
                            <div class="col-lg col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="product_name">หน่วย1 (UM1)</label>
                                    <input type="text" class="form-control" id="product_um" name="product_um"
                                        value="{{ $data->product_um }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="product_name">หน่วย2 (UM2)</label>
                                    <input type="text" class="form-control" id="product_um2" name="product_um2"
                                        value="{{ $data->product_um2 }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="product_work_desc">ลักษณะงาน</label>
                                    <select class="form-control" name="product_work_desc" id="product_work_desc">
                                        <option selected>เลือกลักษณะงาน</option>
                                        <option value="แยกจ่าย">แยกจ่าย</option>
                                        <option value="รับจัด">รับจัด</option>
                                        <option value="เลือด">เลือด</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
