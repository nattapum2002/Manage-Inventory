<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutoCreateLockController extends Controller
{
    //
    function ShowPrelock($CUS_ID, $ORDER_DATE)
    {
        $pallet_type = DB::table('pallet_type')->get();
        $orders_number = DB::table('orders')
                ->select('order_number')
                ->where('customer_id',$CUS_ID)
                ->whereDate('order_date',$ORDER_DATE)
                ->get();
        return view('Admin.ManageLockStock.AutoLock', compact('CUS_ID', 'ORDER_DATE', 'pallet_type','orders_number'));
    }
    public function forgetSession($CUS_ID,$ORDER_DATE)
    {
        // dd($id);
        session()->forget('lock' . $CUS_ID . $ORDER_DATE);

        return back()->with('success', 'Data cleared successfully');
    }
    public function AutoCompleteAddPallet(Request $request)
    {
        $query = $request->get('query');
        $type = $request->get('type');
        // $order_number = $request->get('order_number');
        $data = DB::table('product')
            ->select('product_id', 'product_description', 'product_number', 'product_um', 'product_um2')
            ->where('product_description', 'like', '%' . $query . '%')
            ->distinct()
            ->limit(10)
            ->get();

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ jQuery autocomplete ต้องการ
        $results = $data->map(function ($item) {
            return [
                'label' => $item->product_description,
                'value' => $item->product_description,
                'product_no' => $item->product_number,
                'product_id' => $item->product_id,
                'ordered_quantity' => $item->order_quantity ?? 0,
                'ordered_quantity_UM' => $item->product_um,
                'ordered_quantity2' => $item->order_quantity2 ?? 0,
                'ordered_quantity_UM2' => $item->product_um2 ?? 'ไม่มี',
            ];
        });

        return response()->json($results);
    }
    public function insert_pallet($CUS_ID, $ORDER_DATE)
    {
        $data = session()->get('lock' . $CUS_ID);
        //dd($data);
        DB::table('pallet')->truncate();
        DB::table('pallet_detail')->truncate();
        DB::table('confirmOrder')->truncate();
            DB::transaction(function () use ($data, $CUS_ID, $ORDER_DATE) {
                $counter = 1;
                $convertDate = str_replace(["/", "-"], "", $ORDER_DATE);
            foreach ($data as $items) {
                foreach ($items as $item) {
                    $palletNum = sprintf('%s%s%s', $CUS_ID, $convertDate, $counter);
                    $palletId = DB::table('pallet')->insertGetId([
                        'pallet_id' => $palletNum,
                        'order_number' => $item['order_number'],
                        'pallet_name' => $counter,
                        'pallet_type_id' => $item['pallet_type'],
                        'warehouse_id' => $item['warehouse'],
                        'note' => null,
                        'arrange_pallet_status' => false,
                        'recive_status' => false,
                        'pallet_desc' => $item['work_type']
                    ]);

                    $insertPalletDetail = [];
                    $insertConfirmOrder = [];

                    foreach ($item['items'] as $details) {
                        $insertPalletDetail[] = [
                            'pallet_id' => $palletId,
                            'product_id' => $details['product_id'],
                            'product_number' => $details['product_number'],
                            'quantity' => $details['quantity'],
                            'quantity2' => $details['quantity2'],
                        ];

                        $insertConfirmOrder[] = [
                            'order_number' => $details['order_number'],
                            'pallet_id' => $palletId,
                            'product_id' => $details['product_id'],
                            'quantity' => $details['quantity'],
                            'quantity2' => null,
                            'product_work_desc_id' => $item['work_type'],
                            'confirm_order_status' => false,
                            'confirm_at' => null,
                            'created_at' => now()
                        ];
                    }

                    if (!empty($insertPalletDetail)) {
                        foreach (array_chunk($insertPalletDetail, 100) as $chunk) {
                            DB::table('pallet_detail')->insert($chunk);
                        }
                    }

                    if (!empty($insertConfirmOrder)) {
                        foreach (array_chunk($insertConfirmOrder, 100) as $chunk) {
                            DB::table('confirmOrder')->insert($chunk);
                        }
                    }

                    $counter++;
                }
                }
            });
            session()->forget('lock');

        return redirect()->route('DetailLockStock', [$CUS_ID, $ORDER_DATE])->with('success', 'Data saved successfully');
    }
    public function addUpSellPallet(Request $request , $CUS_ID , $ORDER_DATE)
    {
        $data = $request->validate([
            'order_number' => 'required',
            'room' => 'required',
            'work_desc' => 'required',
            'show_product_id.*' => 'nullable',
            'product_id.*' => 'nullable',
            'product_name.0' => 'required',
            'product_name.*' => 'nullable',
            'quantity.*' => 'nullable',
            'pallet_type_id' => 'required'
        ],[
            'room.required' => 'กรุณาเลือกห้อง',
            'work_desc.required' => 'กรุณาเลือกลักษณะงาน',
            'pallet_type_id.required' => 'กรุณาเลือกประเถทใบล็อค',
            'product_name.0.required' => 'กรุณากรอกสินค้า',
            // 'quantity.required' => 'กรุณากรอกจำนวน'
        ]);

        $item = $this->UpSellProduct($data,$CUS_ID , $ORDER_DATE);

        foreach( $data as $index => $dataItem){
            $UpSellLock = [
                [
                'warehouse' => intval($data['room']),
                'order_number' => $data['order_number'],
                'pallet_type' => 2 ,
                'work_type' =>  intval($data['work_desc']),
                'items' => $item
                ]
            ];
        }
        //dd($UpSellLock);
        //dd(session('lock'.$CUS_ID));
        session()->push('lock' . $CUS_ID . $ORDER_DATE, $UpSellLock);

        return redirect()->back();
        
    }
    function UpSellProduct($data , $CUS_ID , $ORDER_DATE){
        $item = [];
        foreach($data['product_id'] as $index => $product){
            if (empty($product)) {
                continue; // ข้ามค่า null หรือ empty
            }
            $item[] = [
            'order_id' => '',
            'order_no' => NULL,
            'customer_id' => $CUS_ID,
            'order_date' => $ORDER_DATE,
            'product_id' => $product,
            'product_number' => $data['show_product_id'][$index],
            'product_description' => $data['product_name'][$index],
            'quantity' => $data['quantity'][$index],
            'quantity_um' => 'Kg',
            'quantity2' => 0,
            'product_um2' => 0,
            'warehouse' =>  $data['room'],
            'work_type' => $data['work_desc'],
            ];
        }
        //dd($item);
        return $item ;
    }

    public function autoCreateLock(Request $request ,$CUS_ID,$ORDER_DATE){
        $order_number = $request->input('order_number');
        $CustomerOrders = DB::table('orders')
        ->join('order_detail', 'orders.order_number', '=', 'order_detail.order_number')
        ->join('product', 'order_detail.product_id', '=', 'product.product_id')
        ->join('product_work_desc', 'product.product_work_desc_id', '=', 'product_work_desc.id')
        ->join('customer', 'orders.customer_id', '=', 'customer.customer_id')
        ->join('warehouse','warehouse.id','=','product.warehouse_id')
        ->select(
            'orders.order_number as ORDER_NUMBER',
            'orders.customer_id as customer_id',
            'orders.order_date as order_date',
            'customer.customer_name as ORDER_BY_CUS',
            'order_detail.quantity as quantity',
            'order_detail.quantity2 as quantity2',
            'product.product_um as product_um',
            'product.product_um2 as product_um2',
            'product_work_desc.product_work_desc as item_work_desc',
            'product_work_desc.id as item_work_desc_id',
            'product.*',
            'customer.*',
            'warehouse.id as warehouse_id'
        )
        ->where('orders.order_number', $order_number)
        ->orderBy('quantity')
        ->get();

    $this->splitItem($CustomerOrders, $CUS_ID ,$order_number,$ORDER_DATE);

    return back();
}

function splitItem($CustomerOrders, $CUS_ID ,$order_number,$ORDER_DATE)
{
    $lock_items = []; // สำหรับจัดกลุ่มสินค้า (ล็อก)
    $current_group = []; // กลุ่มสินค้าที่กำลังสร้าง
    $current_weight = []; // น้ำหนักสะสมของแต่ละ warehouse และแต่ละลักษณะงาน

    foreach ($CustomerOrders as $itemOrder) {
        $warehouse = $itemOrder->warehouse_id;
        $work_type_name = $itemOrder->item_work_desc_id;

        // ถ้ายังไม่มีข้อมูล warehouse นี้ใน lock_items และ current_weight ให้เริ่มต้น
        if (!isset($current_group[$warehouse][$work_type_name])) {
            $current_group[$warehouse][$work_type_name] = [];
            $current_weight[$warehouse][$work_type_name] = 0;
        }

        if ($itemOrder->product_um === 'Kg') {
            $this->split_product_work_type(
                $itemOrder,
                $itemOrder->quantity,
                $current_group[$warehouse][$work_type_name],
                $current_weight[$warehouse][$work_type_name],
                $lock_items
            );
        } else if ($itemOrder->product_um2 === 'Kg') {
            $this->split_product_work_type(
                $itemOrder,
                $itemOrder->quantity2,
                $current_group[$warehouse][$work_type_name],
                $current_weight[$warehouse][$work_type_name],
                $lock_items
            );
        }
    }

    // เพิ่มกลุ่มสุดท้ายในแต่ละ warehouse และลักษณะงาน
    foreach ($current_group as $warehouse => $work_types) {
        foreach ($work_types as $work_type => $group) {
            if (!empty($group)) {
                $lock_items[] = [
                    'warehouse' => $warehouse,
                    'work_type' => $work_type,
                    'order_number' => $order_number,
                    'pallet_type' => 1 ,
                    'items' => $group,
                ];
            }
        }
    }
    //dd($lock_items);
    session()->push('lock' . $CUS_ID . $ORDER_DATE, $lock_items);
}

function split_product_work_type($itemOrder, $quantity, &$current_group, &$current_weight, &$lock_items)
{
    if ($itemOrder->item_work_desc == 'แยกจ่าย') {
        $this->processItem($itemOrder, $quantity, $current_group, $current_weight, $lock_items);
    } else if ($itemOrder->item_work_desc == 'รับจัด') {
        $this->processItem($itemOrder, $quantity, $current_group, $current_weight, $lock_items);
    } else {
        $this->processItem($itemOrder, $quantity, $current_group, $current_weight, $lock_items);
    }
}

function processItem($itemOrder, $quantity, &$current_group, &$current_weight, &$lock_items)
{
    if ($quantity <= 850) {
        $this->smallOrder($itemOrder, $quantity, $current_group, $current_weight, $lock_items);
    } else {
        $this->largeOrder($itemOrder, $quantity, $lock_items);
    }
}

function smallOrder($itemOrder, $quantity, &$current_group, &$current_weight, &$lock_items)
{
    if ($current_weight + $quantity > 850) {
        // เก็บกลุ่มปัจจุบันลงใน lock_items และรีเซ็ต
        $lock_items[] = [
            'items' => $current_group
        ];
        $current_group = [];
        $current_weight = 0;
    }

    // เพิ่มสินค้าเข้าในกลุ่ม
    $current_group[] = [
        'order_id' => $itemOrder->id,
        'order_number' => $itemOrder->ORDER_NUMBER,
        'customer_id' => $itemOrder->customer_id,
        'order_date' => $itemOrder->order_date,
        'product_id' => $itemOrder->product_id,
        'product_number' => $itemOrder->product_number,
        'product_description' => $itemOrder->product_description,
        'ORDER_BY_CUS' => $itemOrder->ORDER_BY_CUS,
        'quantity' => $itemOrder->quantity,
        'product_um' => $itemOrder->product_um,
        'quantity2' => $itemOrder->quantity2,
        'product_um2' => $itemOrder->product_um2,
        'warehouse' => $itemOrder->warehouse_id,
        // 'work_type' => $itemOrder->item_work_desc_id,
    ];
    $current_weight += $quantity;
}

function largeOrder($itemOrder, $quantity, &$lock_items)
{
    $remaining_quantity = $quantity;

    // กระจายสินค้าออกเป็นหลายกลุ่ม โดยแต่ละกลุ่มมีน้ำหนักไม่เกิน 850
    while ($remaining_quantity > 850) {
        $this->LargeOrderAddData($itemOrder, 850, $lock_items);
        $remaining_quantity -= 850;
    }

    // ถ้ามีส่วนที่เหลืออยู่ ให้นำมาจัดกลุ่ม
    if ($remaining_quantity > 0) {
        $this->LargeOrderAddData($itemOrder, $remaining_quantity, $lock_items);
    }
}


function LargeOrderAddData($itemOrder, $quantity, &$lock_items)
{
    $lock_items[] = [
        'items' =>
        [
            [
                'order_number' => $itemOrder->ORDER_NUMBER,
                'product_id' => $itemOrder->product_id,
                'product_number' => $itemOrder->product_number,
                'product_description' => $itemOrder->product_description,
                'ORDER_BY_CUS' => $itemOrder->ORDER_BY_CUS,
                'quantity' => $itemOrder->quantity,
                'product_um' => $itemOrder->product_um,
                'quantity2' => $itemOrder->quantity2,
                'product_um2' => $itemOrder->product_um2,
                'warehouse' => $itemOrder->warehouse_id,
                // 'work_type' => $itemOrder->item_work_desc_id,
            ]
        ]
    ];
}
}
