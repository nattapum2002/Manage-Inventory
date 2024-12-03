@extends('layouts.master')
@section('title')
    Incentive : จัดสินค้า - พนักงาน
@endsection

@section('content')
<main class="d-flex justify-content-center">
    <section class="card" style="width: 80%;"> 
        <article class="card-body">
            <table id="incentive-arrange-Em-table" class="table table-bordered table-striped " >
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
                                <a type="button" href="" class="btn btn-primary">ดู</a>
                            </td>
                        </tr>                      
                    @endforeach
                </tbody>
            </table>
        </article>
    </section>
</main>
@endsection