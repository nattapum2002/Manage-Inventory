@extends('layouts.master')

@section('title')
    จัดการล็อคพาเลท
@endsection

@section('content')
    <main>
        <section class="card">
            <article class="card-header">
                แก้ไขข้อมูล
            </article>
            <article class="card-body">
                @foreach ($data as $item)
                    <form action="">
                        <div class="form-group">
                            <label for="product_id">รหัสสินค้า</label>
                            <input type="text" class="form-control" id="product_id" name="product_id"
                                value="{{ $item->product_number }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">ชื่อสินค้า</label>
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                value="{{ $item->product_description }}" readonly>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">
                                <label for="product_qty">จํานวนจ่ายสินค้า</label>
                                <input type="text" class="form-control" id="product_qty" name="product_qty"
                                    value="{{ $item->quantity }}">
                            </div>
                            <div class="col-3">
                                <label for="product_unit">หน่วยสินค้า</label>
                                <input type="text" class="form-control" id="product_unit" name="product_unit"
                                    value="{{ $item->product_um }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">
                                <label for="product_qty">จํานวนจ่ายสินค้า</label>
                                <input type="text" class="form-control" id="product_qty" name="product_qty"
                                    value="{{ $item->quantity2 }}">
                            </div>
                            <div class="col-3">
                                <label for="product_unit">หน่วยสินค้า</label>
                                <input type="text" class="form-control" id="product_unit" name="product_unit"
                                    value="{{ $item->product_um2 }}" readonly>
                            </div>
                            <div class="col-3">
                                <label for="">หมายเหตุ</label>
                                <input type="text" class="form-control" id="" name="pallet_detail_note"
                                    value="{{ $item->product_order_note ?? 'ไม่มี' }}">
                            </div>
                            {{-- <div class="col">
                            <label for="">สถานะ</label>
                            <input type="text" class="form-control" id="" name="" value="{{$item->status == 0 ? 'ยกเลิก' : 'ปกติ'}}">
                        </div> --}}
                        </div>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </form>
                @endforeach

            </article>
        </section>
    </main>
@endsection
