@extends('layouts.master')

@section('title')
    เพิ่มพาเลท
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
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="pallet_id">รหัสพาเลท</label>
                                            <input type="text" class="form-control" id="pallet_id" name="pallet_id"
                                                placeholder="รหัสพาเลท">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="pallet_no">หมายเลขพาเลท</label>
                                            <input type="text" class="form-control" id="pallet_no" name="pallet_no"
                                                placeholder="หมายเลขพาเลท">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="room">ห้องเก็บ</label>
                                            <select class="form-control" name="room" id="room">
                                                <option selected>เลือกห้อง</option>
                                                <option value="Cold-A">Cold-A</option>
                                                <option value="Cold-C">Cold-C</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <input type="text" class="form-control" id="note" name="note"
                                                placeholder="หมายเหตุ">
                                        </div>
                                    </div>
                                </article>
                                <hr>
                                <article>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="product_id[0]" class="form-label">รหัสสินค้า</label>
                                                <input type="text" class="form-control" id="product_id0"
                                                    name="product_id[0]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="product_name[0]" class="form-label">รายการ</label>
                                                <input type="text" class="form-control" id="product_name0"
                                                    name="product_name[0]">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="ordered_quantity[0]" class="form-label">สั่งจ่าย</label>
                                                <input type="text" class="form-control" id="ordered_quantity0"
                                                    name="ordered_quantity[0]" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="bag_color[0]" class="form-label">สีถุง</label>
                                                <input type="text" class="form-control" id="bag_color0"
                                                    name="bag_color[0]" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="note[0]" class="form-label">หมายเหตุ</label>
                                                <input type="text" class="form-control" id="note0" name="note[0]"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                <article id="add-products">

                                </article>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-primary mr-3"
                                        id="add-product">เพิ่มสินค้า</button>
                                    <button type="submit" class="btn btn-success">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        var product_count = 1;

        $('#add-product').click(function() {
            product_count++;
            $('#add-products').append(`
        <div class="row" id="product-${product_count}">
            <div class="col-11">
                <div class="row">
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="product_id[${product_count}]" class="form-label">รหัสสินค้า</label>
                            <input type="text" class="form-control" id="product_id${product_count}"
                                name="product_id[${product_count}]" readonly>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="product_name[${product_count}]" class="form-label">รายการ</label>
                            <input type="text" class="form-control" id="product_name${product_count}"
                                name="product_name[${product_count}]">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="ordered_quantity[${product_count}]" class="form-label">สั่งจ่าย</label>
                            <input type="text" class="form-control" id="ordered_quantity${product_count}"
                                name="ordered_quantity[${product_count}]" disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="bag_color[${product_count}]" class="form-label">สีถุง</label>
                            <input type="text" class="form-control" id="bag_color${product_count}"
                                name="bag_color[${product_count}]" disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="note[${product_count}]" class="form-label">หมายเหตุ</label>
                            <input type="text" class="form-control" id="note${product_count}" name="note[${product_count}]"
                                disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-1">
                    <label for="remove-product" class="form-label">#</label>
                <div class="form-group">
                    <button type="button" class="btn btn-danger remove-product">ลบ</button>
                </div>
            </div>
        </div>
    `);
            // เรียกใช้งาน autocomplete กับฟิลด์ที่เพิ่มใหม่
            initializePalletAutocomplete(`#product_name${product_count}`, `#product_id${product_count}`,
                `#ordered_quantity${product_count}`,
                `#bag_color${product_count}`,
                `#note${product_count}`,
                `#order_number${product_count}`,

            );
        });

        // ฟังก์ชันสำหรับลบแถว
        $(document).on('click', '.remove-product', function() {
            $(this).closest('[id^="product-"]').remove();
        });

        // ฟังก์ชันสำหรับ autocomplete
        function initializePalletAutocomplete(product_nameSelector, product_idSelector, ordered_quantitySelector,
            bag_colorSelector, noteSelector, order_numberSelector) {
            $(product_nameSelector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('AutoCompleteAddPallet', ['order_number' => $order_number]) }}",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            console.log(data);
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
                    $(product_nameSelector).val(ui.item.product_name); // เติมชื่อสินค้าในช่องชื่อสินค้า
                    $(ordered_quantitySelector).val(ui.item.ordered_quantity);
                    $(bag_colorSelector).val(ui.item.bag_color);
                    $(noteSelector).val(ui.item.note);
                }
            });

            $(product_nameSelector).focus(function() {
                $(this).autocomplete('search', ''); // ส่งค่าว่างเพื่อแสดง autocomplete ทันที
            });
        }
        initializePalletAutocomplete(`#product_name0`, `#product_id0`, `#ordered_quantity0`, `#bag_color0`, `#note0`,
            `#order_number0`);
    </script>
@endsection
