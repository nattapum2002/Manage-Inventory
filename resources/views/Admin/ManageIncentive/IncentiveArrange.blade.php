@extends('layouts.master')
@section('title')
    Incentive : จัดสินค้า
@endsection

@section('content')
    <main class="d-flex justify-content-center">
        <section class="card " style="width: 50%;"> 
            <article class="card-body">
                <table id="incentive-arrange-date-table" class="table table-bordered table-striped " >
                    <thead>
                        <tr>
                            <th>วันที่</th>
                            <th>ดู</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($palletDate as $item)
                            <tr>
                                <td>{{$item->date}}</td>
                                <td>
                                    <a type="button" href="{{route('IncentiveArrangeWorker',$item->date)}}" class="btn btn-primary">ดู</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        </section>
    </main>
@endsection