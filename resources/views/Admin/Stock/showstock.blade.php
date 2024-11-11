@extends('layouts.master')

@section('title')
    จัดการรายการสินค้าในคลัง
@endsection
@section('content')

<main>
    <section class="card">
        <article class="card-header">
            <a class="btn btn-primary" href="{{route('NewItem')}}">เพิ่มสินค้าใหม่</a>
        </article>
        <article class="card-body">
            <table id="stock-all-table" class="table table-striped">
                <thead>
                    <th>รหัสสินค้า</th>
                    <th>ชื่อสินค้า</th>
                    <th>จำนวนทั้งหมด</th>
                    <th>น้ำหนักทั้งหมด</th>
                    <th>จัดการ</th>
                </thead>
                <tbody>
                    @foreach ($data as $item )
                    <tr>
                        <td>{{$item->product_id}}</td>
                        <td>{{$item->product_name}}</td>
                        <td>{{$item->amount}}</td>
                        <td>{{$item->weight}}</td>
                        <td>
                            <a class="btn btn-primary" href="{{route('Edit name',$item->product_id)}}">แก้ไข</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </article>
    </section>
</main>
@endsection