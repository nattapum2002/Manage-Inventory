@extends('layouts.master')

@section('title')
    รายการสินค้าเข้า : {{ $date }}
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <article class="card-body">
                    <table id="stat-table" class="table table-striped">
                        <thead>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>เข้า(จำนวน)</th>
                            <th class="text-center">ทั้งหมด(คงเหลือ)</th>
                            <th>หน่วย</th>
                            <th>เข้า(จำนวน)</th>
                            <th class="text-center">ทั้งหมด(คงเหลือ)</th>
                            <th>หน่วย</th>
                        </thead>
                        <tbody>
                            @foreach ($summary as $productId => $item)
                                <tr>
                                    <td>{{ $item['item_no'] }}</td> <!-- แสดง product_id -->
                                    <td>{{ $item['product_name'] }}</td> <!-- เข้าถึง 'product_name' -->
                                    <td class="text-success">+ {{ $item['total_quantity'] }}</td>
                                    <!-- เข้าถึง 'total_amount' -->
                                    <td class="text-center">{{ $item['all_quantity'] }}</td>
                                    <td>{{ $item['item_um'] }}</td>

                                    <td class="text-success">+ {{ $item['total_quantity2'] }}</td>
                                    <td class="text-center">{{ $item['all_quantity2'] }}</td>
                                    <td>{{ $item['item_um2'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </article>
            </div>
        </div>
    </section>
@endsection
