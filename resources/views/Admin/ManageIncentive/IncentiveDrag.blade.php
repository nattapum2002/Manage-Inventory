@extends('layouts.master')
@section('title')
    Incentive : ลาก-จ่ายสินค้า
@endsection

@section('content')
    <main class="d-flex justify-content-center">
        <section class="card " style="width: 50%;"> 
            <article class="card-body">
                <table id="incentive-date-table" class="table table-bordered table-striped " >
                    <thead>
                        <tr>
                            <th>เดือนที่</th>
                            <th>ชื่อเดือน</th>
                            <th>ดู</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($palletMonth as $month )
                            <tr>
                                <td>{{ $month->month_number }} / {{ $month->year_number }}</td>
                                <td>{{ $month->month_name }}</td>
                                <td><a href="{{route('incentiveDragWorker',[$month->month_number,$month->year_number])}}" class="btn btn-primary">ดู</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        </section>
    </main>
@endsection