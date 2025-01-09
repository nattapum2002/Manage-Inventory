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
                @foreach ($data as $item )
                <form action="{{route('updateConfirmOrder',[$confirmOrder_id])}}">
                    <div class="form-group">
                        <label for="product_id">รหัสสินค้า</label>
                        <input type="text" class="form-control" id="product_id" name="product_id" value="{{$item->item_no}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">ชื่อสินค้า</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="{{$item->item_desc1}}" readonly>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <label for="product_qty">จํานวนจ่ายสินค้า</label>
                            <input type="text" class="form-control" id="product_qty" name="product_qty" value="{{$item->quantity}}">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <label for="product_unit">หน่วยสินค้า</label>
                            <input type="text" class="form-control" id="product_unit" name="product_unit" value="Kg" disabled>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <label for="">หมายเหตุ</label>
                            <input type="text" class="form-control" id="" name="pallet_detail_note" value="">
                        </div>                    
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </form>
                @endforeach
                
            </article>
        </section>
    </main>
@endsection