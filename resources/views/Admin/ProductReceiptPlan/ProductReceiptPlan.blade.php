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
                            <ol>
                                <li>กรุณาเลือกไฟล์ .xlsx, .xls, .csv</li>
                                <li>กรุณาใช้ไฟล์ <a href="{{ url('storage/FormExcel/FormProductReceiptPlan.xlsx') }}"
                                        download>แบบฟอร์มแผนรับสินค้า</a>
                                </li>
                            </ol>
                            <form action="{{ route('AddProductReceiptPlan') }}" method="POST" enctype="multipart/form-data"
                                class="d-flex">
                                @csrf
                                <input type="file" class="form-control" name="file" accept=".xlsx, .xls, .csv">
                                <button type="submit" class="btn btn-success">เพิ่ม</button>
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
