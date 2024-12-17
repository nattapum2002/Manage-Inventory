@extends('layouts.master')

@section('title')
    เพิ่มคิวลูกค้า
@endsection

@section('content')
    <Section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('SaveAddCustomerQueue') }}" method="POST">
                        @csrf
                        <table id="AddCustomerQueueTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
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
                            <tfoot>
                                <tr>
                                    <th>ลำดับ</th>
                                    @foreach ($detailHeader as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            <input type="hidden" id="filePath" name="filePath" value="{{ $filePath }}">
                            <button type="submit" class="btn btn-success">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Section>
@endsection

@section('script')
    <script>
        $("#AddCustomerQueueTable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            // scrollX: true,
            // layout: {
            //     topStart: {
            //         buttons: [
            //             'copy', 'excel', 'pdf'
            //         ]
            //     }
            // }
        });
    </script>
@endsection
