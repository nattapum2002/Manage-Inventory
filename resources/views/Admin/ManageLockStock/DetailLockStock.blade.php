@extends('layouts.master')

@section('title')
    รายละเอียดล็อคสินค้า : {{ $customer_name ?? 'N/A' }}
@endsection

@section('content')
    <section class="content">
        {{-- @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif --}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">รายละเอียดคำสั่งซื้อ (Order detail)</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="order_number">หมายเลขออเดอร์</label>
                                        <input type="text" class="form-control" id="order_number" name="order_number"
                                            placeholder="หมายเลขออเดอร์"
                                            value="{{ $CustomerOrders[0]->order_number ?? 'N/A' }}" disabled>
                                    </div>
                                </div> --}}
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="customer_name">ชื่อลูกค้า</label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                                            placeholder="ชื่อลูกค้า" value="{{ $customer_name ?? 'N/A' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="customer_name">วันที่สั่ง</label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                                            placeholder="วันที่สั่ง"
                                            value="{{ (new DateTime($ORDER_DATE ?? ''))->format('d/m/Y') ?? 'N/A' }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">

                                </div>
                            </div>
                            <hr>
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <span class="nav-link disabled">หมายเลขออเดอร์</span>
                                </li>
                                @foreach ($formatOrders as $index => $orderTabs)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}" data-bs-toggle="tab"
                                            href="#order-detail{{ $index }}">
                                            {{ $orderTabs['order_number'] }}
                                            <i
                                                class="{{ $orderTabs['ship_status'] ? 'bi bi-check2-all text-success' : 'bi bi-x text-danger' }} order-status-icon{{ $orderTabs['order_number'] }}"></i>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>

                            <div class="tab-content">
                                @foreach ($formatOrders as $index => $orderDetails)
                                    <div id="order-detail{{ $index }}"
                                        class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-2 col-md-2 col-lg-2">
                                                <span class="card mt-3 p-1 ">
                                                    สถานะ : <p
                                                        class="text-center {{ $orderDetails['ship_status'] ? 'text-success' : 'text-danger' }} confirm-status{{$orderDetails['order_number']}}">
                                                        {{ $orderDetails['ship_status'] ? 'จัดส่งแล้ว' : 'ยังไม่จัดส่ง' }}
                                                    </p>
                                                </span>
                                            </div>
                                            <div class="col-sm-2 col-md-2 col-lg-2">
                                                <span class="card mt-3 p-1 ">
                                                    วันเข้ารับ : <p
                                                        class="text-center {{ $orderDetails['order_ship_release']['ship_datetime'] ? 'text-primary' : 'text-danger' }}">
                                                        {{ $orderDetails['order_ship_release']['ship_datetime'] ? date('d/m/Y H:i:s', strtotime($orderDetails['order_ship_release']['ship_datetime'])) : 'ยังไม่กำหนด' }}
                                                    </p>
                                                </span>
                                            </div>
                                            <div class="col-sm-2 col-md-2 col-lg-2">
                                                <span class="card mt-3 p-1 ">
                                                    เวลาเข้า : <p
                                                        class="text-center {{ $orderDetails['order_ship_release']['entry_datetime'] ? 'text-primary' : 'text-danger' }}">
                                                        {{ $orderDetails['order_ship_release']['entry_datetime'] ? date('d/m/Y H:i:s', strtotime($orderDetails['order_ship_release']['entry_datetime'])) : 'ยังไม่กำหนด' }}
                                                    </p>
                                                </span>
                                            </div>
                                            <div class="col-sm-2 col-md-2 col-lg-2">
                                                <span class="card mt-3 p-1 ">
                                                    เวลาออก : <p
                                                        class="text-center {{ $orderDetails['order_ship_release']['release_datetime'] ? 'text-primary' : 'text-danger' }}">
                                                        {{ $orderDetails['order_ship_release']['release_datetime'] ? date('d/m/Y H:i:s', strtotime($orderDetails['order_ship_release']['release_datetime'])) : 'ยังไม่กำหนด' }}
                                                    </p>
                                                </span>
                                            </div>

                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-2 col-md-2 col-lg-2">
                                                <button class="btn btn-success btn-success-order"
                                                    data-order-number="{{ $orderDetails['order_number'] }}"
                                                    data-bs-toggle="modal" data-bs-target="#confirm-sending-pallet"
                                                    {{ $orderDetails['ship_status'] ? 'disabled' : '' }}>
                                                    จัดส่งออเดอร์
                                                </button>
                                            </div>
                                        </div>
                                        <table class="table table-bordered display orderTable">
                                            <thead>
                                                <tr>
                                                    <th>รหัสสินค้า</th>
                                                    <th>ชื่อสินค้า</th>
                                                    <th>จำนวน</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orderDetails['order_detail'] as $Order)
                                                    <tr>
                                                        <td>{{ $Order['product_number'] }}</td>
                                                        <td>{{ $Order['product_name'] ?? '' }}</td>
                                                        <td>{{ $Order['quantity1'] ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>

                        </div>

                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- กลุ่มปุ่มใบล็อค -->
                                <div class="input-group text-nowrap" style="max-width: 350px;">
                                    <span class="input-group-text">ใบล็อค</span>
                                    <div class="form-floating">
                                        <select class="form-select" name="" id="pallet-orderNumber"
                                            {{ $palletOrderId->isEmpty() ? 'disabled' : '' }}>
                                            @forelse ($palletOrderId as $OrderId)
                                                <option value="{{ $OrderId }}">{{ $OrderId }}</option>
                                            @empty
                                                <option selected>ไม่มี</option>
                                            @endforelse
                                            <option value="">ทั้งหมด</option>
                                        </select>
                                        <label for="select-order-number">หมายเลขออเดอร์</label>
                                    </div>
                                    <button class="btn btn-warning text-white print-lock-card"
                                        {{ $palletOrderId->isEmpty() ? 'disabled' : '' }}>ปริ้นใบล็อค</button>
                                </div>
                                <!-- ปุ่มจัดใบล็อค -->
                                <a href="{{ route('PreLock', [$CUS_ID, $ORDER_DATE]) }}" class="btn btn-primary">
                                    จัดใบล็อค
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="pallet" class="table table-bordered display pallet-table">
                                <thead>
                                    <tr>
                                        <th>หมายเลข</th>
                                        <th>หมายเลขออเดอร์</th>
                                        <th>ห้อง</th>
                                        <th>ทีมจัด</th>
                                        <th>ประเภท</th>
                                        <th>สถานะ</th>
                                        <th>การจัดส่ง</th>
                                        <th>หมายเหตุ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pallets as $Pallet)
                                        <tr>
                                            <td>{{ $Pallet->pallet_name }}</td>
                                            <td>{{ $Pallet->order_number }}</td>
                                            <td id="warehouse-name">{{ $Pallet->warehouse_id }}</td>
                                            <td>{{ $Pallet->team_name ?? 'ไม่มี' }}</td>
                                            <td id="pallet-type">{{ $Pallet->pallet_type }}</td>
                                            <td>
                                                {!! $Pallet->arrange_pallet_status == 1
                                                    ? '<p class="text-success arrange-status' . $Pallet->id . '">จัดพาเลทแล้ว</p>'
                                                    : '<p class="text-danger arrange-status' . $Pallet->id . '">ยังไม่จัดพาเลท</p>' !!}
                                            </td>
                                            <td>
                                                {!! $Pallet->recipe_status == 1
                                                    ? '<p class="text-success send-status' . $Pallet->id . '">ส่งแล้ว</p>'
                                                    : '<p class="text-danger send-status' . $Pallet->id . '">ยังไม่จัดส่ง</p>' !!}
                                            </td>
                                            <td>{{ $Pallet->note ?? 'N/A' }}</td>
                                            <td>
                                                @if ($Pallet->arrange_pallet_status == false)
                                                    <button class="btn btn-success btn-pallet-arrange"
                                                        data-pallet-id="{{ $Pallet->id }}" data-bs-toggle="tooltip"
                                                        data-bs-title="ยืนยันการจัด">
                                                        <i class="bi bi-cart"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-danger btn-pallet-arrange"
                                                        data-pallet-id="{{ $Pallet->id }}" data-bs-toggle="tooltip"
                                                        data-bs-title="ยกเลิกการจัด">
                                                        <i class="bi bi-cart-check-fill"></i>
                                                    </button>
                                                @endif
                                                @if ($Pallet->recipe_status == false)
                                                    <button class="btn btn-info btn-pallet-send" data-bs-toggle="tooltip"
                                                        data-pallet-id="{{ $Pallet->id }}" data-bs-title="ยืนยันการส่ง">
                                                        <i class="bi bi-truck-flatbed"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning btn-pallet-send" data-bs-toggle="tooltip"
                                                        data-bs-title="ยกเลิกการส่ง" data-pallet-id="{{ $Pallet->id }}">
                                                        <i class="bi bi-truck"></i>
                                                    </button>
                                                @endif

                                                <a href="{{ route('DetailPallets', [$ORDER_DATE, $CUS_ID, $Pallet->id]) }}"
                                                    class="btn btn-primary" data-bs-toggle="tooltip"
                                                    data-bs-title="รายละเอียด"><i class="far fa-file-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>หมายเลข</th>
                                        <th>ห้อง</th>
                                        <th>ทีมจัด</th>
                                        <th>ประเภท</th>
                                        <th>สถานะ</th>
                                        <th>การจัดส่ง</th>
                                        <th>หมายเหตุ</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="9">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    หมายเหตุ : {{ $CustomerOrders[0]->note ?? 'N/A' }}
                                                </div>

                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <section>
        <div class="modal fade" id="confirm-sending-pallet" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ยืนยันการส่งออเดอร์</h5>
                    </div>
                    <div class="modal-body confirm-send-body">
                        <!-- เนื้อหาจะถูกเพิ่มโดย JavaScript -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="button" class="btn btn-primary modal-confirm-sending-order">บันทึก</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.orderTable').DataTable({
                "paging": true, // เปิดใช้งาน Pagination
                "lengthMenu": [7, 10, 15], // กำหนดจำนวนแถวที่แสดง
                "searching": true, // เปิดใช้งานช่องค้นหา
                "ordering": true, // เปิดใช้งานการเรียงลำดับ
                "info": true, // แสดงข้อมูลจำนวนแถว
                "autoWidth": false, // ปิด Auto Width เพื่อให้ตารางดูสมส่วน
                "responsive": true, // ทำให้รองรับหน้าจอขนาดเล็ก
                "language": {
                    "search": "ค้นหา:",
                    "lengthMenu": "แสดง _MENU_ รายการต่อหน้า",
                    "info": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    "paginate": {
                        "first": "หน้าแรก",
                        "last": "หน้าสุดท้าย",
                        "next": "ถัดไป",
                        "previous": "ก่อนหน้า"
                    }
                },
            });

            $(".pallet-table").DataTable({
                // responsive: true,
                lengthChange: true,
                autoWidth: false,
                // scrollX: true,
                order: [
                    [1, 'asc']
                ],
                rowGroup: {
                    dataSrc: 1
                },
                "language": {
                    "search": "ค้นหา:",
                    "lengthMenu": "แสดง _MENU_ รายการต่อหน้า",
                    "info": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    "paginate": {
                        "first": "หน้าแรก",
                        "last": "หน้าสุดท้าย",
                        "next": "ถัดไป",
                        "previous": "ก่อนหน้า"
                    }
                },
            });
        });

        $('.print-lock-card').on('click', () => {
            const order_number = $('#pallet-orderNumber').val();
            let order_date = @json($ORDER_DATE);
            let cusId = @json($CUS_ID);
            let loading = false;

            $.ajax({
                url: '{{ route('LoadLog') }}',
                method: 'GET',
                data: {
                    order_number,
                    order_date,
                    cusId
                },
                xhrFields: {
                    responseType: 'blob' // รับไฟล์เป็น PDF
                },
                beforeSend: function() {
                    loading = true; // ✅ ตั้งค่า loading เป็น true ก่อนเริ่มส่ง request
                    setLoading(); // แสดงสถานะโหลด
                },
                success: function(response) {
                    let blob = new Blob([response], {
                        type: 'application/pdf'
                    });
                    let url = URL.createObjectURL(blob);

                    window.open(url); // หรือเปิดหน้าใหม่
                    loading = false;
                    setLoading();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });

            function setLoading() {
                $('.print-lock-card').attr('disabled', loading);
                $('.print-lock-card').text("กำลังโหลด...");
                if (!loading) {
                    $('.print-lock-card').text("ปริ๊นใบล็อค");
                }
            }


        })
    </script>

    {{-- Update Pallet Status --}}
    <script>
        $(document).on('click', '.btn-pallet-arrange', async function() {
            let palletId = $(this).data('palletId'); // ✅ ใช้ .data() แปลง camelCase อัตโนมัติ
            let button = $(this);
            let query = 1;
            const arrangeStatus = $(`.arrange-status${palletId}`);
            try {
                let response = await updatePalletStatus(palletId, query);
                // console.log(arrangeStatus);
                updataArrangeStatus(arrangeStatus, button);
            } catch (error) {
                console.error('Error:', error);
            }
        });

        $(document).on('click', '.btn-pallet-send', async function() {
            let palletId = $(this).data('palletId');
            let button = $(this);
            let query = 2;
            const sendStatus = $(`.send-status${palletId}`);
            try {
                let response = await updatePalletStatus(palletId, query);
                updateSendStatus(sendStatus, button);
            } catch (error) {
                console.log(error);
            }
        });

        function updateSendStatus(sendStatus, button) {
            if (sendStatus.text() === 'ยังไม่จัดส่ง') {
                sendStatus.removeClass('text-danger').addClass('text-success')
                    .text('ส่งแล้ว')
                button.removeClass('btn-info').addClass('btn-warning').attr('data-bs-title', 'ยกเลิกการส่ง')
                    .html('<i class="bi bi-truck"></i>')
            } else {
                sendStatus.removeClass('text-success').addClass('text-danger')
                    .text('ยังไม่จัดส่ง')
                button.removeClass('btn-warning').addClass('btn-info').attr('data-bs-title', 'ยืนยันการส่ง')
                    .html('<i class="bi bi-truck-flatbed"></i>')
            }


            button.tooltip('dispose').tooltip();
        }

        function updataArrangeStatus(arrangeStatus, button) {
            if (arrangeStatus.text() === 'ยังไม่จัดพาเลท') {
                arrangeStatus.removeClass('text-danger').addClass('text-success').text('จัดพาเลทแล้ว')
                button.removeClass('btn-success').addClass('btn-danger').attr('data-bs-title', 'ยกเลิกการจัด')
                    .html('<i class="bi bi-cart-check-fill"></i>');
            } else {
                arrangeStatus.removeClass('text-success').addClass('text-danger').text('ยังไม่จัดพาเลท')
                button.removeClass('btn-danger').addClass('btn-success').attr('data-bs-title', 'ยืนยันการจัด')
                    .html('<i class="bi bi-cart"></i>');
            }
            button.tooltip('dispose').tooltip();
        }

        function updatePalletStatus(palletId, query) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    'url': '{{ route('updatePalletStatus') }}',
                    'data': {
                        palletId,
                        query
                    },
                    success: (response) => {
                        resolve(response);
                    },
                    error: (error) => {
                        reject(error);
                        console.log(error);
                    }
                })
            });

        }
    </script>
    {{-- Update Pallet Status --}}

    {{-- Update Order Status --}}
    <script>
        $(document).on('click', '.btn-success-order', function() {
            let orderNumber = $(this).data('orderNumber');
            const modalSendOrderBody = $('.confirm-send-body');
            const sendBtn = $(this);

            modalSendOrderBody.text('ยืนยันการจัดส่งออเดอร์หมายเลข :' + ' ' + orderNumber);

            $('.modal-confirm-sending-order').off('click').on('click', async function(){
                let response = await updateOrderConfirmStatus(orderNumber);
                modalSendOrderBody.html(
                    `<p class='text-success'>ยืนยันเสร็จสิ้น</p>`
                );
                updataElementStatusOrder(orderNumber);
                sendBtn.attr('disabled',true);
            });
        });

        function updateOrderConfirmStatus(orderNumber) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('updateOrderConfirmStatus') }}',
                    data:{
                        orderNumber,
                    },
                    beforeSend: () => {
                        $('.confirm-send-body').html(
                            `
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            `
                        );
                    },
                    success: (response) => {
                        resolve(response);
                    },
                    error: (error) => {
                        reject(error);
                    }
                })
            });
        }
        
        function updataElementStatusOrder(orderNumber){
            const orderStatusIcon = $(`.order-status-icon${orderNumber}`);
            const confirmStatus = $(`.confirm-status${orderNumber}`)
            orderStatusIcon.removeClass('bi-x').removeClass('text-danger')
                .addClass('bi-check2-all').addClass('text-success')
            confirmStatus.text('จัดส่งแล้ว').removeClass('text-danger').addClass('text-success');
        }
    </script>
    {{-- Update Order Status --}}
@endsection
