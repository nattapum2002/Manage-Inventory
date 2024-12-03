@extends('layouts.master')

@section('title')
    งานพาเลท
@endsection

@section('content')
    <main>
        <section class="card">
            <article class="card-header">
                งานจัดพาเลท
            </article>
            <article class="card-body">
                <table id="Em-Pallet" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>สถานะ</th>
                            <th>เลขที่</th>
                            <th>หมายเลขพาเลท</th>
                            <th>ห้องเก็บ</th>
                            <th>ลูกค้า</th>
                            <th>ประเภท</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $pallet )
                        <tr>
                            <td>{!!$pallet->pallet_status == 1 ? '<p class="text-success">จัดพาเลทแล้ว</p>' : '<p class="text-danger">ยังไม่จัดพาเลท</p>' !!}</td>
                            <td>{{$pallet->pallet_id}}</td>
                            <td>{{$pallet->pallet_no}}</td>
                            <td>{{$pallet->room}}</td>
                            <td>{{$pallet->customer_name}}</td>
                            <td>{{$pallet->pallet_type}}</td>
                            <td>
                                <a href="{{route('Em.Work.palletDetail',[$pallet->pallet_id , $pallet->order_id])}}" class="btn btn-info">รายละเอียด</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        </section>
    </main>
@endsection