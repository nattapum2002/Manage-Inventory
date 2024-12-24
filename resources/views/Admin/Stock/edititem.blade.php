@extends('layouts.master')

@section('title')
    แก้ไขชื่อสินค้า
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @foreach ($data as $item)
                        <form action="{{ route('Updatename') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="product_id">รหัสสินค้า</label>
                                <input type="text" class="form-control" id="product_id" name="product_id"
                                    value="{{ $item->item_no }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="room_id">ห้องเก็บ</label>
                                <select name="room" class="form-control" id="">
                                    @foreach ($Warehouse as $item2)
                                        <option value="{{ $item2->id }}"
                                            {{ $item->warehouse == $item2->whs_name ? 'selected' : '' }}>
                                            {{ $item2->whs_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product_name">ชื่อสินค้า</label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    value="{{ $item->item_desc1 }}">
                            </div>
                            <div class="form-group">
                                <label for="product_work_desc">ลักษณะงาน</label>
                                <select class="form-control" name="product_work_desc" id="product_work_desc">
                                    <option value="1">แยกจ่าย</option>
                                    <option value="2">รับจัด</option>
                                    {{-- <option value="ไหลจ่าย">ไหลจ่าย</option> --}}
                                    <option value="3">เลือด</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product_name">หน่วย1 (item_um)</label>
                                <input type="text" class="form-control" id="item_um" name="item_um"
                                    value="{{ $item->item_um }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="product_name">หน่วย2 (item_um2)</label>
                                <input type="text" class="form-control" id="item_um2" name="item_um2"
                                    value="{{ $item->item_um2 }}" readonly>
                            </div>
                            <button class="btn btn-primary" type="submit">บันทึก</button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
