@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง : สลิป
@endsection

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header d-flex justify-content-start">
            <a class="btn btn-primary" href="{{route('Add item')}}">เพิ่มข้อมูลสินค้า</a>
        </div>
        <div class="card-body">
            <table id="slip_per_date" class="table table-striped">
                <thead>
                    <tr>
                        <th>สลิปใบที่ No.</th>
                        <th>หน่วยงาน</th>
                        <th>Production Checker</th>
                        <th>Domestic Checker</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>DMT</td>
                        <td>นาย A</td>
                        <td>ไม่มี</td>
                        <td>
                            <a class="btn btn-primary" href="{{route('Manage item')}}">ดู</a>
                            {{-- <a class="btn btn-danger" href="">ลบ</a> --}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
    
@endsection