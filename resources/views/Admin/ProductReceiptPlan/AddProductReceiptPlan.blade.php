@extends('layouts.master')

@section('title')
    เพิ่มแผนรับสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('SaveProductReceiptPlan') }}" method="POST">
                                @csrf
                                <article class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="product_receipt_plan_id">รหัสแผนรับสินค้า</label>
                                            <input type="text" class="form-control" id="product_receipt_plan_id"
                                                name="product_receipt_plan_id" placeholder="รหัสแผนรับสินค้า">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_id">กะพนักงาน</label>
                                            <select class="form-control" id="shift_id" name="shift_id">
                                                <option selected value="">เลือกกะพนักงาน</option>
                                                @foreach ($shifts as $shift)
                                                    <option value="{{ $shift->shift_id }}">{{ $shift->shift_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="date">วันที่</label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                placeholder="วันที่">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <input type="text" class="form-control" id="note" name="note"
                                                placeholder="หมายเหตุ">
                                        </div>
                                    </div>
                                </article>
                                <hr>
                                <table id="ProductReceiptPlanTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            @foreach ($detailHeader as $header)
                                                <th>{{ $header }}</th>
                                            @endforeach
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rows as $row)
                                            <tr>
                                                <td>{{ $row[0] }}</td>
                                                <td>{{ $row[1] }}</td>
                                                <td>{{ $row[2] }}</td>
                                                <td>{{ $row[3] }}</td>
                                                <td>{{ $row[4] }}</td>
                                                <td>{{ $row[5] }}</td>
                                                <td>{{ $row[6] ?? 'N/A' }}</td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            @foreach ($detailHeader as $header)
                                                <th>{{ $header }}</th>
                                            @endforeach
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    <input type="hidden" id="filePath" name="filePath" value="{{ $filePath }}">
                                    <button type="submit" class="btn btn-success">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
