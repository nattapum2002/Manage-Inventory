@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง : รายวัน
@endsection

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-start">
                <a class="btn btn-primary" href="{{ route('Add item') }}">เพิ่มสินค้าเข้าคลัง</a>
            </div>
            <div class="card-body">
                <table id="stock_per_date" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">วันที่</th>
                            <th>จำนวนสลิป</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($show_per_date as $item)
                            <tr>
                                <td class="text-center">{{ (new DateTime($item->store_datetime))->format('d/m/Y') }}</td>
                                <td>{{ $item->total_slip }}</td>
                                <td>
                                    <a class="btn btn-primary"
                                        href="{{ route('ManageSlip', (new DateTime($item->store_datetime))->format('Y-m-d')) }}">ดู</a>
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
