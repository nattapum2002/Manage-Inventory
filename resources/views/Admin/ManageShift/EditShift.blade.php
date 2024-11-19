@extends('layouts.master')

@section('title')
    รายละเอียดกะพนักงาน
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('SaveEditShift') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <input type="hidden" class="form-control" id="shift_id" name="shift_id"
                                        placeholder="รหัสกะพนักงาน" value="{{ $shifts[0]->shift_id }}">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_name">ชื่อกะพนักงาน</label>
                                            <select class="form-control" id="shift_name" name="shift_name">
                                                <option selected value="{{ $shifts[0]->shift_name }}">
                                                    {{ $shifts[0]->shift_name }}
                                                </option>
                                                @foreach ($filtered_shifts as $shift)
                                                    <option value="{{ $shift['select_name'] }}">{{ $shift['select_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="start_shift">เวลาเริ่มกะ</label>
                                            <input type="time" class="form-control" id="start_shift" name="start_shift"
                                                placeholder="เวลาเริ่มกะ"
                                                value="{{ isset($shifts[0]->start_shift) ? substr($shifts[0]->start_shift, 0, 5) : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="end_shift">เวลาเลิกกะ</label>
                                            <input type="time" class="form-control" id="end_shift" name="end_shift"
                                                placeholder="เวลาเลิกกะ"
                                                value="{{ isset($shifts[0]->end_shift) ? substr($shifts[0]->end_shift, 0, 5) : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <input type="text" class="form-control" id="note" name="note"
                                                placeholder="หมายเหตุ" value="{{ $shifts[0]->note }}">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table id="Shifttable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>รหัสพนักงาน</th>
                                            <th>ชื่อ</th>
                                            <th>นามสกุล</th>
                                            <th>ตําแหน่ง</th>
                                            {{-- <th>ประเภทผู้ใช้</th> --}}
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shifts as $shift)
                                            <tr>
                                                <td>
                                                    <span id="span_shift_user_id_{{ $shift->user_id }}">
                                                        {{ $shift->user_id }}
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="edit_shift_user_id_{{ $shift->user_id }}" name="user_id"
                                                        value="{{ $shift->user_id }}" style="display:none;" readonly>
                                                </td>
                                                <td>
                                                    <span id="span_shift_name_{{ $shift->user_id }}">
                                                        {{ $shift->name }}
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="edit_shift_name_{{ $shift->user_id }}" name="name"
                                                        value="{{ $shift->name }}" style="display:none;">
                                                </td>
                                                <td>
                                                    <span id="span_shift_surname_{{ $shift->user_id }}">
                                                        {{ $shift->surname }}
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="edit_shift_surname_{{ $shift->user_id }}" name="surname"
                                                        value="{{ $shift->surname }}" style="display:none;" disabled>
                                                </td>
                                                <td>
                                                    <span id="span_shift_position_{{ $shift->user_id }}">
                                                        {{ $shift->position }}
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="edit_shift_position_{{ $shift->user_id }}" name="position"
                                                        value="{{ $shift->position }}" style="display:none;" disabled>
                                                </td>
                                                {{-- <td>{{ $shift->user_type }}</td> --}}
                                                <td>

                                                    <input type="hidden" id="edit_shift_old_user_id_{{ $shift->user_id }}"
                                                        name="old_user_id" value="{{ $shift->user_id }}"
                                                        style="display:none;" disabled>
                                                    <button type="button" class="btn btn-primary edit_shift"
                                                        data-shift-id="{{ $shift->user_id }}">แก้ไข</button>
                                                    <button type="button" class="btn btn-danger"
                                                        id="cancel_edit_shift_{{ $shift->user_id }}"
                                                        style="display:none;">ยกเลิก</button>
                                                    <a href="{{ route('DeleteShift', ['shift_id' => $shift->shift_id, 'user_id' => $shift->user_id]) }}"
                                                        class="btn btn-danger"
                                                        style="{{ $shifts->count() < 2 ? 'display:none;' : '' }}">ลบ</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>รหัสพนักงาน</th>
                                            <th>ชื่อ</th>
                                            <th>นามสกุล</th>
                                            <th>ตําแหน่ง</th>
                                            {{-- <th>ประเภทผู้ใช้</th> --}}
                                            <th></th>
                                        </tr>
                                        <th colspan="5">
                                            <div class="row">
                                                <div class="col-9"></div>
                                                <div class="col-3">จำนวนพนักงาน : {{ $shifts->count() }}</div>
                                            </div>
                                        </th>
                                    </tfoot>
                                </table>
                                <hr>
                                <article>
                                    <h4>เพิ่มพนักงาน</h4>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="user_id[0]" class="form-label">รหัสพนักงาน</label>
                                                <input type="text" class="form-control" id="user_id0"
                                                    name="user_id[0]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="name[0]" class="form-label">ชื่อ</label>
                                                <input type="text" class="form-control" id="name0"
                                                    name="name[0]">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="surname[0]" class="form-label">นามสกุล</label>
                                                <input type="text" class="form-control" id="surname0"
                                                    name="surname[0]" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="position[0]" class="form-label">ตำแหน่ง</label>
                                                <input type="text" class="form-control" id="position0"
                                                    name="position[0]" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </article>

                                <article id="add-user-shift">

                                </article>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-primary mr-3"
                                        id="add-user">เพิ่มพนักงาน</button>
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
        $(document).ready(function() {
            let fields = ['user_id', 'name', 'surname', 'position', 'old_user_id'];

            function hideEdit(edit_shift_id) {
                fields.forEach(function(field) {
                    $('#span_shift_' + field + '_' + edit_shift_id).show();
                    $('#edit_shift_' + field + '_' + edit_shift_id).hide();
                });
                $('.save_shift_btn').text('แก้ไข').removeClass('btn-success')
                    .addClass('btn-primary').removeClass('save_shift_btn').addClass('edit_shift');
                $('#cancel_edit_shift_' + edit_shift_id).hide();
            }

            $(document).on('click', '.edit_shift', function() {
                let edit_shift_id = $(this).data('shift-id');
                fields.forEach(function(field) {
                    $('#span_shift_' + field + '_' + edit_shift_id).hide();
                    $('#edit_shift_' + field + '_' + edit_shift_id).show();
                });
                $('#cancel_edit_shift_' + edit_shift_id).show();
                $(this).text('บันทึก').removeClass('btn-primary').addClass('btn-success')
                    .removeClass('edit_shift').addClass('save_shift_btn').attr('type', 'button');

                $('#cancel_edit_shift_' + edit_shift_id).click(function() {
                    hideEdit(edit_shift_id);
                });

                // Initialize autocomplete for each field when edit mode is activated
                initializeEditShiftAutocomplete('#edit_shift_name_' + edit_shift_id,
                    '#edit_shift_user_id_' +
                    edit_shift_id, '#edit_shift_surname_' + edit_shift_id,
                    '#edit_shift_position_' + edit_shift_id);
            });

            $(document).on('click', '.save_shift_btn', function() {
                let edit_shift_id = $(this).data('shift-id');
                let data_shift = {};

                $.each(fields, function(index, field) {
                    data_shift[field] = $('#edit_shift_' + field + '_' + edit_shift_id)
                        .val();
                });

                $.ajax({
                    url: "{{ route('SaveEditShift') }}",
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        shift_id: {{ $shifts[0]->shift_id }},
                        shift_edit: data_shift,
                    },
                    success: function(response) {
                        if (response.status) {
                            fields.forEach(function(field) {
                                $('#span_team_' + field + '_' + edit_team_id).text(
                                    data_team[field]);
                            });
                            hideEdit(edit_team_id);
                            alert('บันทึกข้อมูลสำเร็จ');
                            console.log(response);
                        } else {
                            alert('เกิดข้อผิดพลาด: ' + response.data);
                            console.log(response);
                        }
                    },
                    error: function(xhr) {
                        alert('มีข้อผิดพลาดในการบันทึกข้อมูล: ' + xhr.responseJSON?.message ||
                            'Unknown error');
                        console.error(xhr);
                    }
                });
            });
        });

        function initializeEditShiftAutocomplete(nameSelector, idSelector, surnameSelector, positionSelector) {
            $(nameSelector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('AutoCompleteAddShift') }}",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 0,
                select: function(event, ui) {
                    $(idSelector).val(ui.item.user_id);
                    $(nameSelector).val(ui.item.name);
                    $(surnameSelector).val(ui.item.surname);
                    $(positionSelector).val(ui.item.position);
                }
            });

            $(nameSelector).focus(function() {
                $(this).autocomplete('search', '');
            });
        }
    </script>
@endsection
