@extends('layouts.master')

@section('title')
    จ่ายสินค้า
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
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

            @if ($customer_queues->isNotEmpty())
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
                                    {{-- <div class="row">
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
                                    @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    ไม่มีคิวที่ต้องจ่ายสินค้า
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    {{-- <script>
        $(document).ready(function() {
            // Load queue details when clicking the button
            $('.load-queue-detail').on('click', function() {
                $('#order-details').html(' '); // Clear previous details
                let queueId = $(this).data('queue-id');

                $.ajax({
                    url: '{{ route('SelectPayGoods') }}',
                    method: 'GET',
                    data: {
                        id: queueId
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.select_queue) {
                            let orderDetails = `
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
                            <hr>
                        `;

                            if (response.pallets_with_products.length > 0) {
                                orderDetails += `<div class="tab">`;

                                // Generate pallet tabs dynamically
                                response.pallets_with_products.forEach((pallet, index) => {
                                    orderDetails += `
                                    <button class="tabLinks btn btn-outline-primary"
                                            onclick="openTab(event, 'pallet-${index}')">
                                            พาเลท ${index + 1}
                                    </button>
                                `;
                                });

                                orderDetails += `</div>`;

                                // Generate tab contents
                                response.pallets_with_products.forEach((pallet, index) => {
                                    orderDetails += `
                                    <div id="pallet-${index}" class="tabContent" style="display: none;">
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
                                `;

                                    pallet.products.forEach(product => {
                                        orderDetails += `
                                        <tr>
                                            <td>${product.product_id}</td>
                                            <td>${product.product_description}</td>
                                            <td>${product.quantity} ${product.product_um}</td>
                                            <td>${product.quantity2} ${product.product_um2}</td>
                                        </tr>
                                    `;
                                    });

                                    orderDetails += `
                                            </tbody>
                                        </table>
                                        <div class="row">
                                `;

                                    // Generate team buttons
                                    response.teams.forEach(member => {
                                        if (member.incentive_id && !member
                                            .end_time) {
                                            orderDetails += `
                                            <div class="col">
                                                <form action="{{ route('EndWork') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="products" value='${JSON.stringify(pallet.products)}'>
                                                    <input type="hidden" name="incentive_id" value="${member.incentive_id}">
                                                    <input type="hidden" name="order_number" value="${response.select_queue.order_number}">
                                                    <button class="btn btn-warning btn-block">${member.name}</button>
                                                </form>
                                            </div>
                                        `;
                                        } else if (!member.incentive_id) {
                                            orderDetails += `
                                            <div class="col">
                                                <form action="{{ route('StartWork') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="${member.user_id}">
                                                    <input type="hidden" name="order_number" value="${response.select_queue.order_number}">
                                                    <button class="btn btn-primary btn-block">${member.name}</button>
                                                </form>
                                            </div>
                                        `;
                                        }
                                    });

                                    orderDetails += `
                                        </div>
                                    </div>
                                `;
                                });
                            } else {
                                orderDetails += `
                                        <div class="alert alert-info" role="alert">
                                            ยังไม่จัดสินค้าในคิวนี้
                                        </div>
                                `;
                            }

                            $('#order-details').html(orderDetails);

                            // Automatically activate the first tab
                            const firstTab = document.querySelector(".tabLinks");
                            if (firstTab) {
                                firstTab.click();
                            }
                        } else {
                            alert('ไม่พบข้อมูล');
                        }
                    },
                    error: function() {
                        alert('ไม่สามารถโหลดข้อมูลได้');
                    }
                });
            });

            // Tab switching functionality
            window.openTab = function(evt, palletNo) {
                const tabContent = document.getElementsByClassName("tabContent");
                for (let i = 0; i < tabContent.length; i++) {
                    tabContent[i].style.display = "none";
                }

                const tabLinks = document.getElementsByClassName("tabLinks");
                for (let i = 0; i < tabLinks.length; i++) {
                    tabLinks[i].className = tabLinks[i].className.replace(" active", "");
                }

                document.getElementById(palletNo).style.display = "block";
                evt.currentTarget.className += " active";
            };
        });
    </script> --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Load queue details when clicking the button
            document.querySelectorAll('.load-queue-detail').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('order-details').innerHTML =
                        ''; // Clear previous details
                    let queueId = $(this).data('queue-id');

                    fetch('{{ route('SelectPayGoods') }}', {
                            method: 'POST', // เปลี่ยนเป็น POST
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF Token
                            },
                            body: JSON.stringify({
                                queueId: queueId // ส่งข้อมูลในรูป JSON
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(response => {
                            console.log(response);
                            if (response.select_queue) {
                                let orderDetails = `
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
                            <hr>
                            `;

                                if (response.pallets_with_products.length > 0) {
                                    orderDetails += `<div class="tab">`;

                                    // Generate pallet tabs dynamically
                                    response.pallets_with_products.forEach((pallet, index) => {
                                        orderDetails += `
                                    <button class="tabLinks btn btn-outline-primary"
                                            onclick="openTab(event, 'pallet-${index}')">
                                            พาเลท ${index + 1}
                                    </button>
                                `;
                                    });

                                    orderDetails += `</div>`;

                                    // Generate tab contents
                                    response.pallets_with_products.forEach((pallet, index) => {
                                        orderDetails += `
                                    <div id="pallet-${index}" class="tabContent" style="display: none;">
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
                                    `;

                                        pallet.products.forEach(product => {
                                            orderDetails += `
                                        <tr>
                                            <td>${product.product_id}</td>
                                            <td>${product.product_description}</td>
                                            <td>${product.quantity} ${product.product_um}</td>
                                            <td>${product.quantity2} ${product.product_um2}</td>
                                        </tr>
                                    `;
                                        });

                                        orderDetails += `
                                            </tbody>
                                        </table>
                                        <div class="row">
                                    `;

                                        // Generate team buttons
                                        response.teams.forEach(member => {
                                            if (member.incentive_id && !member
                                                .end_time) {
                                                orderDetails += `
                                            <div class="col">
                                                <form action="{{ route('EndWork') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="products" value='${JSON.stringify(pallet.products)}'>
                                                    <input type="hidden" name="incentive_id" value="${member.incentive_id}">
                                                    <input type="hidden" name="order_number" value="${response.select_queue.order_number}">
                                                    <button class="btn btn-warning btn-block">${member.name}</button>
                                                </form>
                                            </div>
                                        `;
                                            } else if (!member.incentive_id) {
                                                orderDetails += `
                                            <div class="col">
                                                <form action="{{ route('StartWork') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="${member.user_id}">
                                                    <input type="hidden" name="order_number" value="${response.select_queue.order_number}">
                                                    <button class="btn btn-primary btn-block">${member.name}</button>
                                                </form>
                                            </div>
                                        `;
                                            }
                                        });

                                        orderDetails += `
                                        </div>
                                    </div>
                                `;
                                    });
                                } else {
                                    orderDetails += `
                                        <div class="alert alert-info" role="alert">
                                            ยังไม่จัดสินค้าในคิวนี้
                                        </div>
                                `;
                                }

                                document.getElementById('order-details').innerHTML =
                                    orderDetails;

                                // Automatically activate the first tab
                                const firstTab = document.querySelector(".tabLinks");
                                if (firstTab) {
                                    firstTab.click();
                                }
                            } else {
                                alert('ไม่พบข้อมูล');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            alert('ไม่สามารถโหลดข้อมูลได้');
                        });
                });
            });

            // Tab switching functionality
            window.openTab = function(evt, palletNo) {
                const tabContent = document.getElementsByClassName("tabContent");
                for (let i = 0; i < tabContent.length; i++) {
                    tabContent[i].style.display = "none";
                }

                const tabLinks = document.getElementsByClassName("tabLinks");
                for (let i = 0; i < tabLinks.length; i++) {
                    tabLinks[i].className = tabLinks[i].className.replace(" active", "");
                }

                document.getElementById(palletNo).style.display = "block";
                evt.currentTarget.className += " active";
            };
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
            // responsive: true,
            // lengthChange: true,
            // autoWidth: true,
            ordering: true,
            pageLength: 25,
            lengthMenu: [25, 50, 100],
            order: []
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

        .tabContent {
            display: none;
        }
    </style>
@endsection
