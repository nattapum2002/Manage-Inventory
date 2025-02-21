@extends('layouts.master')

@section('title')
    รับสินค้า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-end">
                                <div class="d-flex align-items-center flex-wrap me-3">
                                    <div class="form-check me-2">
                                        <input type="checkbox" class="form-check-input" name="ShowAll" id="ShowAll">
                                        <label for="ShowAll" class="form-check-label">แสดงข้อมูลทั้งหมด</label>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center flex-wrap me-3">
                                    <div class="form-check me-2">
                                        <input type="radio" class="form-check-input" name="shift" id="shiftA1"
                                            value="1" checked>
                                        <label for="shiftA1" class="form-check-label">A ชุด 1</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input type="radio" class="form-check-input" name="shift" id="shiftA2"
                                            value="2">
                                        <label for="shiftA2" class="form-check-label">A ชุด 2</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input type="radio" class="form-check-input" name="shift" id="shiftB1"
                                            value="3">
                                        <label for="shiftB1" class="form-check-label">B ชุด 1</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="shift" id="shiftB2"
                                            value="4">
                                        <label for="shiftB2" class="form-check-label">B ชุด 2</label>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="date" id="date"
                                            value="{{ $ReceiptPlan->first()?->date ? (new DateTime($ReceiptPlan->first()->date))->format('Y-m-d') : now()->format('Y-m-d') }}">
                                        <button type="button" class="btn btn-primary" id="btn-search-plan">
                                            <i class="fas fa-search" id="icon-search"></i>
                                            <div class="spinner-border spinner-border-sm text-light" id="loading"
                                                style="display: none;" role="status">
                                            </div>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg col-md col-sm-12">
                                    <div class="form-group">
                                        <label for="receipt_slip_number" class="form-label">Slip ใบที่</label>
                                        <input type="text" class="form-control" id="receipt_slip_number"
                                            name="receipt_slip_number" required>
                                    </div>
                                </div>
                                <div class="col-lg col-md col-sm-12">
                                    <div class="form-group">
                                        <label for="department" class="form-label">หน่วยงาน</label>
                                        <input type="text" class="form-control" id="SelectorDepartment" name="department"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg col-md col-sm-12">
                                    <div class="form-group">
                                        <label for="team_receive_product" class="form-label">ทีมรับ</label>
                                        <select class="form-control" name="team_receive_product"
                                            id="team_receive_product"></select>
                                    </div>
                                </div>
                                <div class="col-lg col-md col-sm-12">
                                    <div class="form-group">
                                        <label for="product_checker_id" class="form-label">Product Checker</label>
                                        <input type="text" class="form-control" id="SelectorProductChecker"
                                            name="product_checker_id" required>
                                    </div>
                                </div>
                                <div class="col-lg col-md col-sm-12">
                                    <div class="form-group">
                                        <label for="domestic_checker_id" class="form-label">Domestic Checker</label>
                                        <input type="text" class="form-control" id="domestic_checker_id"
                                            name="domestic_checker_id" value="{{ auth()->user()->user_id }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-21 d-flex justify-content-end">
                                    <button type="button" class="btn btn-success" id="btn-save">บันทึก</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <table id="ReceiptProductTable" class="table table-bordered table-striped nowrap">
                            <thead>
                                <tr>
                                    <th>รหัสสินค้า</th>
                                    <th>รายการสินค้า</th>
                                    <th>จำนวน</th>
                                    <th>จำนวนที่รับ</th>
                                    <th>ห้องเก็บ</th>
                                    <th>หมายเหตุ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>รหัสสินค้า</th>
                                    <th>รายการสินค้า</th>
                                    <th>จำนวน</th>
                                    <th>จำนวนที่รับ</th>
                                    <th>ห้องเก็บ</th>
                                    <th>หมายเหตุ</th>
                                    <th></th>
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
        const ReceiptProductDataTable = $("#ReceiptProductTable").DataTable({
            // responsive: true,
            // lengthChange: true,
            // autoWidth: true,
            info: false,
            scrollX: true,
            ordering: true,
            paging: true,
            pageLength: 50,
            lengthMenu: [25, 50, 100],
            order: []
        });

        let receiptData = []; // Store confirmed data

        function updateButtonState(product_id, isConfirmed) {
            document.getElementById(`btn-confirm_${product_id}`).style.display = isConfirmed ? 'none' : 'inline-block';
            document.getElementById(`btn-cancel_${product_id}`).style.display = isConfirmed ? 'inline-block' : 'none';
        }

        function resetProductData(product_id) {
            const receiptQuantityElement = document.getElementById(`receipt_quantity_${product_id}`);
            const receiptQuantityElement2 = document.getElementById(`receipt_quantity2_${product_id}`);
            const noteElement = document.getElementById(`note_${product_id}`);
            const receiptRemainingQuantityElement = document.getElementById(`receipt_remaining_quantity_${product_id}`);
            const warehouseElement = document.getElementById(`warehouse_${product_id}`);

            // Ensure receipt_quantity is a number
            const receiptQuantity = parseFloat(receiptQuantityElement.value) || 0;

            if (receiptRemainingQuantityElement) {
                // Correcting the way remaining quantity is updated
                const remainingQuantity = parseFloat(receiptRemainingQuantityElement.textContent) || 0;
                receiptRemainingQuantityElement.textContent = (remainingQuantity + receiptQuantity);
            }

            if (receiptQuantityElement) {
                receiptQuantityElement.value = ''; // Clear input
                receiptQuantityElement.disabled = false; // Enable input
            }

            if (receiptQuantityElement2) {
                receiptQuantityElement2.value = ''; // Clear input
                receiptQuantityElement2.disabled = false; // Enable input
            }

            if (noteElement) {
                noteElement.value = ''; // Clear textarea
                noteElement.disabled = false; // Enable textarea
            }

            if (warehouseElement) {
                warehouseElement.disabled = false;
            }

            // Remove from array
            receiptData = receiptData.filter(data => data.product_id !== product_id);

            // Update the button state (show confirm and hide cancel)
            updateButtonState(product_id, false);
        }

        function bindTableEvents() {
            document.querySelector('#ReceiptProductTable').addEventListener('click', function(e) {
                const target = e.target;

                // Handle confirm button click
                if (target.classList.contains('btn-confirm')) {
                    const product_id = target.getAttribute('data-product_id');
                    const receiptQuantityElement = document.getElementById(`receipt_quantity_${product_id}`);
                    const receiptQuantityElement2 = document.getElementById(`receipt_quantity2_${product_id}`);
                    const noteElement = document.getElementById(`note_${product_id}`);
                    const receiptRemainingQuantityElement = document.getElementById(
                        `receipt_remaining_quantity_${product_id}`);
                    const warehouseElement = document.getElementById(`warehouse_${product_id}`);

                    const receipt_quantity = parseFloat(receiptQuantityElement.value) || 0;
                    const receipt_quantity2 = parseFloat(receiptQuantityElement2.value) || 0;
                    const remaining_quantity = parseFloat(receiptRemainingQuantityElement.textContent) || 0;

                    receiptRemainingQuantityElement.textContent = (remaining_quantity - receipt_quantity);
                    receiptQuantityElement.disabled = true;
                    receiptQuantityElement2.disabled = true;
                    noteElement.disabled = true;
                    warehouseElement.disabled = true;

                    receiptData.push({
                        product_id,
                        receipt_quantity,
                        receipt_quantity2,
                        warehouse: warehouseElement.value,
                        note: noteElement.value,
                    });

                    updateButtonState(product_id, true);
                }

                // Handle cancel button click
                if (target.classList.contains('btn-cancel')) {
                    const product_id = target.getAttribute('data-product_id');
                    resetProductData(product_id);
                    // alert(`ข้อมูลของสินค้า ID ${product_id} ถูกยกเลิก`);
                }
            });

            document.querySelector('#ReceiptProductTable').addEventListener('input', function(e) {
                const target = e.target;

                // Handle receipt quantity input change
                if (target.id.startsWith('receipt_quantity_')) {
                    const product_id = e.target.id.split('_').pop();
                    const receiptQuantityElement2 = document.getElementById(`receipt_quantity2_${product_id}`);

                    if (!receiptQuantityElement2) {
                        console.error('Target element not found for product_id:', product_id);
                        return;
                    }

                    const value = parseFloat(target.value) || 0;
                    receiptQuantityElement2.value = (value / 10).toFixed(2); // คำนวณและอัปเดตค่า
                }
            });
        }

        function initializeAutocompleteDepartment(Selector) {
            $(Selector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('AutoCompleteDepartment') }}",
                        data: {
                            query: request.term,
                        },
                        success: function(data) {
                            response(data);
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                },
                minLength: 0,
                select: function(event, ui, response) {
                    $(Selector).val(ui.item.department);
                }
            });

            $(Selector).focus(function() {
                $(this).autocomplete('search', '');
            });
        }
        initializeAutocompleteDepartment('#SelectorDepartment');

        function initializeAutocompleteProductChecker(Selector) {
            $(Selector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('AutoCompleteProductChecker') }}",
                        data: {
                            query: request.term,
                        },
                        success: function(data) {
                            response(data);
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                },
                minLength: 0,
                select: function(event, ui, response) {
                    $(Selector).val(ui.item.ProductChecker);
                }
            });

            $(Selector).focus(function() {
                $(this).autocomplete('search', '');
            });
        }
        initializeAutocompleteProductChecker('#SelectorProductChecker');

        function loadReceiptPlanData(date, shift, showAll) {
            document.getElementById('loading').style.display = 'inline-block';
            document.getElementById('icon-search').style.display = 'none';

            fetch(`{{ route('ReceiptPlanFilter') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        date: date,
                        shift: shift,
                        ShowAll: showAll
                    }),
                })
                .then((response) => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then((data) => {
                    ReceiptProductDataTable.clear();
                    if (data.ReceiptPlanFilter && data.ReceiptPlanFilter.length > 0) {
                        const newRows = data.ReceiptPlanFilter.map(item => [
                            item.product_number,
                            item.product_description,
                            `
                            <p class="text-success">ตามแผน : ${item.total_weight} Kg</p>
                            <span class="text-danger">ขาดอีก : </span>
                            <span class="text-danger" id="receipt_remaining_quantity_${item.product_id}">${item.remaining_quantity}</span>
                            <span class="text-danger">Kg</span>
                            `,
                            `
                            <input type="number" class="form-control mb-1" id="receipt_quantity_${item.product_id}" placeholder="Kg" min="0" max="${item.remaining_quantity}">
                            <input type="number" class="form-control" id="receipt_quantity2_${item.product_id}" placeholder="BAG">
                            `,
                            `
                            <select class="form-control" id="warehouse_${item.product_id}" required>
                                <option>เลือกห้องเก็บ</option>
                                ${data.Warehouses.map(warehouse =>
                                    `<option ${item.warehouse == warehouse.warehouse_name ? 'selected' : ''} value="${warehouse.id}">${warehouse.warehouse_name}</option>`
                                ).join('')}
                            </select>
                            `,
                            `<textarea class="form-control" id="note_${item.product_id}" rows="1">${item.note || ''}</textarea>`,
                            `<button type="button" class="btn btn-warning btn-confirm" id="btn-confirm_${item.product_id}" data-product_id="${item.product_id}">ยืนยัน</button>
                                <button type="button" class="btn btn-danger btn-cancel" id="btn-cancel_${item.product_id}" data-product_id="${item.product_id}" style="display: none;">ยกเลิก</button>`
                        ]);
                        ReceiptProductDataTable.rows.add(newRows).draw();
                    } else {
                        alert('ไม่พบข้อมูล');
                    }

                    let select = document.getElementById('team_receive_product');
                    select.innerHTML = ''; // ล้าง option เก่าทั้งหมด

                    if (data.Team) {
                        let newOption = document.createElement('option');
                        data.Team.forEach(team => {
                            let newOption = document.createElement('option');
                            newOption.value = team.team_id;
                            newOption.text = team.team_name;
                            select.appendChild(newOption);
                        });
                    } else {
                        let newOption = document.createElement('option');
                        newOption.text = 'ไม่มีทีม';
                        select.appendChild(newOption);
                    }

                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';
                })
                .catch(error => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';
                    console.error('Error:', error);
                });
        }

        document.getElementById('btn-search-plan').addEventListener('click', function() {
            const date = document.getElementById('date').value;
            const shift = document.querySelector('input[name="shift"]:checked').value;
            const showAll = document.getElementById('ShowAll').checked ? 1 : 0;
            loadReceiptPlanData(date, shift, showAll);
        });

        document.addEventListener('DOMContentLoaded', function() {
            bindTableEvents(); // Bind event listeners to buttons
            const defaultDate = document.getElementById('date').value;
            const defaultShift = document.querySelector('input[name="shift"]:checked').value;
            const defaultShowAll = document.getElementById('ShowAll').checked ? 1 : 0;

            loadReceiptPlanData(defaultDate, defaultShift, defaultShowAll);

        });

        document.getElementById('btn-save').addEventListener('click', function() {
            if (receiptData.length === 0) {
                alert('ไม่มีข้อมูลที่ต้องบันทึก');
                return;
            }

            // ดึงค่าจากฟอร์ม
            const date = document.getElementById('date').value;
            const shift = document.querySelector('input[name="shift"]:checked').value;
            const receiptSlipNumber = document.getElementById('receipt_slip_number').value;
            const department = document.getElementById('SelectorDepartment').value;
            const productCheckerId = document.getElementById('SelectorProductChecker').value;
            const domesticCheckerId = document.getElementById('domestic_checker_id').value;
            const teamReceiveProduct = document.getElementById('team_receive_product').value;

            // ตรวจสอบว่าข้อมูลฟอร์มครบถ้วน
            if (!receiptSlipNumber || !department || !productCheckerId) {
                alert('กรุณากรอกข้อมูลในฟอร์มให้ครบถ้วน');
                return;
            }

            // ส่งข้อมูลทั้งหมดที่เก็บไว้ไปยัง server
            fetch(`{{ route('SaveReceiptProduct') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        date: date,
                        shift_id: shift,
                        receiptSlipNumber: receiptSlipNumber,
                        department: department,
                        productCheckerId: productCheckerId,
                        domesticCheckerId: domesticCheckerId,
                        receiptData: receiptData,
                        teamReceiveProduct: teamReceiveProduct,
                    }),
                })
                .then((response) => {
                    // console.log(data);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    alert(data.message);
                    location.reload();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
