@extends('layouts.master')

@section('title')
    การถ่ายโอนสินค้า
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
                                        <th>รหัสสลิปสินค้า</th>
                                        <th>หมายเลขสลิป</th>
                                        <th>วันเก็บสินค้า</th>
                                        <th>เวลาเก็บสินค้า</th>
                                        <th>product checker</th>
                                        <th>domestic checker</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product_stores as $product_store)
                                        <tr>
                                            <td>{{ $product_store->product_slip_id }}</td>
                                            <td>{{ $product_store->product_slip_number }}</td>
                                            <td>{{ $product_store->store_date }}</td>
                                            <td>{{ $product_store->store_time }}</td>
                                            <td>{{ $product_store->product_checker }}</td>
                                            <td>{{ $product_store->domestic_checker }}</td>
                                            <td>
                                                <a href="{{ route('DetailProductStore', $product_store->product_slip_id) }}"
                                                    class="btn btn-primary"><i class="far fa-file-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสลิปสินค้า</th>
                                        <th>หมายเลขสลิป</th>
                                        <th>วันเก็บสินค้า</th>
                                        <th>เวลาเก็บสินค้า</th>
                                        <th>product checker</th>
                                        <th>domestic checker</th>
                                        <th></th>
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
