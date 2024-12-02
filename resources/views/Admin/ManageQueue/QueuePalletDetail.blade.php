@extends('layouts.master')

@section('title')
    รายละเอียดคิวลูกค้า
@endsection

@section('content')
<main>
    <section class="card">
        <article class="card-body">
            <table id="customer-queue-pallet-detail" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>รหัสสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th>จำนวน</th>
                        <th>หน่วย</th>
                        <th>จำนวน (เพิ่มเติม)</th>
                        <th>หน่วย (เพิ่มเติม)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Pallets as $item)
                    <tr>
                        <td>{{$item->item_no}}</td>
                        <td>{{$item->item_desc1}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->item_um}}</td>
                        <td>{{$item->quantity2}}</td>
                        <td>{{$item->item_um2}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </article>
        <article class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="{{route('DetailCustomerQueue',$order_id)}}" class="btn btn-secondary">ย้อนกลับ</a>
                <a href="{{route('ConfirmReceive',[$order_id,$Pallets[0]->id])}}" onclick="return confirm('คุณแน่ใจที่จะยืนยันการส่งสินค้า');" class="btn btn-success {{$Pallets[0]->recive_status == 1 ? 'disabled' : ''}}">{{$Pallets[0]->recive_status == 1 ? 'ส่งแล้ว' : 'ยืนยันการส่ง'}}</a>
            </div>
        </article>
    </section>
</main>
@endsection