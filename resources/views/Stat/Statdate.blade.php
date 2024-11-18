@extends('layouts.master')

@section('title')
    รายการสินค้าเข้าออก
@endsection
@section('content')
    <main>
        <section>
            <article class="card-body">
                <table id="date-stat-table" class="table">
                    <thead>
                        <th class="text-center" width="70%">วันที่</th>
                        <th>รายการ</th>
                    </thead>
                    <tbody>
                        @foreach ( $show_per_date as $data )
                            <tr>
                                <td class="text-center">{{$data->store_date}}</td>
                                <td >
                                    <a href="{{route('Show_imported_stat',$data->store_date)}}" class="btn btn-primary">ดูรายการเข้า</a>
                                    <a href="{{route('Show_dispense_stat',$data->store_date)}}" class="btn btn-warning">ดูรายการออก</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        </section>
    </main>
@endsection