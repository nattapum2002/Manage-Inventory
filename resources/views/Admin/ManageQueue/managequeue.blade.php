@extends('layouts.master')

@section('title')
จัดการคิวลูกค้า
@endsection

@section('content')
    <main>
        <section class="card">
            <article class="card-header">
                รายชื่อคิวลูกค้า
            </article>
            <article class="card-body">
                <table id="queue-all-table" class="table table-striped">
                    <thead>
                        <th>รหัสคิวลูกค้า</th>
                        <th>วันที่</th>
                        <th>เกรด</th>
                        <th>ชื่อลูกค้า</th>
                        <th>เบอร์โทรศัพท์</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>2022-01-01</td>
                            <td>A</td>
                            <td>นายสมชาย</td>
                            <td>090-1234567</td>
                            <td>ยังไม่รับ</td>
                            <td>
                                <a href="" class="btn btn-primary">ดู</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </article>
        </section>
    </main>
@endsection