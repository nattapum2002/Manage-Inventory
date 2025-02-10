@extends('layouts.master')

@section('title')
    สินค้าคงคลัง
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
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

        const ProductStocksTable = initDataTable("#ProductStocksTable");
    </script>
@endsection
