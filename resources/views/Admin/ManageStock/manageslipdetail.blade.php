@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง : รายละเอียดสินค้า
@endsection

@section('content')
<section class="content"> 
    <div class="card">
        <div class="card-header d-flex justify-content-start">
            <a class="btn btn-primary" href="{{route('Add item')}}">เพิ่มข้อมูลสินค้า</a>
        </div>
        <div class="card-body">
            <table id="item_per_slip" class="table table-striped">
                <thead>
                    <tr>
                        <th>รหัสสินค้า</th>
                        <th>รายละเอียดสินค้า</th>
                        <th>จำนวนถุง</th>
                        <th>น้ำหนัก</th>
                        <th>หมายเหตุ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>123456</td>
                        <td>ไก่ชายโสด</td>
                        <td>25</td>
                        <td>250</td>
                        <td>14000</td>
                        <td>
                            <a class="btn btn-primary" href="">แก้ไข</a>
                            <a class="btn btn-danger" href="">ลบ</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="card-footer">
                Production Checker :<strong> นาย A </strong>
                Domestic Checker :<strong> นาย B</strong><br>
                <strong>Date : 12/06/2564 </strong>
                <strong>Time : 12:00:00 </strong>
            </div>
        </div>
    </div>
</section>
    
<script>
    new DataTable('#item_per_slip', {
        order: [[3, 'desc']]
    });
</script>
@endsection