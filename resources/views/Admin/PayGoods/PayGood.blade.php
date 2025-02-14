@extends('layouts.master')

@section('title')
    จ่ายสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>พนักงานขนย้าย</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 id="time"></h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 id="date"></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <ol id="queue-list" class="list-group list-group-numbered" style="max-height: 70vh; overflow-y: auto;">
                        @foreach ($queue as $queue)
                            <li class="list-group-item d-flex justify-content-between align-items-start list-group-item-action"
                                value="{{ $queue->customer_id }}"
                                data-time="{{ (new DateTime($queue->ship_datetime))->format('H:i') }}"
                                style="cursor: pointer;">
                                <div class="ms-2 me-auto">
                                    {{-- <div class="fw-bold">{{ $queue->customer_name }}</div> --}}
                                    {{ $queue->customer_name }}
                                </div>
                                <span
                                    class="badge bg-primary rounded-pill">{{ (new DateTime($queue->ship_datetime))->format('H:i') }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-6 mt-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-6 mb-2">
                                    <label class="text-primary font-weight-bold">ชื่อลูกค้า</label>
                                    <h5 id="text_customer_name"></h5>
                                </div>
                                <div class="col-md-6 col-lg-3 mb-2">
                                    <label class="text-primary font-weight-bold">เวลานัด</label>
                                    <h5 id="text_queue_time"></h5>
                                </div>
                                <div class="col-md-6 col-lg-3 mb-2">
                                    <label class="text-primary font-weight-bold">จำนวนพาเลท</label>
                                    <h5 id="text_total_pallets"></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="PalletTabsTable" class="card text-center"></div>
                </div>
            </div>

            {{-- @if ($customer_queues->isNotEmpty())
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-12 mb-4">
                        <div class="queue-list">
                            @foreach ($customer_queues as $queue)
                                <button class="btn btn-primary mb-2 load-queue-detail"
                                    data-queue-id="{{ $queue->order_number }}">
                                    {{ $queue->customer_name }}
                                    ({{ $queue->queue_time }})
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="order-details">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3 mb-2">
                                            <label class="text-primary font-weight-bold">หมายเลขออเดอร์</label>
                                            <h5>{{ $auto_select_queue['order_number'] ?? 'N/A' }}</h5>
                                        </div>
                                        <div class="col-md-6 col-lg-5 mb-2">
                                            <label class="text-primary font-weight-bold">ชื่อลูกค้า</label>
                                            <h5>{{ $auto_select_queue['customer_name'] ?? 'N/A' }}</h5>
                                        </div>
                                        <div class="col-md-6 col-lg-2 mb-2">
                                            <label class="text-primary font-weight-bold">เวลานัด</label>
                                            <h5>{{ $auto_select_queue['queue_time'] ?? 'N/A' }}</h5>
                                        </div>
                                        <div class="col-md-6 col-lg-2 mb-2">
                                            <label class="text-primary font-weight-bold">จำนวนพาเลท</label>
                                            <h5>{{ $total_pallets ?? 'N/A' }}</h5>
                                        </div>
                                    </div>
                                    <hr>
                                    @if ($pallets_with_products->isNotEmpty())
                                        <div class="tab">
                                            @foreach ($pallets_with_products as $index => $pallet)
                                                <button class="tabLinks btn btn-outline-primary"
                                                    onclick="openTab(event, 'pallet-{{ $index }}')">
                                                    พาเลท {{ $index }}
                                                </button>
                                            @endforeach
                                        </div>
                                        @foreach ($pallets_with_products as $index => $pallet)
                                            <div id="pallet-{{ $index }}" class="tabContent" style="display: none;">
                                                <table class="table table-bordered table-striped pallet nowrap">
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
                                                                <td>{{ $product['product_id'] }}</td>
                                                                <td>{{ $product['product_description'] }}</td>
                                                                <td>{{ $product['quantity'] }}
                                                                    {{ $product['product_um'] }}
                                                                </td>
                                                                <td>{{ $product['quantity2'] }}
                                                                    {{ $product['product_um2'] }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="row">
                                                    @foreach ($teams as $member)
                                                        @if ($member['incentive_id'] && !$member['end_time'])
                                                            <div class="col">
                                                                <form action="{{ route('EndWork') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="products"
                                                                        value="{{ json_encode($pallet['products']) }}">
                                                                    <input type="hidden" name="incentive_id"
                                                                        value="{{ $member['incentive_id'] }}">
                                                                    <input type="hidden" name="order_number"
                                                                        value="{{ $auto_select_queue['order_number'] }}">
                                                                    <button
                                                                        class="btn btn-warning btn-block">{{ $member['name'] }}</button>
                                                                </form>
                                                            </div>
                                                        @elseif (!$member['incentive_id'])
                                                            <div class="col">
                                                                <form action="{{ route('StartWork') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="user_id"
                                                                        value="{{ $member['user_id'] }}">
                                                                    <input type="hidden" name="order_number"
                                                                        value="{{ $auto_select_queue['order_number'] }}">
                                                                    <button
                                                                        class="btn btn-primary btn-block">{{ $member['name'] }}</button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-info" role="alert">
                                            ยังไม่จัดสินค้าในคิวนี้
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    ไม่มีคิวที่ต้องจ่ายสินค้า
                </div>
            @endif --}}
        </div>
    </section>
@endsection

@section('script')
    <script>
        const PalletTable = $("#PalletTable").DataTable({
            // responsive: true,
            // lengthChange: true,
            // autoWidth: true,
            info: false,
            scrollX: true,
            ordering: true,
            paging: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            order: []
        });

        function loadPayGoodsData(customer_id) {
            const CustomerNameElement = document.getElementById('text_customer_name');
            const QueueTimeElement = document.getElementById('text_queue_time');
            const TotalPalletsElement = document.getElementById('text_total_pallets');
            const PalletTabsTable = document.getElementById('PalletTabsTable');

            if (!PalletTabsTable) {
                console.error('Element #PalletTabsTable ไม่พบใน DOM');
                return;
            }

            fetch(`{{ route('PayGoodsData') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        customer_id
                    }),
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    CustomerNameElement.textContent = data.queue?.customer_name || '-';
                    QueueTimeElement.textContent = formatTime(data.queue?.ship_datetime);

                    if (Array.isArray(data.pallet) && data.pallet.length > 0) {
                        TotalPalletsElement.textContent = data.pallet.length;

                        let tabs = `
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            ${data.pallet.map((pallet, index) => `
                                                                                                    <li class="nav-item">
                                                                                                        <a class="nav-link ${index === 0 ? 'active' : ''}" id="tab${index + 1}-tab" data-bs-toggle="tab" href="#tab${index + 1}">
                                                                                                            พาเลท ${index + 1}
                                                                                                        </a>
                                                                                                    </li>
                                                                                                `).join('')}
                        </ul>
                    </div>
                `;

                        let tabContent = `
                    <div class="card-body tab-content">
                    ${data.pallet.map((pallet, index) => `
                                                                                            <div class="tab-pane fade ${index === 0 ? 'show active' : ''}" id="tab${index + 1}" role="tabpanel" aria-labelledby="tab${index + 1}-tab">
                                                                                                <table id="PalletTable${index + 1}" class="table table-bordered table-striped nowrap">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <th>รหัสสินค้า</th>
                                                                                                            <th>รายละเอียดสินค้า</th>
                                                                                                            <th>จำนวน</th>
                                                                                                            <th>จำนวน 2</th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                    ${Array.isArray(pallet.products) ? pallet.products.map(product => `
                                    <tr>
                                        <td>${product.product_code || '-'}</td>
                                        <td>${product.name || '-'}</td>
                                        <td>${product.quantity || '0'}</td>
                                        <td>${product.quantity_2 || '0'}</td>
                                    </tr>
                                `).join('') : ''}
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            `).join('')}
                    </div>
                `;

                        PalletTabsTable.innerHTML = tabs + tabContent;
                    } else {
                        TotalPalletsElement.textContent = '0';
                        PalletTabsTable.innerHTML = `<div class="mt-3 mb-3">ยังไม่จัดสินค้าในคิวนี้</div>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function selectClosestQueue() {
            let queueItems = document.querySelectorAll("#queue-list li");
            let now = new Date();
            let closestItem = null;
            let minDiff = Infinity;

            queueItems.forEach((item) => {
                let timeString = item.getAttribute("data-time");
                let [hours, minutes] = timeString.split(":").map(Number);
                let itemTime = new Date();
                itemTime.setHours(hours, minutes, 0, 0);

                let diff = itemTime - now;

                if (diff > 0 && diff < minDiff) {
                    minDiff = diff;
                    closestItem = item;
                }
            });

            if (closestItem) {
                queueItems.forEach((i) => i.classList.remove("active"));
                closestItem.classList.add("active");

                let customer_id = closestItem.getAttribute("value");
                loadPayGoodsData(customer_id);

                closestItem.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });
            }
        }

        function setupQueueClickEvents() {
            let queueItems = document.querySelectorAll("#queue-list li");

            queueItems.forEach((item) => {
                item.addEventListener("click", function() {
                    queueItems.forEach((i) => i.classList.remove("active"));
                    item.classList.add("active");

                    let customer_id = item.getAttribute("value");
                    loadPayGoodsData(customer_id);

                    item.scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });
                });
            });
        }

        function formatDate(date) {
            const d = new Date(date);
            return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`;
        }

        function formatTime(datetime) {
            if (!datetime) return "-";
            const d = new Date(datetime);
            if (isNaN(d.getTime())) return "-";

            const hours = d.getHours().toString().padStart(2, '0');
            const minutes = d.getMinutes().toString().padStart(2, '0');
            return `${hours}:${minutes}`;
        }

        document.addEventListener("DOMContentLoaded", function() {
            selectClosestQueue();
            setupQueueClickEvents();
        });

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
        updateDateTime();
    </script>
@endsection
