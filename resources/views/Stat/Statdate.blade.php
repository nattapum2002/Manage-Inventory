@extends('layouts.master')

@section('title')
    รายการสินค้าเข้าออก
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5></h5>
                        <div>
                            <div class="input-group">
                                <input type="month" class="form-control" name="month" id="month"
                                    value="{{ $show_per_date->first()?->date ? (new DateTime($show_per_date->first()->date))->format('Y-m') : now()->format('Y-m') }}">
                                <button type="button" class="btn btn-primary" id="btn-search-shift">
                                    <i class="fas fa-search" id="icon-search"></i>
                                    <div class="spinner-border spinner-border-sm text-light" id="loading" role="status"
                                        style="display: none;"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="DateStatTable" class="table table-bordered table-striped nowrap">
                        <thead>
                            <th>วันที่</th>
                            <th>รายการ</th>
                        </thead>
                        <tbody>
                            {{-- @foreach ($show_per_date as $data)
                                <tr>
                                    <td class="text-center">{{ $data->date }}</td>
                                    <td>
                                        <a href="{{ route('Show_imported_stat', $data->date) }}"
                                            class="btn btn-primary">ดูรายการเข้า</a>
                                        <a href="{{ route('Show_dispense_stat', $data->date) }}"
                                            class="btn btn-warning">ดูรายการออก</a>
                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        const DateStatTable = $('#DateStatTable').DataTable({
            // responsive: true,
            // lengthChange: true,
            // autoWidth: false,
            info: false,
            scrollX: true,
            ordering: true,
            paging: true,
            pageLength: 40,
            lengthMenu: [10, 20, 40],
            order: [
                [0, 'desc']
            ]
        })

        function loadProductTransactions(month) {
            document.getElementById('loading').style.display = 'inline-block';
            document.getElementById('icon-search').style.display = 'none';

            fetch(`{{ route('ProductTransactionsFilterMonth') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        month: month
                    }),
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';

                    DateStatTable.clear();
                    if (data.ProductTransactionsFilterMonth && data.ProductTransactionsFilterMonth.length > 0) {
                        const newRows = data.ProductTransactionsFilterMonth.map(item => [
                            formatDate(item.date),
                            `
                                <a href="/ShowStat/Imported/${item.date}" class="btn btn-primary">ดูรายการเข้า</a>
                                <a href="/ShowStat/Dispense/${item.date}" class="btn btn-warning">ดูรายการออก</a>
                            `,
                        ]);

                        DateStatTable.rows.add(newRows).draw();
                    }


                })
                .catch((error) => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('icon-search').style.display = 'inline-block';
                    console.error('Error:', error)
                });
        }

        document.getElementById('btn-search-shift').addEventListener('click', function() {
            const month = document.getElementById('month').value;
            loadProductTransactions(month);
        })

        document.addEventListener('DOMContentLoaded', function() {
            const defaultMonth = document.getElementById('month').value;
            loadProductTransactions(defaultMonth);
        })


        function formatDate(date) {
            const d = new Date(date);
            return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1)
                .toString()
                .padStart(2, '0')}/${d.getFullYear()}`;
        }
    </script>
@endsection
