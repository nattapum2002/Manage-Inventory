@extends('layouts.master')

@section('title')
    รายละเอียดล็อคสินค้า : {{ $CustomerOrders[0]->customer_name ?? 'N/A' }}
@endsection

@section('content')
    <section class="content">
        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}
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
                                        <th colspan="7">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    ชื่อลูกค้า : {{ $CustomerOrders[0]->customer_name ?? 'N/A' }}
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12"></div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    วันที่ : {{ $CustomerOrders[0]->ORDERED_DATE ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th>จำนวน</th>
                                        <th>หน่วย</th>
                                        <th>จำนวน(เพิ่มเติม)</th>
                                        <th>หน่วย(เพิ่มเติม)</th>
                                        <th>ORDER_BY_CUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($CustomerOrders as $CustomerOrder)
                                        <tr>
                                            <td>{{ $CustomerOrder->item_no }}</td>
                                            <td>{{ $CustomerOrder->item_desc1 }}</td>
                                            <td>{{ $CustomerOrder->ORDERED_QUANTITY }}</td>
                                            <td>{{ $CustomerOrder->UOM1 }}</td>
                                            <td>{{ $CustomerOrder->ORDERED_QUANTITY2 }}</td>
                                            <td>{{ $CustomerOrder->UOM2 }}</td>
                                            <td>{{ $CustomerOrder->ORDER_BY_CUS }}</td>
                                            {{-- <td>
                                                <a href="{{ route('DetailLockStock', $CustomerOrder->order_number) }}"
                                                    class="btn btn-primary"><i class="far fa-file-alt"></i></a>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th>จำนวน</th>
                                        <th>หน่วย</th>
                                        <th>จำนวน(เพิ่มเติม)</th>
                                        <th>หน่วย(เพิ่มเติม)</th>
                                        <th>ORDER_BY_CUS</th>
                                    </tr>
                                    <tr>
                                        <th colspan="7">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    หมายเหตุ : {{ $CustomerOrders[0]->note ?? 'N/A' }}
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
                                <h3 class="card-title">ใบล็อค</h3>

                                <a href="{{route('PreLock',[$CustomerOrders[0]->CUSTOMER_ID,$CustomerOrders[0]->ORDERED_DATE])}}"
                                    class="btn btn-primary">จัดใบล็อค</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="pallate" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="9">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">ชื่อลูกค้า :
                                                    {{ $CustomerOrders[0]->customer_name ?? 'N/A' }}</div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>รหัสพาเลท</th>
                                        <th>หมายเลข</th>
                                        <th>ห้อง</th>
                                        <th>ทีมจัด</th>
                                        <th>ประเภท</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th>การจัดส่ง</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Pallets as $Pallet)
                                        <tr>
                                            <td>{{ $Pallet->pallet_id }}</td>
                                            <td>{{ $Pallet->pallet_no }}</td>
                                            <td id="warehouse-name">{{ $Pallet->room }}</td>
                                            <td>{{ $Pallet->team_name ?? 'ไม่มี' }}</td>
                                            <td>{{ $Pallet->pallet_type }}</td>
                                            <td>{{ $Pallet->note }}</td>
                                            <td>{!! $Pallet->status == 1
                                                ? '<p class="text-success">จัดพาเลทแล้ว</p>'
                                                : '<p class="text-danger">ยังไม่จัดพาเลท</p>' !!}</td>
                                            <td>{!! $Pallet->recive_status == 1
                                                ? '<p class="text-success">ส่งแล้ว</p>'
                                                : '<p class="text-danger">ยังไม่จัดส่ง</p>' !!}</td>
                                            <td>
                                                <a href="{{route('DetailPallets',[$ORDER_DATE,$CUS_ID,$Pallet->id])}}"
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
                                        <th>ทีมจัด</th>
                                        <th>ประเภท</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th>การจัดส่ง</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="9">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    หมายเหตุ : {{ $CustomerOrders[0]->note ?? 'N/A' }}
                                                </div>
                                                {{-- <div class="col-lg-6 col-md-6 col-sm-12">
                                                    พนักงานจัด :
                                                    @foreach ($LockTeams->where('team_id', $CustomerOrders[0]->team_id) as $LockTeam)
                                                        {{ $LockTeam->name }}
                                                    @endforeach
                                                </div> --}}
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
