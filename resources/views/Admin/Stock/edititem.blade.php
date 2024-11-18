@extends('layouts.master')

@section('title')
    แก้ไขชื่อสินค้า
@endsection
@section('content')
    <main>
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <section class="card">
            <article class="card-body">
            @foreach ($data as $item )
                <form action="{{route('Updatename')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="product_id">รหัสสินค้า</label>
                        <input type="text" class="form-control" id="product_id" name="product_id" value="{{ $item->item_no }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="room_id">ห้องเก็บ</label>
                        <select name="room" class="form-control" id="">
                            <option value="Cold-A" {{ $item->storage_room == 'Cold-A' ? 'selected' : '' }}>Cold-A</option>
                            <option value="Cold-C" {{ $item->storage_room == 'Cold-C' ? 'selected' : '' }}>Cold-C</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="product_name">ชื่อสินค้า</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $item->item_desc1 }}">
                    </div>

                    <button class="btn btn-primary" type="submit">บันทึก</button>
                </form>         
            @endforeach
            </article>
        </section>
    </main>
@endsection