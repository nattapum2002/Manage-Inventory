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
                            <h3 class="card-title">การถ่ายโอนสินค้า (receipt_product)</h3>
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
                                    @foreach ($product_stores as $receipt_product)
                                        <tr>
                                            <td>{{ $receipt_product->product_slip_id ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->product_slip_number ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->product_id ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->department ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->weight ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->amount ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->store_date ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->store_time ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->status ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->comment ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->product_checker ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->domestic_checker ?? 'N/A' }}</td>
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
                            <h3 class="card-title">สินค้าคงคลัง (product_stock)</h3>
                        </div>
                        <div class="card-body">
                            <table id="stocktable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>จํานวน</th>
                                        <th>จํานวน 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stocks as $product_stock)
                                        <tr>
                                            <td>{{ $product_stock->product_id }}</td>
                                            <td>{{ $product_stock->product_description }}</td>
                                            <td>{{ $product_stock->quantity . ' ' . $product_stock->product_um }}</td>
                                            <td>{{ $product_stock->quantity2 . ' ' . $product_stock->product_um2 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>จํานวน</th>
                                        <th>จํานวน 2</th>
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
                                            <td>{{ $customer_order->order_id ?? 'N/A' }}</td>
                                            <td>{{ $customer_order->product_id ?? 'N/A' }}</td>
                                            <td>{{ $customer_order->customer_id ?? 'N/A' }}</td>
                                            <td>{{ $customer_order->order_amount ?? 'N/A' }}</td>
                                            <td>{{ $customer_order->send_amount ?? 'N/A' }}</td>
                                            <td>{{ $customer_order->date ?? 'N/A' }}</td>
                                            <td>{{ $customer_order->packer_id ?? 'N/A' }}</td>
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
                                            <td>{{ $pallet->pallet_id ?? 'N/A' }}</td>
                                            <td>{{ $pallet->order_id ?? 'N/A' }}</td>
                                            <td>{{ $pallet->product_id ?? 'N/A' }}</td>
                                            <td>{{ $pallet->order_amount ?? 'N/A' }}</td>
                                            <td>{{ $pallet->send_amount ?? 'N/A' }}</td>
                                            <td>{{ $pallet->bag_color ?? 'N/A' }}</td>
                                            <td>{{ $pallet->room ?? 'N/A' }}</td>
                                            <td>{{ $pallet->pack_start_time ?? 'N/A' }}</td>
                                            <td>{{ $pallet->pack_end_time ?? 'N/A' }}</td>
                                            <td>{{ $pallet->checker_id ?? 'N/A' }}</td>
                                            <td>{{ $pallet->shift_id ?? 'N/A' }}</td>
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
                                            <td>{{ $customer_queue->no ?? 'N/A' }}</td>
                                            <td>{{ $customer_queue->queue_time ?? 'N/A' }}</td>
                                            <td>{{ $customer_queue->queue_no ?? 'N/A' }}</td>
                                            <td>{{ $customer_queue->entry_time ?? 'N/A' }}</td>
                                            <td>{{ $customer_queue->release_time ?? 'N/A' }}</td>
                                            <td>{{ $customer_queue->customer_id ?? 'N/A' }}</td>
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
