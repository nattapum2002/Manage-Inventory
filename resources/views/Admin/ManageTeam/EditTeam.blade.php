@extends('layouts.master')

@section('title')
    รายละเอียดทีมพนักงาน
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="Teamtable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="5">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">ชื่อทีม :
                                                    {{ $teams[0]->team_name }}</div>
                                                <div class="col-lg-3 col-md-6 col-sm-12"></div>
                                                <div class="col-lg-3 col-md-6 col-sm-12">วันที่ :
                                                    {{ $teams[0]->date }}</div>
                                                <div class="col-lg-3 col-md-6 col-sm-12"></div>
                                            </div>
                                        </th>
                                    </tr>
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
                                    @foreach ($teams as $team)
                                        <tr>
                                            <td>
                                                <span id="span_team_user_id_{{ $team->user_id }}">
                                                    {{ $team->user_id }}
                                                </span>
                                                <input type="text" class="form-control"
                                                    id="edit_team_user_id_{{ $team->user_id }}" name="user_id"
                                                    value="{{ $team->user_id }}" style="display:none;" readonly>
                                            </td>
                                            <td>
                                                <span id="span_team_name_{{ $team->user_id }}">
                                                    {{ $team->name }}
                                                </span>
                                                <input type="text" class="form-control"
                                                    id="edit_team_name_{{ $team->user_id }}" name="name"
                                                    value="{{ $team->name }}" style="display:none;">
                                            </td>
                                            <td>
                                                <span id="span_team_surname_{{ $team->user_id }}">
                                                    {{ $team->surname }}
                                                </span>
                                                <input type="text" class="form-control"
                                                    id="edit_team_surname_{{ $team->user_id }}" name="surname"
                                                    value="{{ $team->surname }}" style="display:none;" disabled>
                                            </td>
                                            <td>
                                                <span id="span_team_position_{{ $team->user_id }}">
                                                    {{ $team->position }}
                                                </span>
                                                <input type="text" class="form-control"
                                                    id="edit_team_position_{{ $team->user_id }}" name="position"
                                                    value="{{ $team->position }}" style="display:none;" disabled>
                                            </td>
                                            {{-- <td>{{ $team->user_type }}</td> --}}
                                            <td>
                                                <input type="hidden" id="edit_team_old_user_id_{{ $team->user_id }}"
                                                    name="old_user_id" value="{{ $team->user_id }}" style="display:none;"
                                                    disabled>
                                                <button type="button" class="btn btn-primary edit_team"
                                                    data-user_id="{{ $team->user_id }}">แก้ไข</button>
                                                <button type="button" class="btn btn-danger"
                                                    id="cancel_edit_team_{{ $team->user_id }}"
                                                    style="display:none;">ยกเลิก</button>
                                                <a href="{{ route('DeleteTeam', ['team_id' => $team->team_id, 'user_id' => $team->user_id]) }}"
                                                    class="btn btn-danger"
                                                    style="{{ $teams->count() < 2 ? 'display:none;' : '' }}">ลบ</a>
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
                                            <div class="col-3">จำนวนพนักงาน : {{ $teams->count() }}</div>
                                        </div>
                                    </th>
                                </tfoot>
                            </table>
                            <hr>
                            <form action="{{ route('AddTeam') }}" method="POST">
                                @csrf
                                <article style="display:none;" class="row">
                                    <input type="text" class="form-control" id="team_id" name="team_id"
                                        placeholder="รหัสทีมพนักงาน" value="{{ $teams[0]->team_id }}">
                                    <input type="text" class="form-control" id="team_name" name="team_name"
                                        placeholder="เลือกชื่อทีมพนักงาน" value="{{ $teams[0]->team_name }}">
                                    <input type="date" class="form-control" id="date" name="date"
                                        placeholder="วันที่" value="{{ $teams[0]->date }}">
                                    <input type="text" class="form-control" id="note" name="note"
                                        placeholder="หมายเหตุ" value="{{ $teams[0]->note }}">
                                </article>

                                <article id="add-user-team">

                                </article>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-primary mr-3" id="add-user">เพิ่มพนักงาน</button>
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
        var user_count = 1;

        $('#add-user').click(function() {
            user_count++;
            $('#add-user-team').append(`
                <div class="row" id="user-${user_count}">
                    <div class="col-11">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="user_id[${user_count}]" class="form-label">รหัสพนักงาน</label>
                                    <input type="text" class="form-control" id="user_id${user_count}" name="user_id[${user_count}]" readonly>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name[${user_count}]" class="form-label">ชื่อ</label>
                                    <input type="text" class="form-control" id="name${user_count}" name="name[${user_count}]">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="surname[${user_count}]" class="form-label">นามสกุล</label>
                                    <input type="text" class="form-control" id="surname${user_count}" name="surname[${user_count}]" disabled>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="position[${user_count}]" class="form-label">ตำแหน่ง</label>
                                    <input type="text" class="form-control" id="position${user_count}" name="position[${user_count}]" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-1">
                            <label for="remove-user" class="form-label">#</label>
                        <div class="form-group">
                            <button type="button" class="btn btn-danger remove-user">ลบ</button>
                        </div>
                    </div>
                </div>
            `);
            // เรียกใช้งาน autocomplete กับฟิลด์ที่เพิ่มใหม่
            initializeTeamAutocomplete(`#name${user_count}`, `#user_id${user_count}`, `#surname${user_count}`,
                `#position${user_count}`);
        });

        // ฟังก์ชันสำหรับลบแถว
        $(document).on('click', '.remove-user', function() {
            $(this).closest('[id^="user-"]').remove();
        });

        // ฟังก์ชันสำหรับ autocomplete
        function initializeTeamAutocomplete(nameSelector, idSelector, surnameSelector, positionSelector) {
            $(nameSelector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('AutoCompleteAddTeam') }}",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            console.log(data);
                            response(data); // ส่งข้อมูลผลลัพธ์ไปยัง autocomplete
                        }
                    });
                },
                minLength: 0, // เริ่มค้นหาหลังจากพิมพ์ไป 2 ตัวอักษร
                select: function(event, ui) {
                    // เมื่อเลือกสินค้า ให้เติมรหัสสินค้าในฟิลด์ item_id
                    $(idSelector).val(ui.item.user_id); // เติมรหัสสินค้าในช่องรหัสสินค้า
                    $(nameSelector).val(ui.item.name); // เติมชื่อสินค้าในช่องชื่อสินค้า
                    $(surnameSelector).val(ui.item.surname);
                    $(positionSelector).val(ui.item.position);
                }
            });

            $(nameSelector).focus(function() {
                $(this).autocomplete('search', ''); // ส่งค่าว่างเพื่อแสดง autocomplete ทันที
            });
        }
        initializeTeamAutocomplete(`#name0`, `#user_id0`, `#surname0`, `#position0`);
    </script>

    <script>
        $(document).ready(function() {
            let fields = ['user_id', 'name', 'surname', 'position', 'old_user_id'];

            function hideEdit(edit_team_id) {
                fields.forEach(function(field) {
                    $('#span_team_' + field + '_' + edit_team_id).show();
                    $('#edit_team_' + field + '_' + edit_team_id).hide();
                });
                $('.save_team_btn').text('แก้ไข').removeClass('btn-success')
                    .addClass('btn-primary').removeClass('save_team_btn').addClass('edit_team');
                $('#cancel_edit_team_' + edit_team_id).hide();
            }

            $(document).on('click', '.edit_team', function() {
                let edit_team_id = $(this).data('user_id');
                fields.forEach(function(field) {
                    $('#span_team_' + field + '_' + edit_team_id).hide();
                    $('#edit_team_' + field + '_' + edit_team_id).show();
                });
                $('#cancel_edit_team_' + edit_team_id).show();
                $(this).text('บันทึก').removeClass('btn-primary').addClass('btn-success')
                    .removeClass('edit_team').addClass('save_team_btn').attr('type', 'button');

                $('#cancel_edit_team_' + edit_team_id).click(function() {
                    hideEdit(edit_team_id);
                });

                // Initialize autocomplete for each field when edit mode is activated
                initializeEditTeamAutocomplete('#edit_team_name_' + edit_team_id,
                    '#edit_team_user_id_' +
                    edit_team_id, '#edit_team_surname_' + edit_team_id,
                    '#edit_team_position_' + edit_team_id);
            });

            $(document).on('click', '.save_team_btn', function() {
                let edit_team_id = $(this).data('user_id');
                let data_team = {};

                $.each(fields, function(index, field) {
                    data_team[field] = $('#edit_team_' + field + '_' + edit_team_id)
                        .val();
                });

                $.ajax({
                    url: "{{ route('SaveEditTeam') }}",
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        team_id: {{ $teams[0]->team_id }},
                        team_edit: data_team,

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

        function initializeEditTeamAutocomplete(nameSelector, idSelector, surnameSelector, positionSelector) {
            $(nameSelector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('AutoCompleteAddTeam') }}",
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
