@extends('layouts.master')

@section('title')
    รายละเอียดล็อคสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">รายละเอียดคำสั่งซื้อ (Order detail)</h3>
                        </div>
                        <div class="card-body">
                            <table id="locktable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="10">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    ชื่อลูกค้า : {{ $CustomerOrders[0]->customer_name }}
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12"></div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    ทีม : {{ $CustomerOrders[0]->team_name ?? 'N/A' }}
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    วันที่ : {{ $CustomerOrders[0]->date }}
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th>จำนวน</th>
                                        <th>UOM</th>
                                        <th>จำนวน</th>
                                        <th>UOM2</th>
                                        <th>สีถุง</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($CustomerOrders as $CustomerOrder)
                                        <tr>
                                            <td>{{ $CustomerOrder->product_id }}</td>
                                            <td>{{ $CustomerOrder->product_name }}</td>
                                            <td>{{ $CustomerOrder->ordered_quantity }}</td>
                                            <td>{{ $CustomerOrder->product_uom }}</td>
                                            <td>{{ $CustomerOrder->ordered_quantity2 }}</td>
                                            <td>{{ $CustomerOrder->product_uom2 }}</td>
                                            <td>{{ $CustomerOrder->bag_color }}</td>
                                            <td>{{ $CustomerOrder->note }}</td>
                                            <td>{{ $CustomerOrder->status }}</td>
                                            <td>
                                                {{-- <a href="{{ route('DetailLockStock', $CustomerOrder->order_number) }}"
                                                    class="btn btn-primary"><i class="far fa-file-alt"></i></a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th>จำนวน</th>
                                        <th>UOM</th>
                                        <th>จำนวน</th>
                                        <th>UOM2</th>
                                        <th>สีถุง</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="10">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    หมายเหตุ : {{ $CustomerOrders[0]->note }}
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    {{-- @foreach ($LockTeams->where('team_id', $CustomerOrders[0]->team_id) as $LockTeam)
                                                        พนักงานจัด : {{ $LockTeam->name }}
                                                    @endforeach --}}
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">พาเลท (Pallets)</h3>
                                <a href="{{ route('AddPallet', $CustomerOrders[0]->order_number) }}"
                                    class="btn btn-primary">เพิ่มพาเลท</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="pallte" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="6">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">ชื่อลูกค้า :
                                                    {{ $CustomerOrders[0]->customer_name }}</div>
                                                <div class="col-lg-3 col-md-6 col-sm-12"></div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">ทีม :
                                                    {{ $CustomerOrders[0]->team_name }}</div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">วันที่ :
                                                    {{ $CustomerOrders[0]->date }}</div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>รหัสพาเลท</th>
                                        <th>หมายเลข</th>
                                        <th>ห้อง</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Pallets->where('order_number', $CustomerOrders[0]->order_number) as $Pallet)
                                        <tr>
                                            <td>{{ $Pallet->pallet_id }}</td>
                                            <td>{{ $Pallet->pallet_no }}</td>
                                            <td>{{ $Pallet->room }}</td>
                                            <td>{{ $Pallet->note }}</td>
                                            <td>{{ $Pallet->status }}</td>
                                            <td>
                                                <a href="{{ route('DetailPallets', ['order_number' => $Pallet->order_number, 'pallet_id' => $Pallet->pallet_id]) }}"
                                                    class="btn btn-primary"><i class="far fa-file-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสพาเลท</th>
                                        <th>หมายเลข</th>
                                        <th>ห้อง</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="6">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    หมายเหตุ : {{ $CustomerOrders[0]->note }}
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    @foreach ($LockTeams->where('team_id', $CustomerOrders[0]->team_id) as $LockTeam)
                                                        พนักงานจัด : {{ $LockTeam->name }}
                                                    @endforeach
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
