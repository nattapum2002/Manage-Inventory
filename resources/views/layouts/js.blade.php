<script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>

<!-- jQuery -->
<script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- DataTables  & Plugins -->
{{-- <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('backend/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('backend/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script> --}}
<!-- CodeMirror -->
<script src="{{ asset('backend/plugins/codemirror/codemirror.js') }}"></script>
<script src="{{ asset('backend/plugins/codemirror/mode/css/css.js') }}"></script>
<script src="{{ asset('backend/plugins/codemirror/mode/xml/xml.js') }}"></script>
<script src="{{ asset('backend/plugins/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('backend/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="{{ asset('backend/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('backend/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('backend/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('backend/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('backend/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('backend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Bootstrap Switch -->
<script src="{{ asset('backend/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<!-- BS-Stepper -->
<script src="{{ asset('backend/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- dropzonejs -->
<script src="{{ asset('backend/plugins/dropzone/min/dropzone.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script
    src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.1.8/af-2.7.0/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/date-1.5.4/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.3/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.8.1/sp-2.3.3/sl-2.1.0/sr-1.4.1/datatables.min.js">
</script>
<script>
    var item_count = 0;
    $('#add-item').click(function() {
        item_count++;
        $('#item-row').append(`<div class="row" id="item-${item_count}">${item_count}<div class="col">
                    <label for="item_id" class="form-label">รหัสสินค้า</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="item_id" name="item_id[${item_count}]">
                    </div>
                </div>
                <div class="col">
                    <label for="item_name" class="form-label">ชื่อสินค้า</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="item_name" name="item_name[${item_count}]" disabled>
                    </div>
                </div>
                <div class="col">
                    <label for="item_amount" class="form-label">จำนวน</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="item_amount" name="item_amount[${item_count}]">
                    </div>
                </div>
                <div class="col">
                    <label for="item_weight" class="form-label">น้ำหนัก</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="item_weight" name="item_weight[${item_count}]">
                    </div>
                </div>
                <div class="col">
                    <label for="item_comment" class="form-label">หมายเหตุ</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="item_comment" name="item_comment[${item_count}]">
                    </div>
                </div>
                <div class="col">
                    <label for="remove-item" class="form-label">จัดการ</label>
                    <div class="input-group text-center">
                        <button type="button" class="btn btn-danger remove-item" id="">ลบ</button>
                    </div>
                </div>
            </div>

            `);
    })
    $(document).on('click', '.remove-item', function() {
        item_count--;
        $(this).closest('[id^="item-"]').remove();
    });
    $(document).ready(function() {
        // Trigger custom event when success message is found
        $(document).trigger('dataSavedEvent', '{{ session()->get('success') }}');

        alert(message);
    });
</script>
<script>
    $(document).ready(function() {
        $('#stock_per_date').DataTable({
            info: false,
            ordering: false,
            paging: true
        });
        $('#slip_per_date').DataTable({
            info: false,
            ordering: false,
            paging: true
        });
        $('#item_per_slip').DataTable({
            info: false,
            ordering: false,
            paging: true
        });
    });
</script>
<script>
    $(function() {
        $("#userstable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            // scrollX: true,
            layout: {
                topStart: {
                    buttons: [
                        'copy', 'excel', 'pdf'
                    ]
                }
            }
        });
        $("#product_storetable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            // scrollX: true,
            layout: {
                topStart: {
                    buttons: [
                        'copy', 'excel', 'pdf'
                    ]
                }
            }
        });
        $("#stocktable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            // scrollX: true,
            layout: {
                topStart: {
                    buttons: [
                        'copy', 'excel', 'pdf'
                    ]
                }
            }
        });
        $("#customer_ordertable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            // scrollX: true,
            // layout: {
            //     topStart: {
            //         buttons: [
            //             'copy', 'excel', 'pdf'
            //         ]
            //     }
            // }
        });
        $("#detail_customer_ordertable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            // scrollX: true,
            layout: {
                topStart: {
                    buttons: [
                        'copy', 'excel', 'pdf'
                    ]
                }
            }
        });
    });
</script>
