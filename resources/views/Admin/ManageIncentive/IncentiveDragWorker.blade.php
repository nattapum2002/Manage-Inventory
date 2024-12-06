@extends('layouts.master')
@section('title')
    Incentive : ลากจ่ายสินค้า - พนักงาน | {{$worker[0]->month_name}} / {{$year}}
@endsection

@section('content')
<main class="d-flex justify-content-center">
    <section class="card" style="width: 80%;"> 
        <article class="card-body">
            <table id="incentive-Em-table" class="table table-bordered table-striped " >
                <thead>
                    <tr>
                        <th>รหัสพนักงาน</th>
                        <th>ชื่อพนักงาน</th>
                        <th>ทีมจัด</th>
                        <th>ดู</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($worker as $employee)
                    <tr>
                        <td>{{$employee->user_id}}</td>
                        <td>{{$employee->name}} {{$employee->surname}}</td>
                        <td>{{$employee->team_name}}</td>
                        <td>
                            <a type="button" href="{{route('IncentiveDragWorkerDetail',[$month,$year,$employee->user_id])}}" class="btn btn-primary">ดู</a>
                        </td>
                    </tr>                      
                @endforeach
                </tbody>
            </table>
        </article>
    </section>
</main>
@endsection