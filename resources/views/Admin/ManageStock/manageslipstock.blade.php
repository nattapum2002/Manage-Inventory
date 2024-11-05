@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>จัดการผลิตภัณฑ์จากคลัง</h3>
            <a class="btn btn-primary" href="">เพิ่มข้อมูลสินค้า</a>
        </div>
        <div class="card-body">
            <table id="slip_per_date" class="table table-striped">
                <thead>
                    <tr>
                        <th>วันที่</th>
                        <th>หน่วยงาน</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2011-04-25</td>
                        <td>DMT</td>
                        <td>
                            <a class="btn btn-primary" href="">ดู</a>
                            {{-- <a class="btn btn-danger" href="">ลบ</a> --}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<script>
    $(document).ready(function() {
        $('#slip_per_date').DataTable();
    });
</script>
@endsection