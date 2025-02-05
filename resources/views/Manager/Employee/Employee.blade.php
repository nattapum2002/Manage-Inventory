@extends('layouts.master')

@section('title')
    พนักงาน
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">พนักงาน</h3>
                        </div>
                        <div class="card-body">
                            <table id="EmployeeTable" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>รหัส</th>
                                        <th>ชื่อ</th>
                                        <th>นามสกุล</th>
                                        <th class="text-center">level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->user_id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->surname }}</td>
                                            <td class="text-center">{{ $user->level }}</td>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>รหัส</th>
                                        <th>ชื่อ</th>
                                        <th>นามสกุล</th>
                                        <th>level</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        function initDataTable(selector) {
            return $(selector).DataTable({
                info: false,
                scrollX: true,
                ordering: true,
                paging: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50],
                order: [],
            });
        }

        const EmployeeTable = initDataTable("#EmployeeTable");
    </script>
@endsection
