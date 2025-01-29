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
                            <form action="{{ route('SaveAddReceiptPlan') }}" method="POST">
                                @csrf
                                <article class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_id">กะพนักงาน</label>
                                            <select class="form-control" id="shift_id" name="shift_id" readonly>
                                                <option selected value="{{ $shift->shift_id }}">
                                                    {{ $shift->shift_name }}
                                                </option>
                                            </select>
                                            <input type="hidden" class="form-control" id="shift_id" name="shift_id"
                                                placeholder="รหัสแผนรับสินค้า" value="{{ $shift->shift_id }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="date">วันที่</label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                placeholder="วันที่" value="{{ $filteredRequest['date'] }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-10 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <input type="text" class="form-control" id="note" name="note"
                                                placeholder="หมายเหตุ" value="{{ $filteredRequest['note'] }}" readonly>
                                        </div>
                                    </div>
                                </article>
                                <hr>
                                <table id="AddReceiptPlanTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            @foreach ($detailHeader as $header)
                                                <th>{{ $header }}</th>
                                            @endforeach
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            @foreach ($detailHeader as $header)
                                                <th>{{ $header }}</th>
                                            @endforeach
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    <input type="hidden" id="filePath" name="filePath" value="{{ $filePath }}">
                                    <button type="submit" class="btn btn-success">บันทึก</button>
                                    <a href="{{ route('CancelAddReceiptPlan', ['filePath' => $filePath]) }}"
                                        class="btn btn-danger">ยกเลิก</a>
                                </div>
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
            $("#AddReceiptPlanTable").DataTable({
                // responsive: true,
                // lengthChange: true,
                // autoWidth: true,
                info: false,
                scrollX: true,
                ordering: true,
                paging: true,
                pageLength: 50,
                lengthMenu: [25, 50, 100],
                order: [],
            });
        });
    </script>
@endsection
