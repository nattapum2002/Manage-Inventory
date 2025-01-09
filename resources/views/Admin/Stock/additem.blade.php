@extends('layouts.master')

@section('title')
    เพิ่มสินค้าใหม่
@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                    <form action="{{ route('Updatename') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="product_id">รหัสสินค้า</label>
                                    <input type="text" class="form-control" id="product_id" name="product_id">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="product_name">ชื่อสินค้า</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="room_id">ห้องเก็บ</label>
                                    <select name="room" class="form-control" id="">
                                        @foreach ($Warehouse as $item2)
                                            <option value="{{ $item2->id }}">
                                                {{ $item2->whs_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="product_work_desc">ลักษณะงาน</label>
                            <select class="form-control" name="product_work_desc" id="product_work_desc">
                                <option value="1">แยกจ่าย</option>
                                <option value="2">รับจัด</option>
                                <option value="3">เลือด</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product_name">หน่วย1 (item_um)</label>
                            <input type="text" class="form-control" id="item_um" name="item_um">
                        </div>
                        <div class="form-group">
                            <label for="product_name">หน่วย2 (item_um2)</label>
                            <input type="text" class="form-control" id="item_um2" name="item_um2">
                        </div>
                        <button class="btn btn-primary" type="submit">บันทึก</button>
                    </form>
            </div>
        </div>
    </div>
</section>
@endsection
