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
                            <h3 class="card-title">การรับสินค้า (receipt_product)</h3>
                        </div>
                        <div class="card-body">
                            <table id="ReceiptProductTable" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>จํานวน</th>
                                        <th>วันรับสินค้า</th>
                                        <th>เวลารับสินค้า</th>
                                        <th>product checker</th>
                                        <th>domestic checker</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product_stores as $receipt_product)
                                        <tr>
                                            <td>{{ $receipt_product->product_number ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->product_description ?? 'N/A' }}</td>
                                            <td>
                                                <p class="text-primary text-bold">
                                                    {{ $receipt_product->quantity . ' ' . $receipt_product->product_um }}
                                                </p>
                                                <span
                                                    class="text-secondary text-bold">{{ $receipt_product->quantity2 . ' ' . $receipt_product->product_um2 }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ (new DateTime($receipt_product->store_datetime))->format('d/m/Y') ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ (new DateTime($receipt_product->store_datetime))->format('H:i') ?? 'N/A' }}
                                            </td>
                                            <td>{{ $receipt_product->product_checker_id ?? 'N/A' }}</td>
                                            <td>{{ $receipt_product->domestic_checker_id ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>จํานวน</th>
                                        <th>วันรับสินค้า</th>
                                        <th>เวลารับสินค้า</th>
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
                            <table id="ProductStocksTable" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>จํานวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stocks as $product_stock)
                                        <tr>
                                            <td>{{ $product_stock->product_id }}</td>
                                            <td>{{ $product_stock->product_description }}</td>
                                            <td>
                                                <p class="text-primary text-bold">
                                                    {{ $product_stock->quantity . ' ' . $product_stock->product_um }}
                                                </p>
                                                <span
                                                    class="text-secondary text-bold">{{ $product_stock->quantity2 . ' ' . $product_stock->product_um2 }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
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
                            <h3 class="card-title">คำสั่งซื้อ (orders)</h3>
                        </div>
                        <div class="card-body">
                            <table id="OrdersTable" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>รหัสลูกค้า</th>
                                        <th>ชื่อลูกค้า</th>
                                        <th>วันที่สั่ง</th>
                                        <th>วันนัดรับ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_orders as $customer_order)
                                        <tr>
                                            <td>{{ $customer_order->order_number ?? 'N/A' }}</td>
                                            <td>{{ $customer_order->customer_id ?? 'N/A' }}</td>
                                            <td>{{ $customer_order->customer_name ?? 'N/A' }}</td>
                                            <td>{{ (new DateTime($customer_order->order_date))->format('d/m/Y') ?? 'N/A' }}
                                            </td>
                                            <td>{{ (new DateTime($customer_order->ship_datetime))->format('d/m/Y') ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสคำสั่งซื้อ</th>
                                        <th>รหัสลูกค้า</th>
                                        <th>ชื่อลูกค้า</th>
                                        <th>วันที่สั่ง</th>
                                        <th>วันนัดรับ</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('script')
    <script>
        function initDataTable(selector) {
            return $(selector).DataTable({
                info: false,
                scrollX: true,
                ordering: true,
                paging: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50],
                order: [
                    // [0, 'desc']
                ],
            });
        }

        const ReceiptProductTable = initDataTable("#ReceiptProductTable");
        const ProductStocksTable = initDataTable("#ProductStocksTable");
        const OrdersTable = initDataTable("#OrdersTable");
    </script>
@endsection
