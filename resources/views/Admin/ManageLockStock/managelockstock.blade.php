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
                                        <th>วันที่สั่ง</th>
                                        <th>วันที่รับสินค้า</th>
                                        <th>ลูกค้า</th>
                                        <th>เกรดลูกค้า</th>
                                        <th>รายละเอียด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($CustomerOrders as $CustomerOrder)
                                        <tr>
                                            <td>{{ (new DateTime($CustomerOrder->order_date))->format('d/m/Y') }}</td>
                                            <td>{{ (new DateTime($CustomerOrder->ship_datetime))->format('d/m/Y') }}</td>
                                            <td>{{ $CustomerOrder->customer_name }}</td>
                                            <td>{{ $CustomerOrder->customer_grade ?? 'ไม่มี' }}</td>
                                            <td>
                                                <a href="{{ route('DetailLockStock', [$CustomerOrder->customer_id, $CustomerOrder->order_date]) }}"
                                                    class="btn btn-primary"><i class="far fa-file-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>วันที่สั่ง</th>
                                        <th>วันที่รับสินค้า</th>
                                        <th>ลูกค้า</th>
                                        <th>เกรดลูกค้า</th>
                                        <th>รายละเอียด</th>
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
