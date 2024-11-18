@extends('layouts.master')

@section('title')
    จัดการล็อคสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="locktable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>ลูกค้า</th>
                                        <th>ทีม</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($CustomerOrders as $CustomerOrder)
                                        <tr>
                                            <td>{{ $CustomerOrder->order_number }}</td>
                                            <td>{{ $CustomerOrder->customer_name }}</td>
                                            <td>{{ $CustomerOrder->team_name ?? 'ไม่มี' }}</td>
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
                                    <th>รหัสคำสั่งซื้อ</th>
                                    <th>ลูกค้า</th>
                                    <th>ทีม</th>
                                    <th>หมายเหตุ</th>
                                    <th>สถานะ</th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
