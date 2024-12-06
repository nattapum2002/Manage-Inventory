@extends('layouts.master')

@section('title')
    จัดการกะและทีมพนักงาน
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('SaveAddShift') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h5>เพิ่มกะ</h5>
                            </div>
                            <div class="card-body">
                                <article class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="date" class="form-label">วันที่</label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                value="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_name">ชื่อกะพนักงาน</label>
                                            <select class="form-control" id="shift_name" name="shift_name">
                                                <option selected value="">เลือกชื่อกะพนักงาน</option>
                                                @foreach ($filtered_shifts as $shift)
                                                    <option value="{{ $shift['select_name'] }}">{{ $shift['select_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="start_shift">เวลาเริ่มกะ</label>
                                            <input type="time" class="form-control" id="start_shift" name="start_shift"
                                                placeholder="เวลาเริ่มกะ"
                                                value="{{ now()->format('H:i') > '12:00' ? '19:00' : '07:00' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="end_shift">เวลาเลิกกะ</label>
                                            <input type="time" class="form-control" id="end_shift" name="end_shift"
                                                placeholder="เวลาเลิกกะ"
                                                value="{{ now()->format('H:i') > '12:00' ? '07:00' : '19:00' }}">
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
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success">เพิ่มกะ</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5>กะ</h5>
                                <div>
                                    <form action="{{ route('ShiftFilterDate') }}" method="post">
                                        @csrf
                                        <div class="input-group">
                                            <input type="date" class="form-control" name="date" id="date"
                                                value="{{ $ShiftFilterDate->first()->date ?? now()->format('Y-m-d') }}">
                                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="ShiftTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>วันที่</th>
                                        <th>ชื่อกะ</th>
                                        <th>เวลาเริ่มกะ</th>
                                        <th>เวลาเลิกกะ</th>
                                        <th>หมายเหตุ</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ShiftFilterDate as $shift)
                                        <tr>
                                            <td>{{ (new DateTime($shift->date))->format('d/m/Y') }}</td>
                                            <td>{{ $shift->shift_name }}</td>
                                            <td>{{ (new DateTime($shift->start_shift))->format('H:i') }}</td>
                                            <td>{{ (new DateTime($shift->end_shift))->format('H:i') }}</td>
                                            <td>{{ $shift->note }}</td>
                                            <td>
                                                <a href="{{ Route('EditShiftTeam', $shift->shift_id) }}"
                                                    class="btn btn-primary"><i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>วันที่</th>
                                        <th>ชื่อกะ</th>
                                        <th>เวลาเริ่มกะ</th>
                                        <th>เวลาเลิกกะ</th>
                                        <th>หมายเหตุ</th>
                                        <th>จัดการ</th>
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

@section('script')
    <script>
        $("#ShiftTable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            // scrollX: true,
            // layout: {
            //     topStart: {
            //         buttons: [
            //             'copy', 'excel', 'pdf'
            //         ]
            //     }
            // }
        });
    </script>
@endsection
