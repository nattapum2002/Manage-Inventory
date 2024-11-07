@extends('layouts.master')

@section('title')
    พาเลท
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="pallettable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสพาเลท</th>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>ห้อง</th>
                                        <th>เวลาเริ่มแพ็คสินค้า</th>
                                        <th>เวลาสิ้นสุดแพ็คสินค้า</th>
                                        <th>ผู้เช็ค</th>
                                        <th>กะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pallets as $pallet)
                                        <tr>
                                            <td>{{ $pallet->pallet_id }}</td>
                                            <td>{{ $pallet->order_id }}</td>
                                            <td>{{ $pallet->room }}</td>
                                            <td>{{ $pallet->pack_start_time }}</td>
                                            <td>{{ $pallet->pack_end_time }}</td>
                                            <td>{{ $pallet->checker_id }}</td>
                                            <td>{{ $pallet->shift_id }}</td>
                                            <td>
                                                <a href="{{ route('DetailPallet', $pallet->pallet_id) }}"
                                                    class="btn btn-primary"><i class="far fa-file-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสพาเลท</th>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>ห้อง</th>
                                        <th>เวลาเริ่มแพ็คสินค้า</th>
                                        <th>เวลาสิ้นสุดแพ็คสินค้า</th>
                                        <th>ผู้เช็ค</th>
                                        <th>กะ</th>
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
@endSection
