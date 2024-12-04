@extends('layouts.master')

@section('title')
    Incentive
@endsection

@section('content')
<main class="d-flex justify-content-center">
    <section class="row w-75">
        <article class="col-lg-6 col-md-12">
            <a href="{{route('IncentiveArrange')}}" class="card p-4 text-decoration-none hover-effect">
                <div class="card-body text-center">
                    <h5 class="text-center">Incentive จัดสินค้า</h5>
                    <i class="bi bi-minecart"></i>
                </div>
            </a>
        </article>
        <article class="col-lg-6 col-md-12">
            <a href="{{route('IncentiveDrag')}}" class="card p-4 text-decoration-none hover-effect">
                <div class="card-body text-center ">
                    <h5 class="text-center">Incentive ลากจ่าย</h5>
                    <i class="bi bi-send"></i>
                </div>
            </a>
        </article>
    </section>
</main>
@endsection