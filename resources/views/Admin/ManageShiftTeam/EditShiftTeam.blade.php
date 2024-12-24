@extends('layouts.master')

@section('title')
    แก้ไขกะและทีมพนักงาน
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>รายละเอียด</h5>
                        </div>
                        <form action="{{ route('SaveEditShift', $ShiftTeams['shift_id']) }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shift_name">กะ</label>
                                            <input type="text" class="form-control" id="shift_name" name="shift_name"
                                                value="{{ $ShiftTeams['shift_name'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="start_shift">เวลาเริ่ม</label>
                                            <input type="text" class="form-control" id="start_shift" name="start_shift"
                                                value="{{ (new DateTime($ShiftTeams['start_shift']))->format('H:i') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="end_shift">เวลาเลิก</label>
                                            <input type="text" class="form-control" id="end_shift" name="end_shift"
                                                value="{{ (new DateTime($ShiftTeams['end_shift']))->format('H:i') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="date">วันที่</label>
                                            <input type="text" class="form-control" id="date" name="date"
                                                value="{{ (new DateTime($ShiftTeams['date']))->format('d/m/Y') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <textarea type="text" class="form-control" id="note" name="note">{{ $ShiftTeams['note'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">
                                    บันทึก
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-center">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddTeamShift">
                        เพิ่มทีม
                    </button>
                </div>
            </div>
            <div class="row">
                @foreach ($ShiftTeams['teams'] as $team)
                    {{-- @dd($team) --}}
                    @if ($team['team_id'] != null)
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label for="team_name">ทีม</label>
                                                <input type="text" class="form-control" id="team_name" name="team_name"
                                                    value="{{ $team['team_name'] ?? 'N/A' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label for="work">ลักษณะงาน</label>
                                                <input type="text" class="form-control" id="work" name="work"
                                                    value="{{ $team['work'] ?? 'N/A' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label for="foreman">โฟร์แมน</label>
                                                <input type="text" class="form-control" id="foreman" name="foreman"
                                                    value="{{ $team['users']->where('dmc_position', 'โฟร์แมน')[0]['name'] ?? 'N/A' }} {{ $team['users']->where('dmc_position', 'โฟร์แมน')[0]['surname'] ?? 'N/A' }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-3 col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="note">หมายเหตุ</label>
                                            <input type="text" class="form-control" id="note"
                                                name="note" value="{{ $team['note'] }}" readonly>
                                        </div>
                                    </div> --}}
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>#</label>
                                                <br>
                                                <div class="d-flex ">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#EditTeamShift"
                                                        data-team='@json($team)'
                                                        data-users='@json($team['users'])'>
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    {{-- <a href="{{ route('DeleteTeam', ['Shift_id' => $ShiftTeams['shift_id'], 'team_id' => $team['team_id']]) }}"
                                                    class="btn btn-danger"><i class="fas fa-trash"></i></a> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>รหัสพนักงาน</th>
                                                <th>ชื่อพนักงาน</th>
                                                <th>ตําแหน่งในทีม</th>
                                                {{-- <th>หมายเหตุ</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($team['users'] as $user)
                                                <tr>
                                                    <td>{{ $user['user_id'] ?? 'N/A' }}</td>
                                                    <td>{{ $user['name'] ?? 'N/A' }} {{ $user['surname'] ?? 'N/A' }}</td>
                                                    <td>{{ $user['dmc_position'] ?? 'N/A' }}</td>
                                                    {{-- <td>{{ $user['note'] ?? 'N/A' }}</td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- <p class="d-flex justify-content-center">กรุณาเพิ่มทีมพนักงาน</p> --}}
                    @endif
                @endforeach
            </div>
            <div class="modal fade" id="AddTeamShift">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form action="{{ route('SaveAddTeam') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h4 class="modal-title">เพิ่มทีม</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>ทีม</label>
                                            <input type="text" class="form-control" name="team_name">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>ลักษณะงาน</label>
                                            <input type="text" class="form-control" name="work">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label>หมายเหตุ</label>
                                            <input type="text" class="form-control" name="note">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div>
                                    <div class="row mb-3">
                                        <div class="col-lg-2 col-md-4 col-sm-12">
                                            <label for="user_id">รหัสพนักงาน</label>
                                            <input type="text" class="form-control" id="user_id0" name="user_id[0]"
                                                readonly>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12">
                                            <label for="name">ชื่อ</label>
                                            <input type="text" class="form-control" id="name0" name="name[0]">
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12">
                                            <label for="surname">นามสกุล</label>
                                            <input type="text" class="form-control" id="surname0" name="surname[0]"
                                                disabled>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12">
                                            <label for="position">ตําแหน่ง</label>
                                            <input type="text" class="form-control" id="position0" name="position[0]"
                                                disabled>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12">
                                            <label for="dmc_position">ตําแหน่งในทีม</label>
                                            <input type="text" class="form-control" name="dmc_position[0]">
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12">
                                            <label for="#">#</label>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div id="add-user-team"></div>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-primary" id="add-user">เพิ่มพนักงาน</button>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="shift_id" value="{{ $ShiftTeams['shift_id'] }}">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-success">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="EditTeamShift">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form action="{{ route('SaveEditTeam') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h4 class="modal-title">แก้ไขทีม</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>ชื่อทีม</label>
                                            <input type="text" class="form-control" id="edit_team_name"
                                                name="team_name">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>ลักษณะงาน</label>
                                            <input type="text" class="form-control" id="edit_work" name="work">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label>หมายเหตุ</label>
                                            <input type="text" class="form-control" id="edit_note" name="note">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div id="edit-user-team"></div>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-primary"
                                        id="edit-add-user">เพิ่มพนักงาน</button>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" id="team_id" name="team_id">
                                <input type="hidden" name="shift_id" value="{{ $ShiftTeams['shift_id'] }}">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-success">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('style')
    <style>
        .accordion {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
        }

        .active,
        .accordion:hover {
            background-color: #eee;
        }

        .panel {
            padding: 0 18px;
            display: block;
            background-color: white;
            overflow: hidden;
        }

        .ui-autocomplete {
            z-index: 1060;
            position: absolute;
        }
    </style>
@endsection

@section('script')
    <script>
        new DataTable('table.display', {
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
    </script>

    <script>
        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display == "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }

        // เปิด accordion แรกเป็นค่าเริ่มต้น
        // acc[0].classList.add("active");
        // acc[0].nextElementSibling.style.display = "block";
    </script>

    <script>
        let userCount = 0;

        // 1️⃣ ฟังก์ชันช่วยเหลือต่างๆ
        function createUserRow(index, user = {}) {
            return `
            <div class="row mb-3" id="user-${index}">
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for="user_id">รหัสพนักงาน</label>
                    <input type="text" class="form-control" id="user_id${index}" name="user_id[${index}]" value="${user.user_id || ''}" readonly>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for="name">ชื่อ</label>
                    <input type="text" class="form-control" id="name${index}" name="name[${index}]" value="${user.name || ''}">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for="surname">นามสกุล</label>
                    <input type="text" class="form-control" id="surname${index}" name="surname[${index}]" value="${user.surname || ''}" disabled>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for="position">ตําแหน่ง</label>
                    <input type="text" class="form-control" id="position${index}" name="position[${index}]" value="${user.position || ''}" disabled>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for="dmc_position">ตําแหน่งในทีม</label>
                    <input type="text" class="form-control" name="dmc_position[${index}]" value="${user.dmc_position || ''}">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for="#">#</label><br>
                    <button type="button" class="btn btn-danger remove-user"><i class="fas fa-trash"></i></button>
                </div>
            </div><hr>`;
        }

        function initializeModal(modalId, teamData, users) {
            $(`#${modalId} #team_id`).val(teamData.team_id);
            $(`#${modalId} #edit_team_name`).val(teamData.team_name);
            $(`#${modalId} #edit_work`).val(teamData.work);
            $(`#${modalId} #edit_note`).val(teamData.note);
            $(`#${modalId} #edit-user-team`).html('');

            users.forEach((user, index) => {
                $(`#${modalId} #edit-user-team`).append(createUserRow(index, user));
                initializeTeamAutocomplete(`#name${index}`, `#user_id${index}`, `#surname${index}`,
                    `#position${index}`);
            });
        }

        function initializeTeamAutocomplete(nameSelector, idSelector, surnameSelector, positionSelector) {
            $(nameSelector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('AutoCompleteTeam') }}",
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

        // 2️⃣ ฟังก์ชันเพิ่มแถวผู้ใช้
        function addUserRow(containerSelector) {
            userCount++;
            const userRow = createUserRow(userCount);
            $(containerSelector).append(userRow);
            initializeTeamAutocomplete(`#name${userCount}`, `#user_id${userCount}`, `#surname${userCount}`,
                `#position${userCount}`);
        }

        // 3️⃣ การทำงานต่างๆ ที่เกี่ยวข้องกับปุ่มและโมดอล
        $(document).on('click', '#add-user', function() {
            addUserRow('#add-user-team');
        });

        $(document).on('click', '#edit-add-user', function() {
            addUserRow('#edit-user-team');
        });

        $(document).on('click', '.remove-user', function() {
            $(this).closest('[id^="user-"]').remove();
        });

        $('#EditTeamShift').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let teamData = button.data('team');
            let users = button.data('users');
            initializeModal('EditTeamShift', teamData, users);
        });

        // ตรวจสอบว่ามีช่อง name0 หรือไม่ ถ้ามีให้เรียก initializeTeamAutocomplete
        if ($('#name0').length) {
            initializeTeamAutocomplete('#name0', '#user_id0', '#surname0', '#position0');
        }
    </script>
@endsection
