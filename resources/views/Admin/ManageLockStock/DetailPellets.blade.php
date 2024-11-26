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
                                        <th colspan="14">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">พาเลท :
                                                    {{ $Pallets[0]->pallet_no ?? 'N/A' }}
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12"></div>
                                                <div class="col-lg-2 col-md-6 col-sm-12">ห้อง :
                                                    {{ $Pallets[0]->room }}
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    สถานะ : {!! $Pallets[0]->status == 0 ? '<span class="text-danger">ยังไม่จัดพาเลท</p>' : '<span class="text-success">จัดพาเลทแล้ว</span>' !!}
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th class="text-center" colspan="4">สั่งจ่าย</th>
                                        <th class="text-center" colspan="4">จ่ายจริง</th>
                                        <th>สีถุง</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Pallets as $CustomerOrder)
                                        <tr>
                                            <td>{{ $CustomerOrder->item_no }}</td>
                                            <td>{{ $CustomerOrder->item_desc1 }}</td>
                                            <td>{{ $CustomerOrder->order_quantity ?? 0 }}</td>
                                            <td>{{ $CustomerOrder->item_um }}</td>
                                            <td>{{ $CustomerOrder->order_quantity2 ?? 0  }}</td>
                                            <td>{{ $CustomerOrder->item_um2 }}</td>
                                            <td>{{ $CustomerOrder->quantity }}</td>
                                            <td>{{ $CustomerOrder->item_um }}</td>
                                            <td>{{ $CustomerOrder->quantity2 }}</td>
                                            <td>{{ $CustomerOrder->item_um2 }}</td>
                                            <td>{{ $CustomerOrder->bag_color ?? 'N/A' }}</td>
                                            <td>{{ $CustomerOrder->pallet_order_note ?? 'ไม่มี' }}</td>
                                            <td>{{ $CustomerOrder->status }}</td>
                                            <td>
                                                <a href="{{ route('EditPalletOrder',[$CustomerOrder->order_id , $CustomerOrder->product_id]) }}"
                                                    class="btn btn-primary">แก้ไข</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการ</th>
                                        <th class="text-center" colspan="4">สั่งจ่าย</th>
                                        <th class="text-center" colspan="4">จ่ายจริง</th>
                                        <th>สีถุง</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="14">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                   <form action="" method="post">
                                                        <div class="input-group">
                                                            <span class="input-group-text">หมายเหตุ</span>
                                                           <input type="text" class="form-control" name="pallet_order_note" value="{{$Pallets[0]->pallet_type}}" disabled>
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