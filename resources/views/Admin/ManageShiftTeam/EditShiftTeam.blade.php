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
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-1 col-md-1 col-sm-12">
                                    <div class="form-group">
                                        <label for="shift_name">กะ</label>
                                        <input type="text" class="form-control" id="shift_name" name="shift_name"
                                            value="{{ $ShiftTeams['shift_name'] }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="start_shift">เวลาเริ่ม</label>
                                        <input type="text" class="form-control" id="start_shift" name="start_shift"
                                            value="{{ (new DateTime($ShiftTeams['start_shift']))->format('H:i') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="end_shift">เวลาเลิก</label>
                                        <input type="text" class="form-control" id="end_shift" name="end_shift"
                                            value="{{ (new DateTime($ShiftTeams['end_shift']))->format('H:i') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="date">วันที่</label>
                                        <input type="text" class="form-control" id="date" name="date"
                                            value="{{ (new DateTime($ShiftTeams['date']))->format('d/m/Y') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-12">
                                    <div class="form-group">
                                        <label for="note">หมายเหตุ</label>
                                        <input type="text" class="form-control" id="note" name="note"
                                            value="{{ $ShiftTeams['note'] }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal-xl">
                                        เพิ่มทีม
                                    </button>
                                    <div class="modal fade" id="modal-xl">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <form action="" method="POST">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">เพิ่มทีม</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="team_name">ทีม</label>
                                                                    <input type="text" class="form-control"
                                                                        id="team_name" name="team_name">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="work">ลักษณะงาน</label>
                                                                    <input type="text" class="form-control"
                                                                        id="work" name="work">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="note">หมายเหตุ</label>
                                                                    <input type="text" class="form-control"
                                                                        id="note" name="note">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <article>
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="user_id[0]"
                                                                            class="form-label">รหัสพนักงาน</label>
                                                                        <input type="text" class="form-control"
                                                                            id="user_id0" name="user_id[0]" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="name[0]"
                                                                            class="form-label">ชื่อ</label>
                                                                        <input type="text" class="form-control"
                                                                            id="name0" name="name[0]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="surname[0]"
                                                                            class="form-label">นามสกุล</label>
                                                                        <input type="text" class="form-control"
                                                                            id="surname0" name="surname[0]" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="position[0]"
                                                                            class="form-label">ตำแหน่ง</label>
                                                                        <input type="text" class="form-control"
                                                                            id="position0" name="position[0]" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </article>
                                                        <article id="add-user-team">

                                                        </article>
                                                        <div class="d-flex justify-content-center mt-3">
                                                            <button type="button" class="btn btn-primary mr-3"
                                                                id="add-user">เพิ่มพนักงาน</button>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-danger"
                                                            data-dismiss="modal">ยกเลิก</button>
                                                        <button type="submit" class="btn btn-success">บันทึก</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @foreach ($ShiftTeams['teams'] as $team)
                                <div class="row">
                                    <div class="col-12">
                                        @if ($team['team_id'] != null)
                                            <hr>

                                            <table id="" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th colspan="5">
                                                            <div class="row">
                                                                <div class="col-lg-2 col-md-3 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="team_name">ทีม</label>
                                                                        <input type="text" class="form-control"
                                                                            id="team_name" name="team_name"
                                                                            value="{{ $team['team_name'] ?? 'N/A' }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-md-3 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="work">ลักษณะงาน</label>
                                                                        <input type="text" class="form-control"
                                                                            id="work" name="work"
                                                                            value="{{ $team['work'] ?? 'N/A' }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="foreman">โฟร์แมน</label>
                                                                        <input type="text" class="form-control"
                                                                            id="foreman" name="foreman"
                                                                            value="{{ $team['users']->where('dmc_position', 'โฟร์แมน')[0]['name'] ?? 'N/A' }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-5 col-md-3 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="note">หมายเหตุ</label>
                                                                        <input type="text" class="form-control"
                                                                            id="note" name="note"
                                                                            value="{{ $team['note'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>รหัสพนักงาน</th>
                                                        <th>ชื่อพนักงาน</th>
                                                        <th>ตําแหน่งใน DMC</th>
                                                        <th>หมายเหตุ</th>
                                                        <th>จัดการ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($team['users'] as $user)
                                                        <tr>
                                                            <td>{{ $user['user_id'] ?? 'N/A' }}</td>
                                                            <td>{{ $user['name'] ?? 'N/A' }}</td>
                                                            <td>{{ $user['dmc_position'] ?? 'N/A' }}</td>
                                                            <td>{{ $user['note'] ?? 'N/A' }}</td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            {{-- <p class="d-flex justify-content-center">กรุณาเพิ่มทีมพนักงาน</p> --}}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
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
@endsection
