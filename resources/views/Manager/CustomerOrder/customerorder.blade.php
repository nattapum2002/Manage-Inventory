@extends('layouts.master')

@section('title')
    คำสั่งซื้อ
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="customer_ordertable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>รหัสลูกค้า</th>
                                        <th>วันที่</th>
                                        <th>ผู้แพ็คสินค้า</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_orders as $customer_order)
                                        <tr>
                                            <td>{{ $customer_order->order_id }}</td>
                                            <td>{{ $customer_order->customer_id }}</td>
                                            <td>{{ $customer_order->date }}</td>
                                            <td>{{ $customer_order->packer_id }}</td>
                                            <td>
                                                <a href="{{ route('DetailCustomerOrder', $customer_order->order_id) }}"
                                                    class="btn btn-primary"><i class="far fa-file-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>รหัสลูกค้า</th>
                                        <th>วันที่</th>
                                        <th>ผู้แพ็คสินค้า</th>
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
