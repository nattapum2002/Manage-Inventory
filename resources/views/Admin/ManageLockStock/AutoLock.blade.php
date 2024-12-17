@extends('layouts.master')

@section('title')
    เพิ่มพาเลท : {{ $order_number ?? 'ไม่มี' }}
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="card col-12">
                    <div class="card-header text-end">
                        <a type="button" href="{{ route('AutoLock',[$CUS_ID,$ORDER_DATE])}}" class="btn btn-success" >จัดใบล็อค</a>
                        <a type="button" href="{{ route('forgetSession') }}" class="btn btn-danger ">ล้าง</a>
                    </div>
                    <div class="card-body">
                        <table id="show-pallet" class="table nowrap">
                            <thead>
                                <th>หมายเลขพาเลท</th>
                                <th>ห้อง</th>
                                <th>รหัสสินค้า</th>
                                <th>ชื่อสินค้า</th>
                                <th>จํานวน</th>
                                <th>ประเภท</th>
                                <th>#</th>
                            </thead>
                            <tbody>
                                {{-- @dd(session('pallet')) --}}
                                @if (session('pallet'))
                                    @foreach (session('pallet') as $key => $pallet)
                                        <tr>
                                            <td>{{ $pallet['pallet_id'] }}</td>
                                            <td>{{ $pallet['pallet_no'] }}</td>
                                            <td>{{ $pallet['room'] }}</td>
                                            <td>
                                                @foreach ($pallet['show_product_id'] as $product_id)
                                                    {{ $product_id }} <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($pallet['product_name'] as $name)
                                                    {{ $name }} <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach (array_map(null, $pallet['quantity'], $pallet['quantityUm'], $pallet['quantity2'], $pallet['quantityUm2_']) as [$quantity, $quantityUm, $quantity2, $quantityUm2])
                                                    {{ $quantity }} {{ $quantityUm }} : {{ $quantity2 }}
                                                    {{ $quantityUm2 }} <br>
                                                @endforeach
                                            </td>
                                            <td>{{ $pallet['pallet_type_id'] }}</td>
                                            <td>{{ $pallet['note'] ?? '' }}</td>
                                            <td>
                                                <a type="button" href="{{ route('Remove_Pallet', $key) }}"
                                                    class="btn btn-danger ">ลบ</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        <a href="" class="btn btn-success ">บันทึกข้อมูล</a>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#show-pallet').DataTable({
                info: false,
                scrollX: true,
                ordering: true,
                paging: true,
                columns: [{
                        data: 'pallet_id',
                        className: 'text-center'
                    },
                    {
                        data: 'pallet_no',
                        width: '15%',
                        className: 'text-center'
                    },
                    {
                        data: 'room',
                        className: 'text-center'
                    },
                    {
                        data: 'show_product_id',
                        className: 'text-center'
                    },
                    {
                        data: 'product_name',
                        className: 'text-center'
                    },
                    {
                        data: '',
                        className: 'text-center'
                    },
                    {
                        data: 'pallet_type_id',
                        className: 'text-center'
                    },
                    {
                        data: 'note'
                    },
                    {
                        data: '#'
                    },
                ]
            });
        })
    </script>

    {{-- <script>

        $('#pallet_type').on('change', function() {
            const type = $(this).val(); // รับค่าที่เลือก
            if (type === 'ขายเพิ่ม' || type === 'ปกติ') {
                changePalletType(type); // เรียกฟังก์ชันพร้อมส่งค่าประเภท
            }
        });
    
        function changePalletType(type) {
            $.ajax({
                url: "{{ route('AutoCompleteAddPallet', ['order_number' => $order_number]) }}", // ตรวจสอบว่า order_number มีค่าหรือส่งจาก Blade
                // method: 'GET', // ระบุ method ให้ถูกต้อง
                data: {
                    type: type // ส่งค่าประเภทที่เลือก
                },
                success: function(data) {
                    response(data); // ดูข้อมูลผลลัพธ์
                    // คุณสามารถเพิ่มการทำงานเพิ่มเติมที่นี่ เช่น อัปเดต UI
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText); // แสดงข้อผิดพลาดใน console
                }
            });
        }
    </script> --}}
@endsection
