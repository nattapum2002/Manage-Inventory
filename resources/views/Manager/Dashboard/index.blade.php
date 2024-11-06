@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">การถ่ายโอนสินค้า (product_store)</h3>
                        </div>
                        <div class="card-body">
                            <table id="product_storetable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสสลิปสินค้า</th>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>น้ำหนัก</th>
                                        <th>จํานวน</th>
                                        <th>วันเก็บสินค้า</th>
                                        <th>เวลาเก็บสินค้า</th>
                                        <th>สถานะเช็คสินค้า</th>
                                        <th>หมายเหตุ</th>
                                        <th>product_checker</th>
                                        <th>domestic_checker</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสลิปสินค้า</th>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>น้ำหนัก</th>
                                        <th>จํานวน</th>
                                        <th>วันเก็บสินค้า</th>
                                        <th>เวลาเก็บสินค้า</th>
                                        <th>สถานะเช็คสินค้า</th>
                                        <th>หมายเหตุ</th>
                                        <th>product_checker</th>
                                        <th>domestic_checker</th>
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
