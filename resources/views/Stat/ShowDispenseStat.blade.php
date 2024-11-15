@extends('layouts.master')

@section('title')
    รายการสินค้าเข้า : {{ $date }}
@endsection
@section('content')
    <main>
        <section>
            <article class="card-body">
                <table id="stat-table" class="table">
                    <thead>
                        <th>รหัสสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th class="text-center">ทั้งหมด(คงเหลือ)</th>
                        <th class="text-center">ออก(จำนวน)</th>   
                    </thead>
                    <tbody>
                        {{-- @foreach ($summary as $productId => $item)
                        <tr>
                            <td>{{ $productId }}</td> <!-- แสดง product_id -->
                            <td>{{ $item['product_name'] }}</td> <!-- เข้าถึง 'product_name' -->
                            <td class="text-success">+ {{ $item['total_amount'] }}</td> <!-- เข้าถึง 'total_amount' -->
                            <td class="text-center">{{ $item['all_amount'] }}</td>
                        </tr>
                        @endforeach --}}
                        <tr>
                            <td>000001</td>
                            <td>ตับไก่ A</td>
                            <td class="text-center">100</td>
                            <td class="text-danger text-center">- 20</td>
                        </tr>
                    </tbody>
                </table>
            </article>
        </section>
    </main>
@endsection