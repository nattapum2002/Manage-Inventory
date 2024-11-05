@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง : รายวัน
@endsection

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header d-flex justify-content-start">
            <a class="btn btn-primary" href="{{route('Add item')}}">เพิ่มข้อมูลสินค้า</a>
        </div>
        <div class="card-body">
            <table id="stock_per_date" class="table table-striped">
                <thead>
                    <tr>
                        <th>วันที่</th>
                        <th>เวลา</th>
                        <th>หน่วยงาน</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2011-04-25</td>
                        <td>12:00:00</td>
                        <td>DMT</td>
                        <td>
                            <a class="btn btn-primary" href="{{route('Manage slip')}}">ดู</a>
                            {{-- <a class="btn btn-danger" href="">ลบ</a> --}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
    
@endsection
