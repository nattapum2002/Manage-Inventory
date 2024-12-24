@extends('layouts.master')

@section('title')
    เพิ่มพาเลท : {{ $order_number ?? 'ไม่มี' }}
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('SavePallet', ['order_number' => $order_number]) }}" method="POST">
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
                                    <div class="col-lg-1 col-md-2 col-sm-10">
                                        <div class="form-group">
                                            <label for="room">ห้องเก็บ</label>
                                            <select class="form-control" name="room" id="room">
                                                <option value="" selected>เลือกห้อง</option>
                                                <option value="Cold-A">Cold-A</option>
                                                <option value="Cold-C">Cold-C</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="room">ทีมจัดพาเลท</label>
                                            <input type="text" class="form-control" id="team" name="">
                                            <input type="hidden" class="form-control" id="team-id" name="team_id">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <select class="form-select" name="pallet_type_id" id="pallet_type">
                                                @foreach ($pallet_type as $type )
                                                    <option value="{{$type->id}}" {{$type->pallet_type === 'ทั่วไป' ? 'selected' : ''}}>{{$type->pallet_type}}</option>
                                                @endforeach
                                               
                                            </select>
                                        </div>
                                    </div>
                                </article>
                                <hr>
                                <article id="add-products">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="product_id[0]" class="form-label">รหัสสินค้า</label>
                                                <input type="text" class="form-control" id="show_product_id0"
                                                    name="show_product_id[0]" readonly>
                                                <input type="hidden" class="form-control" id="product_id0"
                                                    name="product_id[0]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-7">
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

                                        <div class="col-lg-2 col-md-3 col-sm-4">
                                            <div class="form-group">
                                                <label for="quantity[0]" class="form-label">จ่ายจริง</label>
                                                <input type="number" class="form-control mb-3" id="quantity0"
                                                    name="quantity[0]">
                                                <input type="number" class="form-control" id="quantity2_0"
                                                    name="quantity2[0]">
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-3">
                                            <div class="form-group">
                                                <label for="" class="form-label">หน่วย</label>
                                                <input type="text" class="form-control mb-3" id="quantity_um0"
                                                    name="quantityUm[0]" readonly>
                                                <input type="text" class="form-control" id="quantity_um2_0"
                                                    name="quantityUm2_[0]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="bag_color[0]" class="form-label">สีถุง</label>
                                                <input type="text" class="form-control" id="bag_color0"
                                                    name="bag_color[0]" disabled>
                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="note[0]" class="form-label">หมายเหตุ</label>
                                                <input type="text" class="form-control" id="note0" name="note[0]"
                                                    disabled>
                                            </div>
                                        </div> --}}
                                        <div class="col-1">
                                            <label for="remove-product" class="form-label">#</label>
                                        </div>
                                    </div>
                                </article>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-warning mr-3"
                                        id="add-product">เพิ่มช่องสินค้า</button>
                                    <button type="submit" id="submit" class="btn btn-primary">สร้างพาเลท</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card col-12">
                <div class="card-header text-end">
                    <a type="button" href="{{ route('forgetSession') }}" class="btn btn-danger ">ล้าง</a>
                </div>
                <div class="card-body">
                    <table id="show-pallet" class="table nowrap">
                        <thead>
                            <th>รหัสพาเลท</th>
                            <th>หมายเลขพาเลท</th>
                            <th>ห้อง</th>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>จํานวน</th>
                            <th>ประเภท</th>
                            <th>หมายเหตุ</th>
                            <th>#</th>
                        </thead>
                        <tbody>
                            {{-- @dd(session('pallet')) --}}
                            @if (session('pallet'))
                                @foreach (session('pallet') as $key => $pallet)
                                    <tr>
                                        <td>{{ $pallet['pallet_id'] }}</td>
                                        <td>{{ $pallet['pallet_no'] }}</td>
                                        <td>{{ $pallet['room'] }}</td>
                                        <td>
                                            @foreach ($pallet['show_product_id'] as $product_id)
                                                {{ $product_id }} <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($pallet['product_name'] as $name)
                                                {{ $name }} <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach (array_map(null, $pallet['quantity'],$pallet['quantityUm'], $pallet['quantity2'],$pallet['quantityUm2_']) as [$quantity,$quantityUm, $quantity2, $quantityUm2])
                                                {{ $quantity }} {{ $quantityUm }} : {{ $quantity2 }} {{ $quantityUm2 }} <br>
                                             @endforeach
                                        </td>
                                        <td>{{ $pallet['pallet_type_id'] }}</td>
                                        <td>{{ $pallet['note'] ?? '' }}</td>
                                        <td>
                                            <a type="button" href="{{ route('Remove_Pallet', $key) }}"
                                                class="btn btn-danger ">ลบ</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('Insert_Pallet', $order_number) }}" class="btn btn-success ">บันทึกข้อมูล</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#show-pallet').DataTable({
                info: false,
                scrollX: true,
                ordering: true,
                paging: true,
                columns: [
                    {data: 'pallet_id',className: 'text-center'},
                    {data: 'pallet_no', width: '15%',className: 'text-center'},
                    {data: 'room',className: 'text-center'},
                    {data: 'show_product_id',className: 'text-center'},
                    {data: 'product_name',className: 'text-center'},
                    {data: '',className: 'text-center'},
                    {data: 'pallet_type_id',className: 'text-center'},
                    {data: 'note'},
                    {data: '#'},
                ]
            });
        })
    </script>
    <script>
        var product_count = 1;
        var $order_number = "{{ $order_number }}";
        $('#add-product').click(function() {
            product_count++;
            $('#add-products').append(`
                <div class="row" id="product-${product_count}">
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="product_id[${product_count}]" class="form-label">รหัสสินค้า</label>
                            <input type="text" class="form-control" id="show_product_id${product_count}"
                                name="show_product_id[${product_count}]" readonly>
                            <input type="hidden" class="form-control" id="product_id${product_count}"
                                name="product_id[${product_count}]" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-7">
                        <div class="form-group">
                            <label for="product_name[${product_count}]" class="form-label">รายการ</label>
                            <input type="text" class="form-control" id="product_name${product_count}"
                                name="product_name[${product_count}]">
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="ordered_quantity[${product_count}]" class="form-label">สั่งจ่าย</label>
                            <input type="text" class="form-control mb-3" id="ordered_quantity${product_count}"
                                name="ordered_quantity[${product_count}]" disabled>
                            <input type="text" class="form-control" id="ordered_quantity2_${product_count}"
                                name="ordered_quantity2[${product_count}]" disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4">
                        <div class="form-group">
                            <label for="quantity[${product_count}]" class="form-label">จ่ายจริง</label>
                            <input type="number" class="form-control mb-3" id="quantity${product_count}"
                                name="quantity[${product_count}]" >
                            <input type="number" class="form-control" id="quantity2_${product_count}"
                                name="quantity2[${product_count}]" >
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-3">
                        <div class="form-group">
                            <label for="quantity_um${product_count}" class="form-label">หน่วย</label>
                            <input type="text" class="form-control mb-3" id="quantity_um${product_count}"
                                name="quantityUm[${product_count}]" readonly>
                            <input type="text" class="form-control" id="quantity_um2_${product_count}"
                                name="quantityUm2_[${product_count}]" readonly>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="bag_color[${product_count}]" class="form-label">สีถุง</label>
                            <input type="text" class="form-control" id="bag_color${product_count}"
                                name="bag_color[${product_count}]" disabled>
                        </div>
                    </div>
                     <div class="col-1">
                        <label for="remove-product" class="form-label">#</label>
                        <div class="form-group">
                        <button type="button" class="btn btn-danger remove-product">ลบ</button>
                    </div>
                </div>
    `);
            // เรียกใช้งาน autocomplete กับฟิลด์ที่เพิ่มใหม่
            initializePalletAutocomplete(`#product_name${product_count}`, `#product_id${product_count}`,
                `#ordered_quantity${product_count}`,
                `#ordered_quantity2_${product_count}`,
                `#bag_color${product_count}`,
                `#note${product_count}`,
                `#order_number${product_count}`,
                `#show_product_id${product_count}`,
                `#quantity_um${product_count}`,
                `#quantity_um2_${product_count}`,
            );
        });

        // ฟังก์ชันสำหรับลบแถว
        $(document).on('click', '.remove-product', function() {
            $(this).closest('[id^="product-"]').remove();
        });

        // ฟังก์ชันสำหรับ autocomplete
        function initializePalletAutocomplete(product_nameSelector, product_idSelector, ordered_quantitySelector,
            ordered_quantity2Selector,
            bag_colorSelector, noteSelector, order_numberSelector, show_product_idSelector, ordered_umSelector,
            ordered_um2Selector) {
            $(product_nameSelector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('AutoCompleteAddPallet', ['order_number' => $order_number]) }}",
                        data: {
                            query: request.term ,
                            type:  $("#pallet_type").val(),
                        },
                        success: function(data) {
                            // console.log(data);
                            response(data); // ส่งข้อมูลผลลัพธ์ไปยัง autocomplete
                        },
                        error: function(xhr, status, error) {
                            alert('มีข้อผิดพลาดในการบันทึกข้อมูล');
                            console.log(xhr.responseText);
                        }
                    });
                },
                minLength: 0, // เริ่มค้นหาหลังจากพิมพ์ไป 2 ตัวอักษร
                select: function(event, ui) {
                    // เมื่อเลือกสินค้า ให้เติมรหัสสินค้าในฟิลด์ item_id
                    $(product_idSelector).val(ui.item.product_id); // เติมรหัสสินค้าในช่องรหัสสินค้า
                    $(show_product_idSelector).val(ui.item.product_no);
                    $(product_nameSelector).val(ui.item.product_name); // เติมชื่อสินค้าในช่องชื่อสินค้า
                    $(ordered_quantitySelector).val(ui.item.ordered_quantity);
                    $(ordered_quantity2Selector).val(ui.item.ordered_quantity2);
                    $(ordered_umSelector).val(ui.item.ordered_quantity_UM);
                    $(ordered_um2Selector).val(ui.item.ordered_quantity_UM2);
                    $(bag_colorSelector).val(ui.item.bag_color);
                    $(noteSelector).val(ui.item.note);

                }
            });
            $(product_nameSelector).focus(function() {
                $(this).autocomplete('search', ''); // ส่งค่าว่างเพื่อแสดง autocomplete ทันที
            });
            
        }
        initializePalletAutocomplete(`#product_name0`, `#product_id0`, `#ordered_quantity0`, `#ordered_quantity2_0`,
            `#bag_color0`, `#note0`,
            `#order_number0`, `#show_product_id0`, `#quantity_um0`, `#quantity_um2_0`);
    </script>
    {{-- <script>

        $('#pallet_type').on('change', function() {
            const type = $(this).val(); // รับค่าที่เลือก
            if (type === 'ขายเพิ่ม' || type === 'ปกติ') {
                changePalletType(type); // เรียกฟังก์ชันพร้อมส่งค่าประเภท
            }
        });
    
        function changePalletType(type) {
            $.ajax({
                url: "{{ route('AutoCompleteAddPallet', ['order_number' => $order_number]) }}", // ตรวจสอบว่า order_number มีค่าหรือส่งจาก Blade
                // method: 'GET', // ระบุ method ให้ถูกต้อง
                data: {
                    type: type // ส่งค่าประเภทที่เลือก
                },
                success: function(data) {
                    response(data); // ดูข้อมูลผลลัพธ์
                    // คุณสามารถเพิ่มการทำงานเพิ่มเติมที่นี่ เช่น อัปเดต UI
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText); // แสดงข้อผิดพลาดใน console
                }
            });
        }
    </script> --}}
@endsection
