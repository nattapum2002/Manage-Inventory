@extends('layouts.master')

@section('title')
    เพิ่มพาเลท : {{ $order_number ?? 'ไม่มี' }}
@endsection

@section('content')
@if (session()->has('LockErrorCreate'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{session('LockErrorCreate')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="content">
        <div class="container-fluid">
            {{-- <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="POST">
                                @csrf
                                <article class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-10">
                                        <div class="form-group">
                                            <label for="pallet_id">รหัสพาเลท</label>
                                            <input type="text" class="form-control" id="pallet_id" name="pallet_id"
                                                placeholder="รหัสพาเลท">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-10">
                                        <div class="form-group">
                                            <label for="pallet_no">หมายเลขพาเลท</label>
                                            <input type="text" class="form-control" id="pallet_no" name="pallet_no"
                                                placeholder="หมายเลขพาเลท">
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-2 col-sm-6">
                                        <div class="form-group">
                                            <label for="room">ห้องเก็บ</label>
                                            <select class="form-control" name="room" id="room">
                                                @foreach ($warehouse as $room)
                                            <option {{ $data->warehouse_id == $room->id ? 'selected' : '' }}
                                                value="{{ $room->id }}">
                                                {{ $room->warehouse_name }}
                                            </option>
                                        @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="note">ประเภทใบล็อค</label>
                                            <select class="form-select" name="pallet_type_id" id="pallet_type">
                                                @foreach ($pallet_type as $type)
                                                    <option value="{{$type->id}}" {{$type->pallet_type === 'ทั่วไป' ? 'selected' : ''}}>{{$type->pallet_type}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </article>
                                <hr>
                                <article id="add-products">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="product_id[0]" class="form-label">รหัสสินค้า</label>
                                                <input type="text" class="form-control" id="show_product_id0"
                                                    name="show_product_id[0]" readonly>
                                                <input type="hidden" class="form-control" id="product_id0"
                                                    name="product_id[0]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product_name[0]" class="form-label">รายการ</label>
                                                <input type="text" class="form-control" id="product_name0"
                                                    name="product_name[0]">
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-3 col-sm-4">
                                            <div class="form-group">
                                                <label for="ordered_quantity[0]" class="form-label">สั่งจ่าย</label>
                                                <input type="text" class="form-control mb-3" id="ordered_quantity0"
                                                    name="ordered_quantity[0]" disabled>
                                                <input type="text" class="form-control" id="ordered_quantity2_0"
                                                    name="ordered_quantity2[0]" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-1 col-md-2 col-sm-4">
                                            <div class="form-group">
                                                <label for="quantity[0]" class="form-label">จ่ายจริง</label>
                                                <input type="number" class="form-control mb-3" id="quantity0"
                                                    name="quantity[0]">
                                                <input type="number" class="form-control" id="quantity2_0"
                                                    name="quantity2[0]">
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-4">
                                            <div class="form-group">
                                                <label for="" class="form-label">หน่วย</label>
                                                <input type="text" class="form-control mb-3" id="quantity_um0"
                                                    name="quantityUm[0]" readonly>
                                                <input type="text" class="form-control" id="quantity_um2_0"
                                                    name="quantityUm2_[0]" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-warning mr-3"
                                        id="add-product">เพิ่มช่องสินค้า</button>
                                    <button type="submit" id="submit" class="btn btn-primary">เพิ่มใบล็อค</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <!-- ปุ่ม "ออกใบขายเพิ่ม" (อยู่ซ้าย) -->
                                <div class="col">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-product">
                                        ออกใบขายเพิ่ม
                                    </button>
                                    {{-- @dump(session()->all()) --}}
                                </div>
                        
                                <!-- Dropdown + ปุ่ม "ล้าง" (อยู่ขวาสุด) -->
                                <div class="col-auto text-end d-flex align-items-center gap-2">
                                    <form action="{{route('AutoLock', [$CUS_ID, $ORDER_DATE])}}" method="GET">
                                        <div class="input-group">
                                            <label class="input-group-text" for="select-order-number">
                                                <button type="submit" id="auto-btn" class="btn btn-warning">จัดใบล็อค</button>
                                            </label>
                                            <div class="form-floating">
                                                <select class="form-select w-auto select-order-number" name="order_number" id="select-order-number">
                                                    @foreach ($orders_number as $order)
                                                        <option value="{{ $order->order_number }}">{{ $order->order_number }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="select-order-number">หมายเลขออเดอร์</label>
                                            </div>
                                        </div>  
                                    </form>
                                    
                                    <a href="{{ route('forgetSession', [$CUS_ID, $ORDER_DATE]) }}" class="btn btn-danger">
                                        ล้าง
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <table id="show-pallet" class="table nowrap table-striped table-bordered">
                                <thead>
                                    <th>หมายเลขออเดอร์</th>
                                    <th>ห้อง</th>
                                    <th>ลักษณะงาน</th>
                                    <th>รหัสสินค้า</th>
                                    <th>ชื่อสินค้า</th>
                                    <th>
                                        จำนวน
                                    </th>
                                    <th>ประเภท</th>
                                    <th>#</th>
                                </thead>
                                <tbody>
                                    {{-- @dd(cache('lock' . $CUS_ID . $ORDER_DATE)) --}}
                                    @if (cache('lock' . $CUS_ID . $ORDER_DATE))
                                        @foreach (cache('lock' . $CUS_ID . $ORDER_DATE) as $lockItem)
                                                <tr>
                                                    <td>{{ $lockItem['order_number'] }}</td>
                                                    <td id="warehouse-name">{{ $lockItem['warehouse'] }}</td>
                                                    <td id="work-type">{{ $lockItem['work_type'] ?? '' }}</td>

                                                    <td>
                                                        @foreach ($lockItem['items'] as $item)
                                                            {{ $item['product_number'] }} <br>
                                                        @endforeach
                                                    </td>

                                                    <td>
                                                        @foreach ($lockItem['items'] as $item)
                                                            {{ $item['product_description'] }} <br>
                                                        @endforeach
                                                    </td>

                                                    <td>
                                                        @foreach ($lockItem['items'] as $item)
                                                            {{ $item['quantity'] }} <br>
                                                        @endforeach
                                                    </td>

                                                    <td id="pallet-type">
                                                        {{ $lockItem['pallet_type'] }}
                                                    </td>
                                                    <td></td>
                                                </tr>

                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-center">
                            @if(cache('lock' . $CUS_ID . $ORDER_DATE))
                                <a href="{{ route('Insert_Pallet', [$CUS_ID, $ORDER_DATE]) }}" class="btn btn-success">
                                    บันทึกข้อมูล
                                </a>
                            @else
                                <button class="btn btn-success disabled">บันทึกข้อมูล</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <a type="button" class="btn btn-warning" href="{{ route('DetailLockStock', [$CUS_ID, $ORDER_DATE]) }}">ย้อนกลับ</a>
    </section>

<section class="modal fade" id="modal-add-product" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modalAddProduct" aria-hidden="true">
    <form action="{{ route('addUpSellPallet', [$CUS_ID, $ORDER_DATE]) }}" class=""  method="POST">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddProduct">ขายเพิ่ม</h1>
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body add-more-product">
                    <div class="row">
                        <div class="col-12">
                            {{-- CARD --}}
                            <div class="card">
                                <div class="card-body">
                                    @csrf
                                    <article class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="note">หมายเลขออเดอร์</label>
                                                <select class="form-select" name="order_number" id="order-number">
                                                    @foreach ($orders_number as $order)
                                                        <option value="{{ $order->order_number }}">{{ $order->order_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-6">
                                            <div class="form-group">
                                                <label for="room">ห้องเก็บ</label>
                                                <select class="form-select" name="room" id="room">
                                                    <option value="" selected>เลือกห้อง</option>
                                                    <option value="1">Cold-A</option>
                                                    <option value="2">Cold-C</option>
                                                    <option value="3">Blood</option>
                                                </select>
                                                @error('room')
                                                    <div class="text-red">
                                                        <p class="fs-6">*{{$message}}</p>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="note">ประเภทใบล็อค</label>
                                                <select class="form-select" name="pallet_type_id" id="pallet_type">
                                                    @foreach ($pallet_type as $type)
                                                        <option value="{{ $type->id }}"
                                                            {{ $type->pallet_type === 'ขายเพิ่ม' ? 'selected' : '' }}>
                                                            {{ $type->pallet_type }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-2 col-sm-6">
                                            <div class="form-group">
                                                <label for="room">ลักษณะงาน</label>
                                                <select class="form-select" name="work_desc" id="work_desc">
                                                    <option value="" selected>เลือก</option>
                                                    <option value="1">แยกจ่าย</option>
                                                    <option value="2">รับจัด</option>
                                                    <option value="3">เลือด</option>
                                                </select>
                                                @error('work_desc')
                                                    <div class="text-red">
                                                        <p class="fs-6">*{{$message}}</p>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </article>
                                    <hr>
                                    <article id="add-products">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product_id[0]" class="form-label">รหัสสินค้า</label>
                                                    <input type="text" class="form-control" id="show_product_id0"
                                                        name="show_product_id[0]" readonly>
                                                    <input type="hidden" class="form-control" id="product_id0"
                                                        name="product_id[0]" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product_name[0]" class="form-label">รายการ</label>
                                                    <input type="text" class="form-control product-name"
                                                        id="product_name0" name="product_name[0]">
                                                        @error('product_name.0')
                                                            <div class="text-red">
                                                                <p class="fs-6">*{{$message}}</p>
                                                            </div>
                                                        @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-4">
                                                <div class="form-group">
                                                    <label for="quantity[0]" class="form-label">จ่าย</label>
                                                    <input type="number" class="form-control mb-3" id="quantity0"
                                                        name="quantity[0]">
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-2">
                                                <div class="form-group">
                                                    <label for="" class="form-label">หน่วย</label>
                                                    <input type="text" class="form-control mb-3" value="Kg"
                                                        id="quantity_um0" name="quantityUm[0]" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-1 col-sm-4">
                                                <div class="form-group">
                                                    <label for="" class="form-label">ลักษณะงาน</label>
                                                    <input type="text" class="form-control mb-3  work-desc"
                                                        id="work_type0" name="" readonly>
                                                    <input type="hidden" class="form-control mb-3"
                                                        id="work_type_id0" name="work_type_id[0]" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <div class="d-flex justify-content-center mt-3">
                                        <button type="button" class="btn btn-warning mr-3"
                                            id="add-product">เพิ่มช่องสินค้า</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@section('script')
    <script>
        $('[id^="pallet-type"]').each(function() {
            let pallet_type = $(this).text().trim(); // ดึงค่าจาก td (ลบช่องว่าง)
            // เปลี่ยนค่าตามเงื่อนไข
            if (pallet_type === '1') {
                $(this).text('ทั่วไป');
            } else if (pallet_type === '2') {
                $(this).text('ขายเพิ่ม');
            } else if (pallet_type === '3') {
                $(this).text('รอเติม');
            } else if (pallet_type === '4') {
                $(this).text('ลดจำนวน');
            }
        });
        $(document).ready(function() {
            $('#show-pallet').DataTable({
                info: false,
                scrollX: true,
                ordering: true,
                paging: true,
                order:[[0 ,'asc']],
                rowGroup:{
                    dataSrc: 0
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
        }, );
    </script>
    <script>
        var product_count = 0;
        var $CUS_ID = "{{ $CUS_ID }}";
        var $ORDER_DATE = "{{ $ORDER_DATE }}"
        $('#add-product').click(function() {
            product_count++;
            $('#add-products').append(`
                <hr>
                <div class="row" id="product-${product_count}">
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="product_id[${product_count}]" class="form-label">รหัสสินค้า</label>
                            <input type="text" class="form-control" id="show_product_id${product_count}"
                                name="show_product_id[${product_count}]" readonly>
                            <input type="hidden" class="form-control" id="product_id${product_count}"
                                name="product_id[${product_count}]" readonly>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="product_name[${product_count}]" class="form-label">รายการ</label>
                            <input type="text" class="form-control" id="product_name${product_count}"
                                name="product_name[${product_count}]">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4">
                        <div class="form-group">
                            <label for="quantity[${product_count}]" class="form-label">จ่าย</label>
                            <input type="number" class="form-control mb-3" id="quantity${product_count}"
                                name="quantity[${product_count}]" >
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2">
                        <div class="form-group">
                            <label for="quantity_um${product_count}" class="form-label">หน่วย</label>
                            <input type="text" class="form-control mb-3" value="Kg" id="quantity_um${product_count}"
                                name="quantityUm[${product_count}]" readonly>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-1 col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-label">ลักษณะงาน</label>
                                <input type="text" class="form-control mb-3  work-desc"
                                    id="work_type${product_count}" name="" readonly>
                                <input type="hidden" class="form-control mb-3"
                                    id="work_type_id${product_count}" name="work_type_id[${product_count}]" readonly>
                        </div>
                    </div>
                     <div class="col-lg-1 col-md-2 col-sm-1">
                        <label for="remove-product" class="form-label">#</label>
                        <div class="form-group">
                        <button type="button" class="btn btn-danger remove-product">ลบ</button>
                    </div>
                     
                </div>
               
            `);
            // เรียกใช้งาน autocomplete กับฟิลด์ที่เพิ่มใหม่
            initializePalletAutocomplete(`#product_name${product_count}`,
                `#product_id${product_count}`,
                `#show_product_id${product_count}`,
                `#work_type${product_count}`
            );
        });

        // ฟังก์ชันสำหรับลบแถว
        $(document).on('click', '.remove-product', function() {
            $(this).closest('[id^="product-"]').remove();
        });

        // ฟังก์ชันสำหรับ autocomplete
        function initializePalletAutocomplete(product_nameSelector, product_idSelector, show_product_idSelector,work_typeSelector,work_type_idSelector) {
            $(product_nameSelector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('AutoCompleteAddPallet') }}",
                        data: {
                            query: request.term,
                        },
                        success: function(data) {
                            response(data); // ส่งข้อมูลผลลัพธ์ไปยัง autocomplete
                        },
                        error: function(xhr, status, error) {
                            alert('มีข้อผิดพลาดในการบันทึกข้อมูล');
                            console.log(xhr.responseText);
                        }
                    });
                },
                appendTo: ".add-more-product",
                minLength: 0,
                select: function(event, ui) {
                    // เมื่อเลือกสินค้า ให้เติมรหัสสินค้าในฟิลด์ item_id
                    $(product_idSelector).val(ui.item.product_id); // เติมรหัสสินค้าในช่องรหัสสินค้า
                    $(show_product_idSelector).val(ui.item.product_no);
                    $(product_nameSelector).val(ui.item.product_name); // เติมชื่อสินค้าในช่องชื่อสินค้า
                    $(work_typeSelector).val(ui.item.item_work_desc);
                    $(work_type_idSelector).val(ui.item.item_work_desc_id);
                }
            });
            $(product_nameSelector).focus(function() {
                $(this).autocomplete('search', ''); // ส่งค่าว่างเพื่อแสดง autocomplete ทันที
            });
        }
        initializePalletAutocomplete(`#product_name0`, `#product_id0`,
            `#show_product_id0`,`#work_type0`,`#work_type_id0`);
    </script>
@endsection
