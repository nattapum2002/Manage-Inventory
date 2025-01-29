@extends('layouts.master')

@section('title')
    รายการสินค้าในคลัง
@endsection
@section('content')
    <main>
        <section class="card">
            <article class="card-body">
                <table id="product_stock-all-table" class="table table-striped">
                    <thead>
                        <th>รหัสสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th>จำนวนทั้งหมด</th>
                        <th>น้ำหนักทั้งหมด</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->product_id }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->amount }}</td>
                                <td>{{ $item->weight }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        </section>
    </main>
@endsection
