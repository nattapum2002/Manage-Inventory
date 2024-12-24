@extends('layouts.master')

@section('title')
    งานจัดพาเลท : รายละเอียดที่ต้องจัด
@endsection

@section('content')
<main>
    <section class="card shadow">
        <article class="card-header bg-primary text-white">
            <div class="row">
                <div class="col-md-6 col-sm-4">
                    <h5>ลูกค้า: {{$Pallets[0]->customer_name ?? 'N/A'}}</h5>
                </div>
                <div class="col-md-3 col-sm-4 text-center">
                    <h5>หมายเลขพาเลท: {{$Pallets[0]->pallet_id?? 'N/A'}} / {{$Pallets[0]->pallet_no?? 'N/A'}}</h5>
                </div>
                <div class="col-md-3 col-sm-4 text-end">
                    <h5>ประเภทพาเลท: {{$Pallets[0]->pallet_type ?? 'N/A'}}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-4">
                    <h5>ห้องเก็บ: {{$Pallets[0]->whs_name ?? 'N/A'}}</h5>
                </div>
                <div class="col-md-3 col-sm-4 text-center">
                    <h5>ลักษณะงาน: {{$Pallets[0]->product_work_desc ?? 'N/A'}}</h5>
                </div>
            </div>
        </article>
        
        <article class="card-body">
            <h5 class="mb-3">รายละเอียดสินค้า</h5>
            <table id="Em-Pallet-detail" class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>รหัสสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th>จำนวน</th>
                        <th>หน่วย</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Pallets as $item)
                        <tr>
                            <td>{{$item->item_no}}</td>
                            <td>{{$item->item_desc1}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>Kg.</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center">
                <a type="button" href="{{route('Em.Work.palletSubmit',$Pallets[0]->id)}}" class="btn btn-success {{$Pallets[0]->status == 1 ? 'disabled' : ''}}" >เสร็จสิ้น</a>
            </div>
        </article>
        
        <article class="card-footer bg-light">
            <h5 class="mb-3">ทีมและผู้จัด</h5>
            @foreach ($groupedTeams as $team => $members)
                <div class="mb-2">
                    <strong>ทีม:</strong> {{$team}} <br>
                    <strong>ผู้จัด:</strong> 
                    @foreach ($members as $employee)
                        {{$employee['name']}} {{$employee['surname']}}
                        @if (!$loop->last), @endif
                    @endforeach
                </div>
            @endforeach
        </article>
    </section>
    
</main>
@endsection