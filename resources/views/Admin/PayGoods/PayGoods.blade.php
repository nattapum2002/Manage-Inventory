@extends('layouts.master')

@section('title')
    จ่ายสินค้า
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- Row for Employee and Date-Time Cards -->
            <div class="row mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12 mb-1">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>พนักงานขนย้าย</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 mb-1">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 id="time"></h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 mb-1">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 id="date"></h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row for Customer Queue and Order Details -->
            <div class="row">
                <!-- Queue List -->
                <div class="col-lg-3 col-md-4 col-sm-12 mb-4">
                    <div class="queue-list">
                        @if ($customer_queues->isNotEmpty())
                            @foreach ($customer_queues as $queue)
                                <button class="btn btn-primary mb-2 load-queue-detail"
                                    data-queue-id="{{ $queue['order_number'] }}">
                                    {{ $queue['customer_name'] }}
                                    ({{ (new DateTime($queue['queue_time']))->format('H:i') }})
                                </button>
                                {{-- <a href="{{ route('SelectPayGoods', ['order_number' => $queue->order_number]) }}"
                                    class="text-decoration-none">
                                    <div class="card mb-2">
                                        <div class="card-body text-center">
                                            <div class="text-primary font-weight-bold">
                                                {{ (new DateTime($queue->queue_time))->format('H:i') }}
                                            </div>
                                            <div>{{ $queue->customer_name }}</div>
                                        </div>
                                    </div>
                                </a> --}}
                            @endforeach
                        @else
                            <div class="card mb-2">
                                <div class="card-body text-center">
                                    <div class="text-primary font-weight-bold">
                                        <div>ไม่มีคิว</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Order Details and Pallet Info -->
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Order Details -->
                            <div id="order-details">
                                <div class="row">
                                    <div class="col-md-6 col-lg-3 mb-2">
                                        <fieldset>
                                            <label class="text-primary font-weight-bold">หมายเลขออเดอร์</label>
                                            <h5>
                                                {{ optional($select_queue)->order_number ? number_format(optional($select_queue)->order_number, 0, '.', '') : (optional($auto_select_queue)->order_number ? number_format(optional($auto_select_queue)->order_number, 0, '.', '') : 'N/A') }}
                                            </h5>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6 col-lg-5 mb-2">
                                        <fieldset>
                                            <label class="text-primary font-weight-bold">ชื่อลูกค้า</label>
                                            <h5>
                                                {{ optional($select_queue)->customer_name ?? (optional($auto_select_queue)->customer_name ?? 'N/A') }}
                                            </h5>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6 col-lg-2 mb-2">
                                        <fieldset>
                                            <label class="text-primary font-weight-bold">เวลานัด</label>
                                            <h5>
                                                {{ optional($select_queue)->queue_time ? \Carbon\Carbon::parse(optional($select_queue)->queue_time)->format('H:i') : (optional($auto_select_queue)->queue_time ? \Carbon\Carbon::parse(optional($auto_select_queue)->queue_time)->format('H:i') : 'N/A') }}
                                            </h5>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6 col-lg-2 mb-2">
                                        <fieldset>
                                            <label class="text-primary font-weight-bold">จำนวนพาเลท</label>
                                            <h5>
                                                {{ $total_pallets ?? 'N/A' }}</h5>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="tab">
                                @foreach ($pallets_with_products as $pallet_no => $pallet)
                                    <button class="tablinks btn btn-outline-primary"
                                        onclick="openTab(event, '{{ $pallet_no }}')">
                                        พาเลท {{ $pallet_no }}
                                    </button>
                                @endforeach
                            </div>

                            @foreach ($pallets_with_products as $pallet_no => $pallet)
                                <div id="{{ $pallet_no }}" class="tabcontent" style="display: none;">
                                    <table class="table table-bordered table-striped pallet">
                                        <thead>
                                            <tr>
                                                <th>รหัสสินค้า</th>
                                                <th>รายละเอียดสินค้า</th>
                                                <th>จำนวน</th>
                                                <th>จำนวน 2</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pallet['products'] as $product)
                                                <tr>
                                                    <td>{{ $product['item_id'] }}</td>
                                                    <td>{{ $product['item_desc1'] }}</td>
                                                    <td>{{ $product['quantity'] . ' ' . $product['item_um'] }}</td>
                                                    <td>{{ $product['quantity2'] . ' ' . $product['item_um2'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        @foreach ($teams as $member)
                                            <div class="col">
                                                <button class="btn btn-success btn-block" disabled>
                                                    {{-- {{ $member->where('user_id', $pallet['towing_staff_id'])->first()->name ?? 'ไม่มีชื่อ' }} --}}
                                                </button>
                                                @if ($member->incentive_id && !$member->end_time)
                                                    <!-- ปุ่มสิ้นสุดงาน (เมื่อเริ่มงานแล้ว) -->
                                                    <form action="{{ route('EndWork') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="products"
                                                            value="{{ $pallet['products'] }}">
                                                        <input type="hidden" name="incentive_id"
                                                            value="{{ session('incentive_id') }}">
                                                        <input type="hidden" name="order_number"
                                                            value="{{ $select_queue->order_number ?? $auto_select_queue->order_number }}">
                                                        <button
                                                            class="btn btn-warning btn-block">{{ $member->name }}</button>
                                                    </form>
                                                @elseif (!$member->incentive_id)
                                                    <!-- ปุ่มเริ่มงาน (เมื่อยังไม่เริ่มงาน) -->
                                                    <form action="{{ route('StartWork') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="user_id"
                                                            value="{{ $member->user_id }}">
                                                        <input type="hidden" name="order_number"
                                                            value="{{ $select_queue->order_number ?? ($auto_select_queue->order_number ?? '') }}">
                                                        <button
                                                            class="btn btn-primary btn-block">{{ $member->name }}</button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.load-queue-detail').on('click', function() {
                let queueId = $(this).data('queue-id');

                $.ajax({
                    url: '{{ route('SelectPayGoods') }}',
                    method: 'GET',
                    data: {
                        id: queueId
                    },
                    success: function(response) {
                        if (response.select_queue) {

                            const orderDetails = `
                            <div class="row">
                                <div class="col-md-6 col-lg-3 mb-2">
                                    <fieldset>
                                        <label class="text-primary font-weight-bold">หมายเลขออเดอร์</label>
                                        <h5>${response.select_queue.order_number}</h5>
                                    </fieldset>
                                </div>
                                <div class="col-md-6 col-lg-5 mb-2">
                                    <fieldset>
                                        <label class="text-primary font-weight-bold">ชื่อลูกค้า</label>
                                        <h5>${response.select_queue.customer_name}</h5>
                                    </fieldset>
                                </div>
                                <div class="col-md-6 col-lg-2 mb-2">
                                    <fieldset>
                                        <label class="text-primary font-weight-bold">เวลานัด</label>
                                        <h5>${response.select_queue.queue_time}</h5>
                                    </fieldset>
                                </div>
                                <div class="col-md-6 col-lg-2 mb-2">
                                    <fieldset>
                                        <label class="text-primary font-weight-bold">จำนวนพาเลท</label>
                                        <h5>${response.select_queue.total_pallets}</h5>
                                    </fieldset>
                                </div>
                            </div>
                        `;

                            $('#order-details').html(orderDetails);
                        } else {
                            alert('ไม่พบข้อมูล');
                        }
                    },
                    error: function() {
                        alert('ไม่สามารถโหลดข้อมูลได้');
                    }
                });
            });
        });
    </script>

    <script>
        function openTab(evt, palletNo) {
            // ซ่อน tabcontent ทั้งหมด
            const tabcontent = document.getElementsByClassName("tabcontent");
            for (let i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // เอา class 'active' ออกจาก tablinks ทั้งหมด
            const tablinks = document.getElementsByClassName("tablinks");
            for (let i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // แสดง tab ที่ถูกเลือก
            document.getElementById(palletNo).style.display = "block";

            // เพิ่ม class 'active' ให้ปุ่มที่ถูกคลิก
            evt.currentTarget.className += " active";
        }

        // เปิดแท็บแรกโดยอัตโนมัติ
        document.addEventListener('DOMContentLoaded', function() {
            const firstTab = document.querySelector(".tablinks");
            if (firstTab) {
                firstTab.click();
            }
        });
    </script>
    <script>
        function updateDateTime() {
            const now = new Date();
            const optionsDate = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                timeZone: 'Asia/Bangkok'
            };
            const optionsTime = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZone: 'Asia/Bangkok'
            };

            document.getElementById('time').innerText = now.toLocaleTimeString('en-GB', optionsTime);
            document.getElementById('date').innerText = now.toLocaleDateString('en-GB', optionsDate);
        }

        setInterval(updateDateTime, 1000);
        updateDateTime(); // Run on page load
    </script>
    <script>
        $('table.pallet').DataTable({
            info: false,
            ordering: false,
            paging: false,
            searching: false,
            responsive: true,
            lengthChange: true,
            autoWidth: true,
        })
    </script>
@endsection

@section('style')
    <style>
        .queue-list {
            overflow-y: scroll;
            max-height: calc(100vh - 270px);

            -ms-overflow-style: none;
            scrollbar-width: none;

            .card:hover {
                cursor: pointer;
                background-color: #f5f5f5;
            }
        }

        .queue-list::-webkit-scrollbar {
            display: none;
        }

        .tab {
            display: flex;
            border-bottom: 1px none;

            button {
                background-color: inherit;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
                transition: 0.3s;
            }

            button.active {
                background-color: #ddd;
            }
        }

        .tabcontent {
            display: none;
        }
    </style>
@endsection
