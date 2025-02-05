@extends('layouts.master')

@section('title')
    ตั้งค่า
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    {{-- ห้องเก็บ --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ห้องเก็บ</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <table id="WarehouseTable"
                                        class="table table-bordered table-striped table-sm table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">รหัส</th>
                                                <th class="text-center">ห้องเก็บ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div id="warehouseEdit">
                                        <div class="row">
                                            <h3 class="card-title">เพิ่มห้องเก็บ</h3>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="warehouse_add_name">ห้องเก็บ</label>
                                                    <input type="text" class="form-control" id="warehouse_add_name"
                                                        name="warehouse_add_name" placeholder="กรุณากรอกห้องเก็บ">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary"
                                                    id="warehouse_add">บันทึก</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ลักษณะงาน --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ลักษณะงาน</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <table id="ProductWorkDescTable"
                                        class="table table-bordered table-striped table-sm table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">รหัส</th>
                                                <th class="text-center">ลักษณะงาน</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div id="productWorkDescEdit">
                                        <div class="row">
                                            <h3 class="card-title">เพิ่มลักษณะงาน</h3>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="product_work_desc_add_name">ลักษณะงาน</label>
                                                    <input type="text" class="form-control"
                                                        id="product_work_desc_add_name" placeholder="กรุณากรอกลักษณะงาน">
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary"
                                                    id="product_work_desc_add">บันทึก</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ประเภทพาเลท --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ประเภทพาเลท</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <table id="PalletTypeTable"
                                        class="table table-bordered table-striped table-sm table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">รหัส</th>
                                                <th class="text-center">ประเภทพาเลท</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div id="palletTypeEdit">
                                        <div class="row">
                                            <h3 class="card-title">เพิ่มประเภทพาเลท</h3>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="pallet_type_add_name">ประเภทพาเลท</label>
                                                    <input type="text" class="form-control" id="pallet_type_add_name"
                                                        placeholder="กรุณากรอกประเภทพาเลท">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary"
                                                    id="pallet_type_add">บันทึก</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- กะ --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ประเภทกะ</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <table id="ShiftTypeTable"
                                        class="table table-bordered table-striped table-sm table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">รหัส</th>
                                                <th class="text-center">ประเภทกะ</th>
                                                <th class="text-center">เวลาเริ่มงาน</th>
                                                <th class="text-center">เวลาเลิกงาน</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div id="shiftTypeEdit">
                                        <div class="row">
                                            <h3 class="card-title">เพิ่มประเภทกะ</h3>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="shift_type_add_name">ประเภทกะ</label>
                                                    <input type="text" class="form-control" id="shift_type_add_name"
                                                        placeholder="กรุณากรอกประกะ">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="shift_type_add_start_time">เวลาเริ่มงาน</label>
                                                    <input type="time" class="form-control"
                                                        id="shift_type_add_start_time" placeholder="กรุณากรอกเวลาเริ่ม">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="shift_type_add_end_time">เวลาเลิกงาน</label>
                                                    <input type="time" class="form-control"
                                                        id="shift_type_add_end_time" placeholder="กรุณากรอกเวลาเลิก">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary"
                                                    id="shift_type_add">บันทึก</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // ฟังก์ชันสร้าง DataTable
            function initDataTable(selector) {
                return $(selector).DataTable({
                    info: false,
                    scrollX: true,
                    ordering: true,
                    paging: true,
                    pageLength: 5,
                    lengthMenu: [5, 10, 20],
                    order: [],
                    createdRow: function(row) {
                        $(row).css("cursor", "pointer");
                    },
                });
            }

            // ฟังก์ชันเพื่อดึงข้อมูล (Refactor)
            function fetchData(route, data = {}) {
                return fetch(route, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify(data),
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('เกิดข้อผิดพลาด โปรดลองใหม่อีกครั้ง');
                    });
            }

            // กำหนดตัวแปรตาราง
            const warehouseTable = initDataTable("#WarehouseTable");
            const productWorkDescTable = initDataTable("#ProductWorkDescTable");
            const palletTypeTable = initDataTable("#PalletTypeTable");
            const shiftTypeTable = initDataTable("#ShiftTypeTable");

            // โหลดข้อมูล
            function loadWarehouse() {
                fetchData("{{ route('getSetData') }}")
                    .then(data => {
                        warehouseTable.clear();
                        if (data.warehouse && data.warehouse.length > 0) {
                            const newRows = data.warehouse.map(item => [
                                item.id,
                                item.warehouse_name,
                            ]);
                            warehouseTable.rows.add(newRows).draw();
                        }
                    });
            }

            function loadProductWorkDesc() {
                fetchData("{{ route('getSetData') }}")
                    .then(data => {
                        productWorkDescTable.clear();
                        if (data.productWorkDesc && data.productWorkDesc.length > 0) {
                            const newRows = data.productWorkDesc.map(item => [
                                item.id,
                                item.product_work_desc,
                            ]);
                            productWorkDescTable.rows.add(newRows).draw();
                        }
                    });
            }

            function loadPalletType() {
                fetchData("{{ route('getSetData') }}")
                    .then(data => {
                        palletTypeTable.clear();
                        if (data.palletType && data.palletType.length > 0) {
                            const newRows = data.palletType.map(item => [
                                item.id,
                                item.pallet_type,
                            ]);
                            palletTypeTable.rows.add(newRows).draw();
                        }
                    });
            }

            function loadShiftType() {
                fetchData("{{ route('getSetData') }}")
                    .then(data => {
                        shiftTypeTable.clear();
                        if (data.shift && data.shift.length > 0) {
                            const newRows = data.shift.map(item => [
                                item.shift_time_id,
                                item.shift_name,
                                formatTime(item.start_shift),
                                formatTime(item.end_shift),
                            ]);
                            shiftTypeTable.rows.add(newRows).draw();
                        }
                    });
            }

            function formatTime(time) {
                const parts = time.split(':');
                if (parts.length < 2) return "Invalid Time";
                const hours = parts[0].padStart(2, '0');
                const minutes = parts[1].padStart(2, '0');
                return `${hours}:${minutes}`;
            }

            // โหลดข้อมูลทั้งหมด
            function loadAllData() {
                loadWarehouse();
                loadProductWorkDesc();
                loadPalletType();
                loadShiftType();
            }

            function warehouseEditDefault() {
                $("#warehouseEdit").html(`
                    <div class="row">
                        <h3 class="card-title">เพิ่มห้องเก็บ</h3>
                        <div class="col">
                            <div class="form-group">
                                <label for="warehouse_add_name">ห้องเก็บ</label>
                                <input type="text" class="form-control" id="warehouse_add_name" placeholder="กรุณากรอกห้องเก็บ">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" id="warehouse_add">บันทึก</button>
                        </div>
                    </div>
                `);
            }

            // คลิกเพื่อแก้ไข warehouse
            $("#WarehouseTable tbody").on("click", "tr", function() {
                var data = warehouseTable.row(this).data();
                var warehouse_id = data[0];
                var warehouse_name = data[1];

                $("#warehouseEdit").html(`
                    <div class="row">
                        <h3 class="card-title">แก้ไขห้องเก็บ</h3>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="warehouse_edit_id">รหัส</label>
                                <input type="text" class="form-control" id="warehouse_edit_id" value="${warehouse_id}" readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="warehouse_edit_name">ห้องเก็บ</label>
                                <input type="text" class="form-control" id="warehouse_edit_name" value="${warehouse_name}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button class="btn btn-primary" id="warehouse_save">บันทึก</button>
                                    <button class="btn btn-danger" id="warehouse_cancel">ยกเลิก</button>
                                </div>
                                <div>
                                    <button class="btn btn-warning align-right" id="warehouse_delete">ลบ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });

            // กดปุ่มยกเลิก กลับไปหน้าเพิ่มข้อมูล
            $(document).on("click", "#warehouse_cancel", function() {
                warehouseEditDefault();
            });

            // เพิ่มข้อมูล warehouse
            $(document).on("click", "#warehouse_add", function() {
                var warehouse_name = $("#warehouse_add_name").val();
                fetchData("{{ route('SaveAddSetData') }}", {
                        warehouse_name: warehouse_name
                    })
                    .then(data => {
                        loadWarehouse();
                        warehouseEditDefault();
                    });
            });

            $(document).on("click", "#warehouse_save", function() {
                var warehouse_id = $("#warehouse_edit_id").val();
                var warehouse_name = $("#warehouse_edit_name").val();
                fetchData("{{ route('SaveUpdateSetData') }}", {
                        warehouse_id: warehouse_id,
                        warehouse_name: warehouse_name
                    })
                    .then(data => {
                        loadWarehouse();
                        warehouseEditDefault();
                    });
            });

            $(document).on("click", "#warehouse_delete", function() {
                var warehouse_id = $("#warehouse_edit_id").val();
                fetchData("{{ route('DeleteSetData') }}", {
                        warehouse_id: warehouse_id
                    })
                    .then(data => {
                        loadWarehouse();
                        warehouseEditDefault();
                    });
            });

            // ฟังก์ชันสำหรับฟอร์มเริ่มต้นของ ProductWorkDesc
            function productWorkDescEditDefault() {
                $("#productWorkDescEdit").html(`
                    <div class="row">
                        <h3 class="card-title">เพิ่มคำอธิบายงานผลิต</h3>
                        <div class="col">
                            <div class="form-group">
                                <label for="product_work_desc_add_name">คำอธิบายงานผลิต</label>
                                <input type="text" class="form-control" id="product_work_desc_add_name" placeholder="กรุณากรอกคำอธิบายงานผลิต">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" id="product_work_desc_add">บันทึก</button>
                        </div>
                    </div>
                `);
            }

            // คลิกเพื่อแก้ไข ProductWorkDesc
            $("#ProductWorkDescTable tbody").on("click", "tr", function() {
                var data = productWorkDescTable.row(this).data();
                var product_work_desc_id = data[0];
                var product_work_desc_name = data[1];

                $("#productWorkDescEdit").html(`
                    <div class="row">
                        <h3 class="card-title">แก้ไขคำอธิบายงานผลิต</h3>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="product_work_desc_edit_id">รหัส</label>
                                <input type="text" class="form-control" id="product_work_desc_edit_id" value="${product_work_desc_id}" readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="product_work_desc_edit_name">คำอธิบายงานผลิต</label>
                                <input type="text" class="form-control" id="product_work_desc_edit_name" value="${product_work_desc_name}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button class="btn btn-primary" id="product_work_desc_save">บันทึก</button>
                                    <button class="btn btn-danger" id="product_work_desc_cancel">ยกเลิก</button>
                                </div>
                                <div>
                                    <button class="btn btn-warning align-right" id="product_work_desc_delete">ลบ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });

            // กดปุ่มยกเลิก กลับไปหน้าเพิ่มข้อมูล
            $(document).on("click", "#product_work_desc_cancel", function() {
                productWorkDescEditDefault();
            });

            // เพิ่มข้อมูล ProductWorkDesc
            $(document).on("click", "#product_work_desc_add", function() {
                var product_work_desc_name = $("#product_work_desc_add_name").val();
                fetchData("{{ route('SaveAddSetData') }}", {
                        product_work_desc_name: product_work_desc_name
                    })
                    .then(data => {
                        loadProductWorkDesc();
                        productWorkDescEditDefault();
                    });
            });

            // แก้ไขข้อมูล ProductWorkDesc
            $(document).on("click", "#product_work_desc_save", function() {
                var product_work_desc_id = $("#product_work_desc_edit_id").val();
                var product_work_desc_name = $("#product_work_desc_edit_name").val();
                fetchData("{{ route('SaveUpdateSetData') }}", {
                        product_work_desc_id: product_work_desc_id,
                        product_work_desc_name: product_work_desc_name
                    })
                    .then(data => {
                        loadProductWorkDesc();
                        productWorkDescEditDefault();
                    });
            });

            // ลบข้อมูล ProductWorkDesc
            $(document).on("click", "#product_work_desc_delete", function() {
                var product_work_desc_id = $("#product_work_desc_edit_id").val();
                fetchData("{{ route('DeleteSetData') }}", {
                        product_work_desc_id: product_work_desc_id
                    })
                    .then(data => {
                        loadProductWorkDesc();
                        productWorkDescEditDefault();
                    });
            });

            // ฟังก์ชันสำหรับฟอร์มเริ่มต้นของ PalletType
            function palletTypeEditDefault() {
                $("#palletTypeEdit").html(`
                    <div class="row">
                        <h3 class="card-title">เพิ่มประเภทพาเลท</h3>
                        <div class="col">
                            <div class="form-group">
                                <label for="pallet_type_add_name">ประเภทพาเลท</label>
                                <input type="text" class="form-control" id="pallet_type_add_name" placeholder="กรุณากรอกประเภทพาเลท">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" id="pallet_type_add">บันทึก</button>
                        </div>
                    </div>
                `);
            }

            // คลิกเพื่อแก้ไข PalletType
            $("#PalletTypeTable tbody").on("click", "tr", function() {
                var data = palletTypeTable.row(this).data();
                var pallet_type_id = data[0];
                var pallet_type_name = data[1];

                $("#palletTypeEdit").html(`
                    <div class="row">
                        <h3 class="card-title">แก้ไขประเภทพาเลท</h3>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="pallet_type_edit_id">รหัส</label>
                                <input type="text" class="form-control" id="pallet_type_edit_id" value="${pallet_type_id}" readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="pallet_type_edit_name">ประเภทพาเลท</label>
                                <input type="text" class="form-control" id="pallet_type_edit_name" value="${pallet_type_name}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button class="btn btn-primary" id="pallet_type_save">บันทึก</button>
                                    <button class="btn btn-danger" id="pallet_type_cancel">ยกเลิก</button>
                                </div>
                                <div>
                                    <button class="btn btn-warning align-right" id="pallet_type_delete">ลบ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });

            // กดปุ่มยกเลิก กลับไปหน้าเพิ่มข้อมูล
            $(document).on("click", "#pallet_type_cancel", function() {
                palletTypeEditDefault();
            });

            // เพิ่มข้อมูล PalletType
            $(document).on("click", "#pallet_type_add", function() {
                var pallet_type_name = $("#pallet_type_add_name").val();
                fetchData("{{ route('SaveAddSetData') }}", {
                        pallet_type_name: pallet_type_name
                    })
                    .then(data => {
                        loadPalletType();
                        palletTypeEditDefault();
                    });
            });

            // แก้ไขข้อมูล PalletType
            $(document).on("click", "#pallet_type_save", function() {
                var pallet_type_id = $("#pallet_type_edit_id").val();
                var pallet_type_name = $("#pallet_type_edit_name").val();
                fetchData("{{ route('SaveUpdateSetData') }}", {
                        pallet_type_id: pallet_type_id,
                        pallet_type_name: pallet_type_name
                    })
                    .then(data => {
                        loadPalletType();
                        palletTypeEditDefault();
                    });
            });

            // ลบข้อมูล PalletType
            $(document).on("click", "#pallet_type_delete", function() {
                var pallet_type_id = $("#pallet_type_edit_id").val();
                fetchData("{{ route('DeleteSetData') }}", {
                        pallet_type_id: pallet_type_id
                    })
                    .then(data => {
                        loadPalletType();
                        palletTypeEditDefault();
                    });
            });
            //----------------------------------------------------------------------------------
            // ฟังก์ชันสำหรับฟอร์มเริ่มต้นของ ShiftType
            function shiftTypeEditDefault() {
                $("#shiftTypeEdit").html(`
                    <div class="row">
                        <h3 class="card-title">เพิ่มประเภทกะ</h3>
                        <div class="col">
                            <div class="form-group">
                                <label for="shift_type_add_name">ประเภทกะ</label>
                                <input type="text" class="form-control" id="shift_type_add_name"
                                    placeholder="กรุณากรอกประกะ">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="shift_type_add_start_time">เวลาเริ่มงาน</label>
                                <input type="time" class="form-control"
                                    id="shift_type_add_start_time" placeholder="กรุณากรอกเวลาเริ่ม">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="shift_type_add_end_time">เวลาเลิกงาน</label>
                                <input type="time" class="form-control"
                                    id="shift_type_add_end_time" placeholder="กรุณากรอกเวลาเลิก">
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"
                                id="shift_type_add">บันทึก</button>
                        </div>
                    </div>
                `);
            }

            // คลิกเพื่อแก้ไข ShiftType
            $("#ShiftTypeTable tbody").on("click", "tr", function() {
                var data = shiftTypeTable.row(this).data();
                var shift_type_id = data[0];
                var shift_type_name = data[1];
                var shift_type_start_time = data[2];
                var shift_type_end_time = data[3];

                $("#shiftTypeEdit").html(`
                    <div class="row">
                        <h3 class="card-title">แก้ไขประเภทกะ</h3>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="shift_type_edit_id">รหัส</label>
                                <input type="text" class="form-control" id="shift_type_edit_id" value="${shift_type_id}" readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="shift_type_edit_name">ประเภทกะ</label>
                                <input type="text" class="form-control" id="shift_type_edit_name" value="${shift_type_name}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="shift_type_edit_start_time">เวลาเริ่มงาน</label>
                                <input type="time" class="form-control" id="shift_type_edit_start_time" value="${shift_type_start_time}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="shift_type_edit_end_time">เวลาเลิกงาน</label>
                                <input type="time" class="form-control" id="shift_type_edit_end_time" value="${shift_type_end_time}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button class="btn btn-primary" id="shift_type_save">บันทึก</button>
                                    <button class="btn btn-danger" id="shift_type_cancel">ยกเลิก</button>
                                </div>
                                <div>
                                    <button class="btn btn-warning align-right" id="shift_type_delete">ลบ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });

            // กดปุ่มยกเลิก กลับไปหน้าเพิ่มข้อมูล
            $(document).on("click", "#shift_type_cancel", function() {
                shiftTypeEditDefault();
            });

            // เพิ่มข้อมูล ShiftType
            $(document).on("click", "#shift_type_add", function() {
                var shift_type_name = $("#shift_type_add_name").val();
                var shift_type_start_time = $("#shift_type_add_start_time").val();
                var shift_type_end_time = $("#shift_type_add_end_time").val();
                fetchData("{{ route('SaveAddSetData') }}", {
                        shift_type_name: shift_type_name,
                        shift_type_start_time: shift_type_start_time,
                        shift_type_end_time: shift_type_end_time,
                    })
                    .then(data => {
                        loadShiftType();
                        shiftTypeEditDefault();
                    });
            });

            // แก้ไขข้อมูล ShiftType
            $(document).on("click", "#shift_type_save", function() {
                var shift_type_id = $("#shift_type_edit_id").val();
                var shift_type_name = $("#shift_type_edit_name").val();
                var shift_type_start_time = $("#shift_type_edit_start_time").val();
                var shift_type_end_time = $("#shift_type_edit_end_time").val();
                fetchData("{{ route('SaveUpdateSetData') }}", {
                        shift_type_id: shift_type_id,
                        shift_type_name: shift_type_name,
                        shift_type_start_time: shift_type_start_time,
                        shift_type_end_time: shift_type_end_time,
                    })
                    .then(data => {
                        loadShiftType();
                        shiftTypeEditDefault();
                    });
            });

            // ลบข้อมูล ShiftType
            $(document).on("click", "#shift_type_delete", function() {
                var shift_type_id = $("#shift_type_edit_id").val();
                fetchData("{{ route('DeleteSetData') }}", {
                        shift_type_id: shift_type_id
                    })
                    .then(data => {
                        loadShiftType();
                        shiftTypeEditDefault();
                    });
            });

            // โหลดข้อมูลทั้งหมดเมื่อหน้าเว็บโหลดเสร็จ
            loadAllData();
        });
    </script>
@endsection
