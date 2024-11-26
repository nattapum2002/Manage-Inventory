@extends('layouts.master')

@section('title')
    รายการสินค้าออก: {{ $date }}
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <article class="card-body">
                    <table id="stat-table" class="table">
                        <thead>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th class="text-center">ทั้งหมด(คงเหลือ)</th>
                            <th class="text-center">ออก(จำนวน)</th>
                            <th>หน่วย</th>
                            <th class="text-center">ทั้งหมด(คงเหลือ)</th>
                            <th class="text-center">ออก(จำนวน)</th>
                            <th>หน่วย</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->item_no }}</td> <!-- แสดง product_id -->
                                    <td>{{ $item->item_desc1 }}</td> <!-- เข้าถึง 'product_name' -->
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center text-danger">- {{ $item->total_quantity }}</td>
                                    <td>{{ $item->item_um }}</td>
                                    <td class="text-center">{{ $item->quantity2 }}</td>
                                    <td class="text-center text-danger ">- {{ $item->total_quantity2 }}</td>
                                    <td>{{ $item->item_um }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </article>
            </div>
        </div>
    </section>
@endsection
