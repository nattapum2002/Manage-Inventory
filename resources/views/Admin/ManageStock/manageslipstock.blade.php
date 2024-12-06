@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง : สลิป {{ $date }}
@endsection

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-start">
                <a class="btn btn-primary" href="{{ route('Add item') }}">เพิ่มสินค้าเข้าคลัง</a>
            </div>
            <div class="card-body">
                <table id="slip_per_date" class="table table-striped">
                    <thead>
                        <tr>
                            <th>เลขที่</th>
                            <th class="text-center">สลิปใบที่ No.</th>
                            <th>แผนก</th>
                            <th>Production Checker</th>
                            <th>Domestic Checker</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($show_slip as $item)
                            <tr>
                                <td>{{ $item->slip_id }}</td>
                                <td class="text-center">{{ $item->slip_number }}</td>
                                <td>{{ $item->department }}</td>
                                <td>{{ $item->product_checker ?? 'ไม่มี' }}</td>
                                <td>{{ $item->domestic_checker ?? 'ไม่มี' }}</td>
                                <td>{!! $item->status == 1 ? '<p class="text-success">ตรวจสอบแล้ว</p>' : '<p class="text-danger">รอตรวจสอบ</p>' !!}</td>
                                <td>
                                    <a class="btn btn-primary" href="{{ route('SlipDetail', $item->id) }}">ดู</a>
                                    {{-- <a class="btn btn-danger" href="">ลบ</a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
