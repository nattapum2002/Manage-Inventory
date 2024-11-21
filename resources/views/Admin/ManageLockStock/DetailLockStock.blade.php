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
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="customer_name">ชื่อลูกค้า</label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                                            placeholder="ชื่อลูกค้า"
                                            value="{{ $CustomerOrders[0]->customer_name ?? 'N/A' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">

                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">

                                </div>
                            </div>
                            <hr>
                            <table id="locktable" class="table table-bordered table-striped">
                                <thead>
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
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="customer_name">ชื่อลูกค้า</label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                                            placeholder="ชื่อลูกค้า"
                                            value="{{ $CustomerOrders[0]->customer_name ?? 'N/A' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="team_name">ชื่อทีม</label>
                                        <select class="form-control" id="team_name" name="team_name">
                                            <option selected value="{{ $CustomerOrders[0]->team_name ?? '' }}">
                                                {{ $CustomerOrders[0]->team_name ?? 'เลือกชื่อทีม' }}
                                            </option>
                                            @foreach ($team_names->where('team_name', '!=', $CustomerOrders[0]->team_name ?? '') as $team)
                                                <option value="{{ $team->team_name }}">
                                                    {{ $team->team_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">วันที่</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            placeholder="วันที่"
                                            value="{{ isset($CustomerOrders[0]->date) ? $CustomerOrders[0]->date : '' }}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <table id="pallte" class="table table-bordered table-striped">
                                <thead>
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
                                            <td>{{ $Pallet->pallet_no ?? 'N/A' }}</td>
                                            <td>{{ $Pallet->room ?? 'N/A' }}</td>
                                            <td>{{ $Pallet->note ?? 'N/A' }}</td>
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
                                                    พนักงานจัด :
                                                    @foreach ($LockTeams->where('team_id', $CustomerOrders[0]->team_id) as $LockTeam)
                                                        {{ $LockTeam->name }}
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
