@extends('layouts.master')

@section('title')
    Upload Excel
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('excel.preview') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".xlsx, .xls, .csv">
                <button type="submit">Upload and Preview</button>
            </form>
        </div>
    </section>
@endsection
