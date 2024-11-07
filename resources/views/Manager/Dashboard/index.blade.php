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
                                        <th>หมายเลขสลิป</th>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>น้ำหนัก</th>
                                        <th>จํานวน</th>
                                        <th>วันเก็บสินค้า</th>
                                        <th>เวลาเก็บสินค้า</th>
                                        <th>สถานะเช็คสินค้า</th>
                                        <th>หมายเหตุ</th>
                                        <th>product checker</th>
                                        <th>domestic checker</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product_stores as $product_store)
                                        <tr>
                                            <td>{{ $product_store->product_slip_id }}</td>
                                            <td>{{ $product_store->product_slip_number }}</td>
                                            <td>{{ $product_store->product_id }}</td>
                                            <td>{{ $product_store->department }}</td>
                                            <td>{{ $product_store->weight }}</td>
                                            <td>{{ $product_store->amount }}</td>
                                            <td>{{ $product_store->store_date }}</td>
                                            <td>{{ $product_store->store_time }}</td>
                                            <td>{{ $product_store->check_status }}</td>
                                            <td>{{ $product_store->comment }}</td>
                                            <td>{{ $product_store->product_checker }}</td>
                                            <td>{{ $product_store->domestic_checker }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสลิปสินค้า</th>
                                        <th>หมายเลขสลิป</th>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>น้ำหนัก</th>
                                        <th>จํานวน</th>
                                        <th>วันเก็บสินค้า</th>
                                        <th>เวลาเก็บสินค้า</th>
                                        <th>สถานะเช็คสินค้า</th>
                                        <th>หมายเหตุ</th>
                                        <th>product checker</th>
                                        <th>domestic checker</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">สินค้าคงคลัง (stock)</h3>
                        </div>
                        <div class="card-body">
                            <table id="stocktable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>น้ำหนัก</th>
                                        <th>จํานวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stocks as $stock)
                                        <tr>
                                            <td>{{ $stock->product_id }}</td>
                                            <td>{{ $stock->product_name }}</td>
                                            <td>{{ $stock->weight }}</td>
                                            <td>{{ $stock->amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>น้ำหนัก</th>
                                        <th>จํานวน</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">คำสั่งซื้อ (customer_order)</h3>
                        </div>
                        <div class="card-body">
                            <table id="customer_ordertable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>รหัสสินค้า</th>
                                        <th>รหัสลูกค้า</th>
                                        <th>จํานวนที่สั่ง</th>
                                        <th>จํานวนที่ส่ง</th>
                                        <th>วันที่</th>
                                        <th>ผู้แพ็คสินค้า</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_orders as $customer_order)
                                        <tr>
                                            <td>{{ $customer_order->order_id }}</td>
                                            <td>{{ $customer_order->product_id }}</td>
                                            <td>{{ $customer_order->customer_id }}</td>
                                            <td>{{ $customer_order->order_amount }}</td>
                                            <td>{{ $customer_order->send_amount }}</td>
                                            <td>{{ $customer_order->date }}</td>
                                            <td>{{ $customer_order->packer_id }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>รหัสสินค้า</th>
                                        <th>รหัสลูกค้า</th>
                                        <th>จํานวนที่สั่ง</th>
                                        <th>จํานวนที่ส่ง</th>
                                        <th>วันที่</th>
                                        <th>ผู้แพ็คสินค้า</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">พาเลท (pallets)</h3>
                        </div>
                        <div class="card-body">
                            <table id="pallettable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสพาเลท</th>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>รหัสสินค้า</th>
                                        <th>จํานวนที่สั่ง</th>
                                        <th>จํานวนที่ส่ง</th>
                                        <th>สีถุง</th>
                                        <th>ห้อง</th>
                                        <th>เวลาเริ่มแพ็คสินค้า</th>
                                        <th>เวลาสิ้นสุดแพ็คสินค้า</th>
                                        <th>ผู้เช็ค</th>
                                        <th>กะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pallets as $pallet)
                                        <tr>
                                            <td>{{ $pallet->pallet_id }}</td>
                                            <td>{{ $pallet->order_id }}</td>
                                            <td>{{ $pallet->product_id }}</td>
                                            <td>{{ $pallet->order_amount }}</td>
                                            <td>{{ $pallet->send_amount }}</td>
                                            <td>{{ $pallet->bag_color }}</td>
                                            <td>{{ $pallet->room }}</td>
                                            <td>{{ $pallet->pack_start_time }}</td>
                                            <td>{{ $pallet->pack_end_time }}</td>
                                            <td>{{ $pallet->checker_id }}</td>
                                            <td>{{ $pallet->shift_id }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสพาเลท</th>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>รหัสสินค้า</th>
                                        <th>จํานวนที่สั่ง</th>
                                        <th>จํานวนที่ส่ง</th>
                                        <th>สีถุง</th>
                                        <th>ห้อง</th>
                                        <th>เวลาเริ่มแพ็คสินค้า</th>
                                        <th>เวลาสิ้นสุดแพ็คสินค้า</th>
                                        <th>ผู้เช็ค</th>
                                        <th>กะ</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">คิวลูกค้า (customer_queue)</h3>
                        </div>
                        <div class="card-body">
                            <table id="CustomerQueuetable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสคิว</th>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>ลำดับคิว</th>
                                        <th>เวลารับ</th>
                                        <th>เวลารับจริง</th>
                                        <th>สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_queues as $customer_queue)
                                        <tr>
                                            <td>{{ $customer_queue->queue_id }}</td>
                                            <td>{{ $customer_queue->order_id }}</td>
                                            <td>{{ $customer_queue->queue }}</td>
                                            <td>{{ $customer_queue->time_queue }}</td>
                                            <td>{{ $customer_queue->recive_time }}</td>
                                            <td>{{ $customer_queue->recive_status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสคิว</th>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>ลำดับคิว</th>
                                        <th>เวลารับ</th>
                                        <th>เวลารับจริง</th>
                                        <th>สถานะ</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
