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
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="order_number">หมายเลขออเดอร์</label>
                                        <input type="text" class="form-control" id="order_number" name="order_number"
                                            placeholder="หมายเลขออเดอร์"
                                            value="{{ $CustomerOrders[0]->order_number ?? 'N/A' }}" disabled>
                                    </div>
                                </div>
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
                                        <label for="customer_name">วันที่สั่ง</label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                                            placeholder="วันที่สั่ง"
                                            value="{{ (new DateTime($CustomerOrders[0]->order_date))->format('d/m/Y') ?? 'N/A' }}"
                                            disabled>
                                    </div>
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
                                        <th>หน่วย</th>
                                        <th>จำนวน(เพิ่มเติม)</th>
                                        <th>หน่วย(เพิ่มเติม)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($CustomerOrders as $CustomerOrder)
                                        <tr>
                                            <td>{{ $CustomerOrder->product_number }}</td>
                                            <td>{{ $CustomerOrder->product_description }}</td>
                                            <td>{{ $CustomerOrder->quantity }}</td>
                                            <td>{{ $CustomerOrder->product_um }}</td>
                                            <td>{{ $CustomerOrder->quantity2 }}</td>
                                            <td>{{ $CustomerOrder->product_um2 }}</td>
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
                                    </tr>
                                    <tr>
                                        <th colspan="7">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    {{-- หมายเหตุ : {{ $CustomerOrders[0]->note ?? 'N/A' }} --}}
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

                                <a href="{{ route('PreLock', [$CustomerOrders[0]->customer_id, $CustomerOrders[0]->order_date]) }}"
                                    class="btn btn-primary">จัดใบล็อค</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="pallate" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>หมายเลข</th>
                                        <th>ห้อง</th>
                                        <th>ทีมจัด</th>
                                        <th>ประเภท</th>
                                        <th>สถานะ</th>
                                        <th>การจัดส่ง</th>
                                        <th>หมายเหตุ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Pallets as $Pallet)
                                        <tr>
                                            <td>{{ $Pallet->pallet_name }}</td>
                                            <td id="warehouse-name">{{ $Pallet->warehouse }}</td>
                                            <td>{{ $Pallet->team_name ?? 'ไม่มี' }}</td>
                                            <td>{{ $Pallet->pallet_type }}</td>
                                            <td>
                                                {!! $Pallet->arrange_pallet_status == 1
                                                    ? '<p class="text-success">จัดพาเลทแล้ว</p>'
                                                    : '<p class="text-danger">ยังไม่จัดพาเลท</p>' !!}
                                            </td>
                                            <td>
                                                {!! $Pallet->recive_status == 1
                                                    ? '<p class="text-success">ส่งแล้ว</p>'
                                                    : '<p class="text-danger">ยังไม่จัดส่ง</p>' !!}
                                            </td>
                                            <td>{{ $Pallet->note ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('DetailPallets', [$ORDER_DATE, $CUS_ID, $Pallet->pallet_id]) }}"
                                                    class="btn btn-primary"><i class="far fa-file-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>หมายเลข</th>
                                        <th>ห้อง</th>
                                        <th>ทีมจัด</th>
                                        <th>ประเภท</th>
                                        <th>สถานะ</th>
                                        <th>การจัดส่ง</th>
                                        <th>หมายเหตุ</th>
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
