@extends('layouts.master')

@section('title')
    จัดการผลิตภัณฑ์จากคลัง : สลิป {{$date}}
@endsection

@section('content')
<section class="content">
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session()->get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-start">
            <a class="btn btn-primary" href="{{route('Add item')}}">เพิ่มข้อมูลสินค้า</a>
        </div>
        <div class="card-body">
            <table id="slip_per_date" class="table table-striped">
                <thead>
                    <tr>
                        <th>เลขที่</th>
                        <th class="text-center">สลิปใบที่ No.</th>
                        <th>หน่วยงาน</th>
                        <th>Production Checker</th>
                        <th>Domestic Checker</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($show_slip as $item )
                    <tr>
                        <td>{{$item->slip_id}}</td>
                        <td class="text-center">{{$item->slip_number}}</td>
                        <td>{{$item->department}}</td>
                        <td>{{$item->product_checker ?? 'ไม่มี'}}</td>
                        <td>{{$item->domestic_checker ?? 'ไม่มี'}}</td>
                        <td>
                            <a class="btn btn-primary" href="{{route('SlipDetail',$item->slip_id)}}">ดู</a>
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