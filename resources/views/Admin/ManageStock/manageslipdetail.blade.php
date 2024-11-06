@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง : รายละเอียดใบสินค้า {{$slip_id}} ใบสลิปที่ {{$show_detail[0]->product_slip_number}}
@endsection

@section('content')
<section class="content"> 
    <div class="card">
        {{-- <div class="card-header d-flex justify-content-start">
            <a class="btn btn-primary" href="{{route('Add item')}}">เพิ่มข้อมูลสินค้า</a>
        </div> --}}
        <div class="card-body">
            <table id="item_per_slip" class="table table-striped">
                <thead>
                    <tr>
                        <th>รหัสสินค้า</th>
                        <th>รายละเอียดสินค้า</th>
                        <th>จำนวนถุง</th>
                        <th>น้ำหนัก(KG.)</th>
                        <th>หมายเหตุ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $show_detail as $item )
                    <tr>
                        <td>{{$item->product_id}}</td>
                        <td>ไก่ชายโสด</td>
                        <td>{{$item->amount}}</td>
                        <td>{{$item->weight}}</td>
                        <td>{{$item->comment}}</td>
                        <td>
                            <a class="btn btn-primary" href="">แก้ไข</a>
                            <a class="btn btn-danger" href="">ลบ</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                Production Checker :<strong> {{$show_detail[0]->product_checker ?? 'N/A'}} </strong>
                Domestic Checker :<strong> {{$show_detail[0]->domestic_checker ?? 'N/A'}} </strong><br>
                <strong>Date : {{$show_detail[0]->store_date ?? 'N/A'}} </strong>
                <strong>Time : {{$show_detail[0]->store_time ?? 'N/A'}} </strong>
            </div>
        </div>
    </div>
</section>
    
<script>
    new DataTable('#item_per_slip', {
        order: [[3, 'desc']]
    });
</script>
@endsection