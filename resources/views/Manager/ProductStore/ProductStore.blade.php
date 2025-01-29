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
                                    @foreach ($product_stores as $receipt_product)
                                        <tr>
                                            <td>{{ $receipt_product->product_slip_id }}</td>
                                            <td>{{ $receipt_product->product_slip_number }}</td>
                                            <td>{{ $receipt_product->store_date }}</td>
                                            <td>{{ $receipt_product->store_time }}</td>
                                            <td>{{ $receipt_product->product_checker }}</td>
                                            <td>{{ $receipt_product->domestic_checker }}</td>
                                            <td>
                                                <a href="{{ route('DetailProductStore', $receipt_product->product_slip_id) }}"
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
