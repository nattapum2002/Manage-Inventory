@extends('layouts.master')

@section('title')
    เพิ่มใบส่งสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('SaveAddTransferSlip') }}" method="POST">
                                @csrf
                                <article>
                                    <div class="row">
                                        {{-- <div class="col">
                                            <div class="form-group">
                                                <label for="slip_id" class="form-label">กรอกหมายเลขสลิป</label>
                                                <input type="number" class="form-control" id="slip_id" name="slip_id"
                                                    value="{{ $request->slip_id ?? '' }}" readonly>
                                            </div>
                                        </div> --}}
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="slip_number" class="form-label">สลิปใบที่</label>
                                                <input type="number" class="form-control" id="slip_number"
                                                    name="slip_number" value="{{ $request->slip_number ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="department" class="form-label">หน่วยงาน</label>
                                                <input type="text" class="form-control" id="department" name="department"
                                                    value="{{ $request->department ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="date" class="form-label">วันที่</label>
                                                <input type="date" class="form-control" id="date" name="date"
                                                    value="{{ $request->date }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="time" class="form-label">เวลา</label>
                                                <input type="time" class="form-control" id="time" name="time"
                                                    value="{{ $request->time }}" readonly>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" id="receipt_plan_id"
                                            name="receipt_plan_id" value="{{ $receipt_plan_id }}">
                                    </div>
                                </article>
                                <hr>
                                <table id="AddTransferSlipTable" class="table table-bordered table-striped nowrap">
                                    <thead>
                                        <tr>
                                            <th>รหัสสินค้า</th>
                                            <th>รายการสินค้า</th>
                                            <th>จำนวนตามแผน(กก.)</th>
                                            <th>จำนวนที่ต้องได้รับ(กก.)</th>
                                            <th>จำนวนที่รับ(กก.)</th>
                                            <th>หน่วย</th>
                                            <th>หมายเหตุ</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mergedData as $Product)
                                            <tr>
                                                <td>{{ $Product['product_id'] }}</td>
                                                <td>{{ $Product['product_description'] }}</td>
                                                <td>{{ $Product['total_weight'] }}</td>
                                                <td>
                                                    {{ $Product['total_sum'] }}
                                                    <input type="hidden" class="form-control"
                                                        id="total_sum_{{ $Product['product_id'] }}"
                                                        name="total_sum[{{ $Product['product_id'] }}]"
                                                        value="{{ $Product['total_sum'] }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" class="form-control"
                                                        id="quantity_{{ $Product['product_id'] }}"
                                                        name="quantity[{{ $Product['product_id'] }}]" min="0"
                                                        max="{{ $Product['total_sum'] }}" value="0"
                                                        {{ $Product['total_sum'] == 0 ? 'readonly' : '' }}>
                                                </td>
                                                <td>
                                                    {{ $Product['product_um'] }}
                                                    {{-- <input type="hidden" class="form-control"
                                                        id="quantity_um_{{ $Product['product_id'] }}"
                                                        name="quantity_um[{{ $Product['product_id'] }}]"
                                                        value="{{ $Product['product_um'] }}"> --}}
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        id="note_{{ $Product['product_id'] }}"
                                                        name="note[{{ $Product['product_id'] }}]"
                                                        {{ $Product['total_sum'] == 0 ? 'readonly' : '' }}>
                                                </td>
                                                <td>
                                                    @if ($Product['total_sum'] == 0)
                                                        <p class="text-success">สินค้าครบตามแผนแล้ว</p>
                                                    @else
                                                        <button type="submit" class="btn btn-success">บันทึก</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>รหัสสินค้า</th>
                                            <th>รายการสินค้า</th>
                                            <th>จำนวนตามแผน(กก.)</th>
                                            <th>จำนวนที่ต้องได้รับ(กก.)</th>
                                            <th>จำนวนที่รับแล้ว</th>
                                            <th>หน่วย</th>
                                            <th>หมายเหตุ</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(function() {
            $("#AddTransferSlipTable").DataTable({
                // responsive: true,
                // lengthChange: true,
                // autoWidth: true,
                info: false,
                scrollX: true,
                ordering: true,
                paging: true,
                pageLength: 25,
                lengthMenu: [25, 50, 100],
                order: []
            });
        });
    </script>
@endsection
