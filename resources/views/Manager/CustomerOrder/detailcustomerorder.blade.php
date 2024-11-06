@extends('layouts.master')

@section('title')
    รายละเอียดคำสั่งซื้อ
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="detail_customer_ordertable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="3">
                                            <div class="row">
                                                <div class="col-4">
                                                    รหัสคำสั่งซื้อ : {{ $customer_orders[0]->order_id }}
                                                </div>
                                                <div class="col-4">
                                                    รหัสลูกค้า : {{ $customer_orders[0]->customer_id }}
                                                </div>
                                                <div class="col-4">
                                                    วันที่ : {{ $customer_orders[0]->date }}
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>จํานวนที่สั่ง</th>
                                        <th>จํานวนที่ส่ง</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_orders as $customer_order)
                                        <tr>
                                            <td>{{ $customer_order->product_id }}</td>
                                            <td>{{ $customer_order->order_amount }}</td>
                                            <td>{{ $customer_order->send_amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>จํานวนที่สั่ง</th>
                                        <th>จํานวนที่ส่ง</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">
                                            <div class="row">
                                                <div class="col-8">
                                                </div>
                                                <div class="col-4">
                                                    ผู้แพ็คสินค้า : {{ $customer_orders[0]->packer_id }}
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
