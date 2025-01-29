@extends('layouts.master')

@section('title')
    สินค้าคงคลัง
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="stocktable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>น้ำหนัก</th>
                                        <th>จํานวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stocks as $product_stock)
                                        <tr>
                                            <td>{{ $product_stock->product_id }}</td>
                                            <td>{{ $product_stock->product_name }}</td>
                                            <td>{{ $product_stock->weight }}</td>
                                            <td>{{ $product_stock->amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>น้ำหนัก</th>
                                        <th>จํานวน</th>
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
