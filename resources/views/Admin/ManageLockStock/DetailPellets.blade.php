@extends('layouts.master')

@section('title')
    จัดการล็อคพาเลท
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="pallte" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="8">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">พาเลท :
                                                    {{ $Pallets[0]->pallet_no }}
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12"></div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">ห้อง :
                                                    {{ $Pallets[0]->room }}
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">สถานะ :
                                                    {{ $Pallets[0]->status }}
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th>สั่งจ่าย</th>
                                        <th>จ่ายจริง</th>
                                        <th>สีถุง</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Pallets as $CustomerOrder)
                                        <tr>
                                            <td>{{ $CustomerOrder->product_id }}</td>
                                            <td>{{ $CustomerOrder->product_name }}</td>
                                            <td>{{ $CustomerOrder->amount_order }}</td>
                                            <td>{{ $CustomerOrder->amount_paid }}</td>
                                            <td>{{ $CustomerOrder->bag_color }}</td>
                                            <td>{{ $CustomerOrder->note }}</td>
                                            <td>{{ $CustomerOrder->status }}</td>
                                            <td>
                                                <a href="{{ route('DetailLockStock', $CustomerOrder->order_number) }}"
                                                    class="btn btn-primary"><i class="far fa-file-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th>สั่งจ่าย</th>
                                        <th>จ่ายจริง</th>
                                        <th>สีถุง</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="8">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    หมายเหตุ : {{ $Pallets[0]->note }}
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12"></div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    รวมสั่งจ่าย :
                                                    {{ $Pallets->where('pallet_id', $Pallets[0]->pallet_id)->where('order_number', $Pallets[0]->order_number)->sum('amount_order') }}
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    รวมจ่ายจริง :
                                                    {{ $Pallets->where('pallet_id', $Pallets[0]->pallet_id)->where('order_number', $Pallets[0]->order_number)->sum('amount_paid') }}
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
