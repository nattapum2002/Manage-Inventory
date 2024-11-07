@extends('layouts.master')

@section('title')
    รายละเอียดสลิปสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="product_storetable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="6">
                                            <div class="row">
                                                <div class="col-3">
                                                    รหัสสลิปสินค้า : {{ $product_stores[0]->product_slip_id ?? 'N/A' }}
                                                </div>
                                                <div class="col-3">
                                                    หมายเลขสลิป : {{ $product_stores[0]->product_slip_number ?? 'N/A' }}
                                                </div>
                                                <div class="col-3">
                                                    วันเก็บสินค้า : {{ $product_stores[0]->store_date ?? 'N/A' }}</div>
                                                <div class="col-3">
                                                    เวลาเก็บสินค้า : {{ $product_stores[0]->store_time ?? 'N/A' }}</div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>น้ำหนัก</th>
                                        <th>จํานวน</th>
                                        <th>สถานะเช็คสินค้า</th>
                                        <th>หมายเหตุ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product_stores as $product_store)
                                        <tr>
                                            <td>{{ $product_store->product_id }}</td>
                                            <td>{{ $product_store->department }}</td>
                                            <td>{{ $product_store->weight }}</td>
                                            <td>{{ $product_store->amount }}</td>
                                            <td>{{ $product_store->check_status }}</td>
                                            <td>{{ $product_store->comment }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>น้ำหนัก</th>
                                        <th>จํานวน</th>
                                        <th>สถานะเช็คสินค้า</th>
                                        <th>หมายเหตุ</th>
                                    </tr>
                                    <tr>
                                        <th colspan="6">
                                            <div class="row">
                                                <div class="col-6"></div>
                                                <div class="col-3">
                                                    product checker : {{ $product_stores[0]->product_checker ?? 'N/A' }}
                                                </div>
                                                <div class="col-3">
                                                    domestic checker : {{ $product_stores[0]->domestic_checker ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
