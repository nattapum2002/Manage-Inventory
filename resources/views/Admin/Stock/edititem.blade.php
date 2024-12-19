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
                            <div class="row">
                                <div class="col-lg col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="product_id">รหัสสินค้า</label>
                                        <input type="text" class="form-control" id="product_id" name="product_id"
                                            value="{{ $item->item_no }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="room_id">ห้องเก็บ</label>
                                        <select name="room" class="form-control" id="">
                                            @foreach ($Warehouse as $item2)
                                                <option value="{{ $item2->whs_name }}"
                                                    {{ $item->warehouse == $item2->whs_name ? 'selected' : '' }}>
                                                    {{ $item2->whs_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="product_name">ชื่อสินค้า</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name"
                                            value="{{ $item->item_desc1 }}">
                                    </div>
                                </div>
                                <div class="col-lg col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="product_name">หน่วย1 (item_um)</label>
                                        <input type="text" class="form-control" id="item_um" name="item_um"
                                            value="{{ $item->item_um }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="product_name">หน่วย2 (item_um2)</label>
                                        <input type="text" class="form-control" id="item_um2" name="item_um2"
                                            value="{{ $item->item_um2 }}" readonly>
                                    </div>
                                </div>
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
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
