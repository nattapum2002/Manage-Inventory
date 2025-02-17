<style>
    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: normal;
        src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format("truetype");
    }

    body {
        font-family: 'THSarabunNew';
        font-size: 20px;
    }

    hr {
        border-top: 1px dashed black;
    }

    .lock-container {
        padding: 5px 5px;
        margin-bottom: 10px;
        page-break-inside: avoid;
    }
    .lock-header {
        text-align: center;
    }
    .lock-header p {
        display: inline-block;
        margin: 0 5px;
        white-space: nowrap;
    }
    .lock-header .pallet-type {
       background-color: #FFE6C9;
       padding: 0px 10px 0px 10px;
       text-align: justify;
    }

    .lock-body table {
        width: 100%;
        border-collapse: collapse;
    }

    /* ปรับขอบตารางทั้งหมด */
    .lock-body th,
    .lock-body td {
        border: 1px solid black;
        padding: 5px;
        text-align: center;
    }

    /* ลบเส้นขอบของแถวลูกค้า */
    .lock-title {
        border: none !important;
        background-color: #D9EAFD;
        text-align: center;
        page-break-after: avoid;
    }
    .lock-title p {
       display: inline-block;
       margin: 0px 20px 0px 20px;
    }

    .lock-detail-header {
        background-color: #F1F1F1;
    }

    /* ปรับให้ข้อความในตารางชิดซ้าย */
    .text-left {
        text-align: left !important;
    }

    .no-break {
        page-break-inside: avoid;
    }
    .order-number{
        padding-bottom: 0px;
        padding-top: 0px;
        white-space: nowrap;
    }

</style>

<div>
    @foreach ($data as $index => $items)
        <div class="lock-container">
            <!-- หัวข้อ -->
            <div class="lock-header">
                <p>เวลาเริ่มจัด : _________________</p>
                <p>ผู้จัด :@foreach ($items['team'] as $team)
                    @if (!empty($team))
                        {{$team}},
                    @else
                        _________________
                    @endif
                @endforeach </p>
                <p>ห้องเก็บ : {{$items['warehouse']}}</p>
                <p class="pallet-type">{{$items['pallet_type']}}</p>
            </div>

            <!-- แถวลูกค้า (ไม่มีเส้นขอบ) -->
            <div class="lock-title">
                <p class="order-number">ออเดอร์ : {{$items['order_number']}}</p>
                <p>ลูกค้า : {{$items['customer']['name']}}</p>
                <p>{{$items['customer']['grade']}}</p>
                <p>{{ \Carbon\Carbon::parse($items['order_date'])->format('d-m-Y') }}</p>
                <p>{{$index + 1 .'/'. count($data )}}</p>
            </div>
            
            <div class="lock-body">
                <table class="no-break">
                    <thead>
                        <!-- แถวหัวข้อหลัก (มีเส้นขอบ) -->
                        <tr class="lock-detail-header">
                            <td>รหัสสินค้า</td>
                            <td style="width: 250px">ชื่อสินค้า</td>
                            <td>สั่งจ่าย</td>
                            <td style="width: 100px">สีถุง</td>
                            <td>จ่ายจริง</td>
                            <td style="width: 70px">LOT</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items['order_details'] as $detail)
                            <tr>
                                <td>{{$detail['product_number']}}</td>
                                <td class="text-left">{{$detail['product_name']}}</td>
                                <td>{{$detail['quantity']}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
    @endforeach
</div>
