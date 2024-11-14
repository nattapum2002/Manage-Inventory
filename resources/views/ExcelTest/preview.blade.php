@extends('layouts.master')

@section('title')
    Upload Excel
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <h2>Preview Excel Data</h2>
            <table border="1">
                <thead>
                    <tr>
                        @foreach ($detailHeader as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <td>{{ $row[0] }}</td>
                            <td>{{ $row[1] }}</td>
                            <td>{{ $row[2] }}</td>
                            <td>{{ $row[3] }}</td>
                            <td>{{ $row[4] }}</td>
                            <td>{{ $row[5] }}</td>
                            <td>{{ $row[6] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('excel.save') }}" method="POST">
                @csrf
                <input type="hidden" name="filePath" value="{{ $filePath }}">
                <button type="submit">Confirm and Save to Database</button>
            </form>
        </div>
    </section>
@endsection
