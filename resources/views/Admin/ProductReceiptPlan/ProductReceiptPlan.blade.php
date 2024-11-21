@extends('layouts.master')

@section('title')
    แผนรับสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">เพิ่มแผนรับสินค้ารายกะ</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('AddProductReceiptPlan') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="product_receipt_plan_id">รหัสแผนรับสินค้า</label>
                                            <input type="text" class="form-control" id="product_receipt_plan_id"
                                                name="product_receipt_plan_id" placeholder="รหัสแผนรับสินค้า">
                                            @error('product_receipt_plan_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_id">กะพนักงาน</label>
                                            <select class="form-control" id="shift_id" name="shift_id">
                                                <option selected value="">เลือกกะพนักงาน</option>
                                                @foreach ($shifts as $shift)
                                                    <option value="{{ $shift->shift_id }}">{{ $shift->shift_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('shift_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="date">วันที่</label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                placeholder="วันที่">
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-10 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <input type="text" class="form-control" id="note" name="note"
                                                placeholder="หมายเหตุ">
                                            @error('note')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="file">ไฟล์แผนรับสินค้า</label>
                                            <ol>
                                                <li>กรุณาเลือกไฟล์ .xlsx, .xls, .csv</li>
                                                <li>กรุณาใช้ไฟล์ <a
                                                        href="{{ url('storage/FormExcel/FormProductReceiptPlan.xlsx') }}"
                                                        download>แบบฟอร์มแผนรับสินค้า</a>
                                                </li>
                                            </ol>
                                            <input type="file" class="form-control" name="file"
                                                accept=".xlsx, .xls, .csv">
                                            @error('file')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-success">เพิ่ม</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">แผนรับสินค้ารายกะ</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="ProductReceiptPlanTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสแผนรับสินค้า</th>
                                        <th>ชื่อแผนรับสินค้า</th>
                                        <th>กะ</th>
                                        <th>วันที่</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ProductReceiptPlans as $ProductReceiptPlan)
                                        <tr>
                                            <td>{{ $ProductReceiptPlan->product_receipt_plan_id }}</td>
                                            <td>{{ $ProductReceiptPlan->product_receipt_plan_name }}</td>
                                            <td>{{ $ProductReceiptPlan->shift_name }}</td>
                                            <td>{{ $ProductReceiptPlan->date }}</td>
                                            <td>{{ $ProductReceiptPlan->note ?? 'ไม่มีหมายเหตุ' }}</td>
                                            <td>{{ $ProductReceiptPlan->status ? 'ใช้งาน' : 'ไม่ใช้งาน' }}</td>
                                            <td>
                                                <a href="{{ route('EditProductReceiptPlan', $ProductReceiptPlan->product_receipt_plan_id) }}"
                                                    class="btn btn-primary"><i class="fas fa-edit"></i>
                                                </a>
                                                {{-- <a href="{{ route('ManageShift.Toggle', [$shift->shift_id, $shift->status ? 0 : 1]) }}"
                                                    class="btn {{ $shift->status ? 'btn-danger' : 'btn-success' }}">
                                                    <i class="fas {{ $shift->status ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                </a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th>รหัสแผนรับสินค้า</th>
                                    <th>ชื่อแผนรับสินค้า</th>
                                    <th>กะ</th>
                                    <th>วันที่</th>
                                    <th>หมายเหตุ</th>
                                    <th>สถานะ</th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection