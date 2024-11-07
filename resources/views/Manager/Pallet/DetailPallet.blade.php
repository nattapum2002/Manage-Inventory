@extends('layouts.master')

@section('title')
    รายละเอียดพาเลท
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="DetailPallettable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="5">
                                            <div class="row">
                                                <div class="col-3">
                                                    รหัสพาเลท : {{ $pallets[0]->pallet_id ?? 'N/A' }}
                                                </div>
                                                <div class="col-3">
                                                    รหัสคำสั่งซื้อ : {{ $pallets[0]->order_id ?? 'N/A' }}
                                                </div>
                                                <div class="col-3">
                                                    เวลาเริ่มแพ็คสินค้า : {{ $pallets[0]->pack_start_time ?? 'N/A' }}</div>
                                                <div class="col-3">
                                                    เวลาสิ้นสุดแพ็คสินค้า : {{ $pallets[0]->pack_end_time ?? 'N/A' }}</div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายละเอียดสินค้า</th>
                                        <th>จํานวนที่สั่ง</th>
                                        <th>จํานวนที่ส่ง</th>
                                        <th>สีถุง</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pallets as $pallet)
                                        <tr>
                                            <td>{{ $pallet->product_id ?? 'N/A' }}</td>
                                            <td></td>
                                            <td>{{ $pallet->order_amount ?? 'N/A' }}</td>
                                            <td>{{ $pallet->send_amount ?? 'N/A' }}</td>
                                            <td>{{ $pallet->bag_color ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายละเอียดสินค้า</th>
                                        <th>จํานวนที่สั่ง</th>
                                        <th>จํานวนที่ส่ง</th>
                                        <th>สีถุง</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5">
                                            <div class="row">
                                                <div class="col-3">
                                                    ห้อง : {{ $pallets[0]->room ?? 'N/A' }}
                                                </div>
                                                <div class="col-3"></div>
                                                <div class="col-3">
                                                    กะ : {{ $pallets[0]->shift_id ?? 'N/A' }}
                                                </div>
                                                <div class="col-3">
                                                    ผู้เช็ค : {{ $pallets[0]->checker_id ?? 'N/A' }}
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
