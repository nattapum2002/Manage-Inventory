@extends('layouts.master')

@section('title')
    จัดการรายการสินค้าในคลัง
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <article class="card-header">
                    <a class="btn btn-primary" href="{{ route('NewItem') }}">เพิ่มสินค้าใหม่</a>
                </article>
                <article class="card-body">
                    <table id="stock-all-table" class="table table-striped">
                        <thead>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>จำนวนทั้งหมด</th>
                            <th>หน่วย</th>
                            <th>จำนวนทั้งหมด</th>
                            <th>หน่วย</th>
                            <th>ห้องเก็บ</th>
                            <th>ลักษณะงาน</th>
                            <th>จัดการ</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->item_no }}</td>
                                    <td>{{ $item->item_desc1 }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->quantity_UM }}</td>
                                    <td>{{ $item->quantity2 }}</td>
                                    <td>{{ $item->quantity_UM2 }}</td>
                                    <td>{{ $item->whs_name ?? 'ยังไม่มี' }}</td>
                                    <td>{{ $item->item_work_desc ?? 'ไม่มี' }}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('Edit name', $item->item_no) }}">แก้ไข</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </article>
            </div>
        </div>
    </section>
@endsection
