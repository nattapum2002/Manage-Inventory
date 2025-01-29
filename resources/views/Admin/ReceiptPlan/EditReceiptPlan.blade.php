@extends('layouts.master')

@section('title')
    แก้ไขแผนรับสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('SaveEditDetail') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="receipt_plan_id">รหัสแผนรับสินค้า</label>
                                            <input type="text" class="form-control" id="receipt_plan_id"
                                                name="receipt_plan_id" value="{{ $receipt_plan_id }}"
                                                placeholder="รหัสแผนรับสินค้า" readonly>
                                            @error('receipt_plan_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_id">กะพนักงาน</label>
                                            <select class="form-control" id="shift_id" name="shift_id" readonly>
                                                <option selected value="{{ $ReceiptPlans->shift_id }}">
                                                    {{ $ReceiptPlans->shift_name }}
                                                </option>
                                                @foreach ($shifts as $shift)
                                                    <option value="{{ $shift->shift_id }}">{{ $shift->shift_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('shift_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="date">วันที่</label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                value="{{ $ReceiptPlans->date }}" placeholder="วันที่">
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-11 col-md-10 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <textarea name="note" class="form-control" id="note" name="note" placeholder="หมายเหตุ" rows="1">{{ $ReceiptPlans->note ?? '' }}</textarea>
                                            @error('note')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-2 col-sm-12">
                                        <div class="form-group">
                                            <label for="#">#</label><br>
                                            <button type="submit" class="btn btn-success">บันทึก</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <table id="EditReceiptPlanTable" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>จำนวนสินค้าเดิม(กก.)</th>
                                        <th>เพิ่มจำนวนสินค้า(กก.)</th>
                                        <th>ลดจำนวนสินค้า(กก.)</th>
                                        <th>จำนวนสินค้าทั้งหมด(กก.)</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ReceiptPlansDetails as $Product)
                                        <tr>
                                            <td>
                                                <span id="span_product_id_{{ $Product->product_id }}">
                                                    {{ $Product->product_number }}
                                                </span>
                                                <input type="text" class="form-control"
                                                    id="edit_product_id_{{ $Product->product_id }}" name="edit_product_id"
                                                    value="{{ $Product->product_number }}" style="display:none;">
                                                @error('edit_product_id_{{ $Product->product_id }}')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <span id="span_product_name_{{ $Product->product_id }}">
                                                    {{ $Product->product_description }}
                                                </span>
                                                <input type="text" class="form-control"
                                                    id="edit_product_name_{{ $Product->product_id }}"
                                                    name="edit_product_name" value="{{ $Product->product_description }}"
                                                    style="display:none;" readonly>
                                            </td>
                                            <td>
                                                <span id="span_product_quantity_{{ $Product->product_id }}">
                                                    {{ $Product->weight }}
                                                </span>
                                                <input type="number" class="form-control"
                                                    id="edit_product_quantity_{{ $Product->product_id }}"
                                                    name="edit_product_quantity" value="{{ $Product->weight }}"
                                                    style="display:none;">
                                            </td>
                                            <td>
                                                <span id="span_increase_quantity_{{ $Product->product_id }}">
                                                    {{ $Product->increase_weight }}
                                                </span>
                                                <input type="number" class="form-control"
                                                    id="edit_increase_quantity_{{ $Product->product_id }}"
                                                    name="edit_increase_quantity" value="{{ $Product->increase_weight }}"
                                                    style="display:none;">
                                            </td>
                                            <td>
                                                <span id="span_reduce_quantity_{{ $Product->product_id }}">
                                                    {{ $Product->reduce_weight }}
                                                </span>
                                                <input type="number" class="form-control"
                                                    id="edit_reduce_quantity_{{ $Product->product_id }}"
                                                    name="edit_reduce_quantity" value="{{ $Product->reduce_weight }}"
                                                    style="display:none;">
                                            </td>
                                            <td>
                                                <span id="span_total_quantity_{{ $Product->product_id }}">
                                                    {{ $Product->total_weight }}
                                                </span>
                                                <input type="number" class="form-control"
                                                    id="edit_total_quantity_{{ $Product->product_id }}"
                                                    name="edit_total_quantity" value="{{ $Product->total_weight }}"
                                                    style="display:none;" readonly>
                                            </td>
                                            <td>
                                                <input type="hidden" id="edit_old_product_id_{{ $Product->product_id }}"
                                                    name="edit_old_product_id" value="{{ $Product->product_id }}"
                                                    style="display:none;" readonly>
                                                <input type="hidden" id="receipt_plan_id" name="receipt_plan_id"
                                                    value="{{ $receipt_plan_id }}" readonly>
                                                <div class="d-flex">
                                                    <button type="button" class="btn btn-primary edit_product"
                                                        data-product_id="{{ $Product->product_id }}">แก้ไข</button>
                                                    <button type="button" class="btn btn-danger"
                                                        id="cancel_edit_product_{{ $Product->product_id }}"
                                                        style="display:none;">ยกเลิก</button>
                                                    <a href="{{ route('DeleteProduct', ['receipt_plan_id' => $receipt_plan_id, 'product_id' => $Product->product_id]) }}"
                                                        class="btn btn-danger delete_product ms-2"
                                                        id="delete_product_{{ $Product->product_id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>รายการสินค้า</th>
                                        <th>จำนวนสินค้าเดิม(กก.)</th>
                                        <th>เพิ่มจำนวนสินค้า(กก.)</th>
                                        <th>ลดจำนวนสินค้า(กก.)</th>
                                        <th>จำนวนสินค้าทั้งหมด(กก.)</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">เพิ่มสินค้า</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('AddProduct') }}" method="POST">
                                @csrf
                                <div style="display:none;" class="row">
                                </div>

                                <div id="add-products">
                                    @error('product_id[${product_count}]')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <input type="hidden" id="receipt_plan_id" name="receipt_plan_id"
                                    value="{{ $receipt_plan_id }}" style="display:none;" readonly>
                                <div class="d-flex justify-content-center">
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
        $("#EditReceiptPlanTable").DataTable({
            // responsive: true,
            // lengthChange: true,
            // autoWidth: true,
            info: false,
            scrollX: true,
            ordering: true,
            paging: true,
            pageLength: 50,
            lengthMenu: [25, 50, 100],
            order: [],
        });
    </script>

    <script>
        $(document).ready(function() {
            let fields = ['product_id', 'product_name', 'product_quantity', 'increase_quantity', 'reduce_quantity',
                'total_quantity', 'old_product_id'
            ];

            const receipt_plan_id = $('#receipt_plan_id').val();

            function hideEdit(edit_product_id) {
                fields.forEach(function(field) {
                    $('#span_' + field + '_' + edit_product_id).show();
                    $('#edit_' + field + '_' + edit_product_id).hide();
                });
                $('.save_product_btn').text('แก้ไข').removeClass('btn-success')
                    .addClass('btn-primary').removeClass('save_product_btn').addClass('edit_product');
                $('#cancel_edit_product_' + edit_product_id).hide();
                $('#delete_product_' + edit_product_id).show();
            }

            $(document).on('click', '.edit_product', function() {
                let edit_product_id = $(this).data('product_id');
                fields.forEach(function(field) {
                    $('#span_' + field + '_' + edit_product_id).hide();
                    $('#edit_' + field + '_' + edit_product_id).show();
                });
                $('#cancel_edit_product_' + edit_product_id).show();
                $('#delete_product_' + edit_product_id).hide();
                $(this).text('บันทึก').removeClass('btn-primary').addClass('btn-success')
                    .removeClass('edit_product').addClass('save_product_btn').attr('type', 'button');

                $('#cancel_edit_product_' + edit_product_id).click(function() {
                    hideEdit(edit_product_id);
                });

                // Initialize autocomplete for each field when edit mode is activated
                initializeProductAutocomplete('#edit_product_name_' + edit_product_id,
                    '#edit_product_id_' + edit_product_id);

                $(`#edit_product_quantity_${edit_product_id}, #edit_increase_quantity_${edit_product_id}, #edit_reduce_quantity_${edit_product_id}`)
                    .on('input', function() {
                        const productQuantity = parseFloat($(`#edit_product_quantity_` +
                                edit_product_id)
                            .val()) || 0;
                        const increaseQuantity = parseFloat($(`#edit_increase_quantity_` +
                                edit_product_id)
                            .val()) || 0;
                        const reduceQuantity = parseFloat($(`#edit_reduce_quantity_` + edit_product_id)
                            .val()) || 0;

                        const totalQuantity = productQuantity + increaseQuantity - reduceQuantity;
                        $(`#edit_total_quantity_` + edit_product_id).val(totalQuantity.toFixed(2));
                    });
            });

            $(document).on('click', '.save_product_btn', function() {
                let edit_product_id = $(this).data('product_id');
                let data_product = {};

                // รวบรวมข้อมูลจากฟิลด์ต่างๆ
                $.each(fields, function(index, field) {
                    data_product[field] = $('#edit_' + field + '_' + edit_product_id).val();
                    $('#span_' + field + '_' + edit_product_id).text($('#edit_' + field + '_' +
                        edit_product_id).val());
                });

                // ส่งข้อมูลด้วย fetch
                fetch("{{ route('SaveEditProduct') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            receipt_plan_id: receipt_plan_id,
                            product_edit: data_product
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message);
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });

                hideEdit(edit_product_id);
            });
        });

        function initializeProductAutocomplete(nameSelector, idSelector) {
            $(idSelector).autocomplete({
                source: function(request, response) {
                    fetch("{{ route('AutocompleteProduct') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                query: request.term // ส่งข้อมูลที่ต้องการค้นหา
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            response(data); // ส่งข้อมูลผลลัพธ์ไปยัง autocomplete
                        })
                        .catch(error => {
                            console.error("Error fetching autocomplete data:", error);
                        });
                },
                minLength: 0, // เริ่มค้นหาหลังจากพิมพ์ไป 0 ตัวอักษร
                select: function(event, ui) {
                    // เมื่อเลือกสินค้า ให้เติมข้อมูลในทั้งสองช่อง
                    $(nameSelector).val(ui.item.product_name); // เติมชื่อสินค้า
                    $(idSelector).val(ui.item.product_id); // เติมรหัสสินค้า
                }
            });

            $(idSelector).focus(function() {
                $(this).autocomplete('search', ''); // ส่งค่าว่างเพื่อแสดง autocomplete ทันที
            });
        }
    </script>

    <script>
        var product_count = 1;

        $('#add-product').click(function() {
            product_count++;
            $('#add-products').append(`
                <div class="row" id="product-${product_count}">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="product_id[${product_count}]" class="form-label">รหัสสินค้า</label>
                                    <input type="text" class="form-control" id="product_id${product_count}" name="product_id[${product_count}]">
                                    @error('product_id[${product_count}]')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="product_name[${product_count}]" class="form-label">รายการสินค้า</label>
                                    <input type="text" class="form-control" id="product_name${product_count}" name="product_name[${product_count}]" readonly>
                                    @error('product_name${product_count}')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-11 col-md-11 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <label for="product_quantity[${product_count}]" class="form-label">จำนวนสินค้าเดิม(กก.)</label>
                                            <input type="number" class="form-control" id="product_quantity${product_count}" name="product_quantity[${product_count}]" value="0">
                                            @error('product_quantity${product_count}')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <label for="increase_quantity[${product_count}]" class="form-label">เพิ่มจำนวนสินค้า(กก.)</label>
                                            <input type="number" class="form-control" id="increase_quantity${product_count}" name="increase_quantity[${product_count}]" value="0">
                                            @error('increase_quantity${product_count}')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <label for="reduce_quantity[${product_count}]" class="form-label">ลดจำนวนสินค้า(กก.)</label>
                                            <input type="number" class="form-control" id="reduce_quantity${product_count}" name="reduce_quantity[${product_count}]" value="0">
                                            @error('reduce_quantity${product_count}')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <label for="total_quantity[${product_count}]" class="form-label">จำนวนสินค้าทั้งหมด(กก.)</label>
                                            <input type="text" class="form-control" id="total_quantity${product_count}" name="total_quantity[${product_count}]" readonly>
                                            @error('total_quantity${product_count}')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-12">
                                <div class="form-group">
                                    <label for="remove-product" class="form-label">#</label><br>
                                    <button type="button" class="btn btn-danger remove-product">ลบ</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <hr>
                    </div>
                </div>
            `);
            // เรียกใช้งาน autocomplete กับฟิลด์ที่เพิ่มใหม่
            initializeProductAutocomplete(`#product_name${product_count}`, `#product_id${product_count}`);

            $(`#product_quantity${product_count}, #increase_quantity${product_count}, #reduce_quantity${product_count}`)
                .on('input', function() {
                    const productQuantity = parseFloat($(`#product_quantity${product_count}`).val()) || 0;
                    const increaseQuantity = parseFloat($(`#increase_quantity${product_count}`).val()) || 0;
                    const reduceQuantity = parseFloat($(`#reduce_quantity${product_count}`).val()) || 0;

                    const totalQuantity = productQuantity + increaseQuantity - reduceQuantity;
                    $(`#total_quantity${product_count}`).val(totalQuantity.toFixed(2));
                });
        });

        // ฟังก์ชันสำหรับลบแถว
        $(document).on('click', '.remove-product', function() {
            $(this).closest('[id^="product-"]').remove();
        });

        function initializeProductAutocomplete(nameSelector, idSelector) {
            $(idSelector).autocomplete({
                source: function(request, response) {
                    fetch("{{ route('AutocompleteProduct') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                query: request.term // ส่งข้อมูลที่ต้องการค้นหา
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            response(data); // ส่งข้อมูลผลลัพธ์ไปยัง autocomplete
                        })
                        .catch(error => {
                            console.error("Error fetching autocomplete data:", error);
                        });
                },
                minLength: 0, // เริ่มค้นหาหลังจากพิมพ์ไป 0 ตัวอักษร
                select: function(event, ui) {
                    // เมื่อเลือกสินค้า ให้เติมข้อมูลในทั้งสองช่อง
                    $(nameSelector).val(ui.item.product_name); // เติมชื่อสินค้า
                    $(idSelector).val(ui.item.product_id); // เติมรหัสสินค้า
                }
            });

            $(idSelector).focus(function() {
                $(this).autocomplete('search', ''); // ส่งค่าว่างเพื่อแสดง autocomplete ทันที
            });
        }
    </script>
@endsection
