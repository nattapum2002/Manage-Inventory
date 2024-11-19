@extends('layouts.master')

@section('title')
    เพิ่มทีมพนักงาน
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('SaveAddTeam') }}" method="POST">
                                @csrf
                                <article class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="team_id">รหัสทีม</label>
                                            <input type="text" class="form-control" id="team_id" name="team_id"
                                                placeholder="รหัสทีม">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="team_name">ชื่อทีม</label>
                                            <select class="form-control" id="team_name" name="team_name">
                                                <option selected value="">เลือกชื่อทีม</option>
                                                @foreach ($filtered_teams as $team)
                                                    <option value="{{ $team['select_name'] }}">{{ $team['select_name'] }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="date">วันที่</label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                placeholder="วันที่">
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
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="user_id[0]" class="form-label">รหัสพนักงาน</label>
                                                <input type="text" class="form-control" id="user_id0" name="user_id[0]"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="name[0]" class="form-label">ชื่อ</label>
                                                <input type="text" class="form-control" id="name0" name="name[0]">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="surname[0]" class="form-label">นามสกุล</label>
                                                <input type="text" class="form-control" id="surname0" name="surname[0]"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="position[0]" class="form-label">ตำแหน่ง</label>
                                                <input type="text" class="form-control" id="position0" name="position[0]"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
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
@endsection
