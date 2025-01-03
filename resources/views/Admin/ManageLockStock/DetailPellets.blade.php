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
                                        <th colspan="10">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">พาเลท :
                                                    {{ $Pallets[0]->pallet_id ?? 'N/A' }}
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-12">ห้อง :
                                                    {{ $Pallets[0]->whs_name }}
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">ลักษณะงาน :
                                                    {{ $Pallets[0]->product_work_desc}}
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    สถานะ : {!! $Pallets[0]->status == 0
                                                        ? '<span class="text-danger">ยังไม่จัดพาเลท</p>'
                                                        : '<span class="text-success">จัดพาเลทแล้ว</span>' !!}
                                                </div>

                                                <form class="row"
                                                    action="{{ route('UpdateLockTeam', [$Pallets[0]->id]) }}" method="POST">
                                                    @csrf
                                                    <div class="col-lg-1 col-md-2 col-sm-3">
                                                        <label for="room">ทีมจัดพาเลท</label>
                                                    </div>
                                                    <div class="col-lg-1 col-md-2 col-sm-7">
                                                        <input type="text" class="form-control" id="team"
                                                            name="" value="{{$Pallets[0]->team_name ?? ''}}" placholder="ค้นหาทีม">
                                                        <input type="hidden" class="form-control" id="team-id"
                                                            name="team_id">
                                                    </div>
                                                    <div class="col-lg-1 col-md-2 col-sm-2">
                                                        <button class="btn btn-success" type="submit">บันทึก</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th class="text-center" colspan="4">สั่งจ่าย</th>
                                        <th class="text-center" colspan="2">จ่ายจริง</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Pallets as $CustomerOrder)
                                        <tr>
                                            <td>{{ $CustomerOrder->item_no }}</td>
                                            <td>{{ $CustomerOrder->item_desc1 }}</td>
                                            <td>{{ $CustomerOrder->ORDERED_QUANTITY ?? 0 }}</td>
                                            <td>{{ $CustomerOrder->UOM1 }}</td>
                                            <td>{{ $CustomerOrder->ORDERED_QUANTITY2 ?? 0 }}</td>
                                            <td>{{ $CustomerOrder->UOM2 }}</td>
                                            <td>{{ $CustomerOrder->quantity }}</td>
                                            <td>Kg</td>
                                            <td>{{ $CustomerOrder->status }}</td>
                                            <td>
                                                <a href="" class="btn btn-primary">แก้ไข</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th class="text-center" colspan="4">สั่งจ่าย</th>
                                        <th class="text-center" colspan="2">จ่ายจริง</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="10">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <form action="" method="post">
                                                        <div class="input-group">
                                                            <span class="input-group-text">หมายเหตุ</span>
                                                            <input type="text" class="form-control"
                                                                name="pallet_order_note"
                                                                value="{{ $Pallets[0]->pallet_type }}" disabled>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12"></div>
                                                {{-- <div class="col-lg-3 col-md-6 col-sm-12">
                                                    รวมสั่งจ่าย :
                                                    {{-- {{ $Pallets->where('pallet_id', $Pallets[0]->pallet_id)->where('order_number', $Pallets[0]->order_number)->sum('amount_order') ?? 'N/A' }} --}}
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                {{-- รวมจ่ายจริง :
                                                    {{ $Pallets->where('pallet_id', $Pallets[0]->pallet_id)->where('order_number', $Pallets[0]->order_number)->sum('amount_paid') }} --}}
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                        </table>
                        
                    </div>
                    
                </div>
                <a type="button" class="btn btn-warning" href="{{route('DetailLockStock',[$CUS_ID , $ORDER_DATE])}}">ย้อนกลับ</a>
            </div>
        </div>
        </div>
    </section>
@endsection
