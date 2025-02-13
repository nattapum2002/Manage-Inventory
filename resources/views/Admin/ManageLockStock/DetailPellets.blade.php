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
                        <table id="pallet" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th colspan="10">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-12">พาเลท : {{ $Pallets[0]->pallet_id ?? 'N/A' }}</div>
                                            <div class="col-lg-2 col-md-6 col-sm-12">ห้อง : {{ $Pallets[0]->warehouse ?? 'N/A' }}</div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">ลักษณะงาน : {{ $Pallets[0]->product_work_desc ?? 'N/A' }}</div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">สถานะ : 
                                                <span class="{{ $Pallets[0]->status == 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ $Pallets[0]->status == 0 ? 'ยังไม่จัดพาเลท' : 'จัดพาเลทแล้ว' }}
                                                </span>
                                            </div>
                                        </div>
                                        <form class="row mt-2" action="{{ route('UpdateLockTeam', [$Pallets[0]->pallet_pr_id]) }}" method="POST">
                                            @csrf
                                            <div class="col-lg-2 col-md-3 col-sm-4 text-center">
                                                <label for="team">ทีมจัดพาเลท</label>
                                            </div>
                                            <div class="col-lg-5 col-md-5 col-sm-6">
                                                <input type="text" class="form-control" id="team" name="team_name" value="{{ $Pallets[0]->team_name ?? '' }}" placeholder="ค้นหาทีม">
                                                <input type="hidden" id="team-id" name="team_id">
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <button class="btn btn-success" type="submit">บันทึก</button>
                                            </div>
                                        </form>
                                    </th>
                                </tr>
                                <tr>
                                    <th>รหัสสินค้า</th>
                                    <th>รายการ</th>
                                    <th class="text-center" colspan="4">สั่งจ่าย</th>
                                    <th>สถานะ</th>
                                    <th>การกระทำ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Pallets as $CustomerOrder)
                                    <tr>
                                        <td>{{ $CustomerOrder->product_number }}</td>
                                        <td>{{ $CustomerOrder->product_description }}</td>
                                        <td>{{ $CustomerOrder->quantity ?? 0 }}</td>
                                        <td>{{ $CustomerOrder->product_um }}</td>
                                        <td>{{ $CustomerOrder->quantity2 ?? 0 }}</td>
                                        <td>{{ $CustomerOrder->product_um2 }}</td>
                                        <td>{{ $CustomerOrder->status }}</td>
                                        <td>
                                            <a href="#" class="btn btn-primary">แก้ไข</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="10">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-12 col-sm-12">
                                                <form action="{{ route('updatePalletType', [$Pallets[0]->pallet_pr_id]) }}">
                                                    @csrf
                                                    <div class="input-group">
                                                        <span class="input-group-text">ประเภทพาเลท</span>
                                                        <select class="form-select" name="pallet_type_id" id="edit-pallet-type-select" disabled>
                                                            @foreach ($pallet_type as $type)
                                                                <option value="{{ $type->id }}" {{ $Pallets[0]->pallet_type == $type->pallet_type ? 'selected' : '' }}>
                                                                    {{ $type->pallet_type }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" id="edit-pallet-type" class="btn btn-primary">ปรับเปลี่ยน</button>
                                                        <button type="button" id="cancel-edit-pallet-type" class="btn btn-danger" hidden>ยกเลิก</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <a href="{{ route('DetailLockStock', [$CUS_ID, $ORDER_DATE]) }}" class="btn btn-warning">ย้อนกลับ</a>
            </div>
        </div>
    </div>
</section>

@endsection
