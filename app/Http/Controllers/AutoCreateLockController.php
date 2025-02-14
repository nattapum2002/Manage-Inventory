<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutoCreateLockController extends Controller
{
    //
    function ShowPrelock($CUS_ID, $ORDER_DATE)
    {
        //dd(session()->get('lock' . $CUS_ID . $ORDER_DATE));
        $pallet_type = DB::table('pallet_type')->get();
        $orders_number = DB::table('orders')
            ->select('order_number')
            ->where('customer_id', $CUS_ID)
            ->whereDate('order_date', $ORDER_DATE)
            ->get();
        return view('Admin.ManageLockStock.AutoLock', compact('CUS_ID', 'ORDER_DATE', 'pallet_type', 'orders_number'));
    }
    public function forgetSession($CUS_ID, $ORDER_DATE)
    {
        // dd($id);
        cache()->forget('lock' . $CUS_ID . $ORDER_DATE);

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
        // dd($CachePallets);
        // DB::table('pallet')->truncate();
        // DB::table('pallet_detail')->truncate();
        // DB::table('confirmOrder')->truncate();
        // DB::table('pallet_team')->truncate();
        DB::transaction(function () use ($CUS_ID, $ORDER_DATE) { // ✅ ใช้ use() เพื่อเข้าถึงตัวแปรจากภายนอก
            $CacheKey = 'lock' . $CUS_ID . $ORDER_DATE;
            $CachePallets = cache($CacheKey, []); // ✅ ดึงข้อมูลจาก session ตาม key

            if (empty($CachePallets)) {
                return; // ถ้าไม่มีข้อมูลใน session ให้จบการทำงาน
            }

            foreach ($CachePallets as $index => $item) {
                // ✅ Debug ตรวจสอบข้อมูล session
                // dd($CachePallets); 

                $palletNum = sprintf('%s%s%02d', $CUS_ID, now()->format('Ymd'), $index + 1);

                // ✅ Insert ลงตาราง `pallet`
                $palletId = DB::table('pallet')->insertGetId([
                    'pallet_id' => $palletNum,
                    'order_number' => $item['order_number'],
                    'pallet_name' => $index + 1,
                    'pallet_type_id' => $item['pallet_type'],
                    'warehouse_id' => $item['warehouse'],
                    'note' => null,
                    'arrange_pallet_status' => false,
                    'recipe_status' => false,
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
                        'order_number' => $item['order_number'],
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

                // ✅ Insert ลง `pallet_detail` (แบ่ง batch)
                if (!empty($insertPalletDetail)) {
                    DB::table('pallet_detail')->insert($insertPalletDetail);
                }

                // ✅ Insert ลง `confirmOrder`
                if (!empty($insertConfirmOrder)) {
                    DB::table('confirmOrder')->insert($insertConfirmOrder);
                }
            }

            cache()->forget($CacheKey);
        });

        return redirect()->route('DetailLockStock', [$CUS_ID, $ORDER_DATE])->with('success', 'Data saved successfully');
    }
    public function addUpSellPallet(Request $request, $CUS_ID, $ORDER_DATE)
    {
        $key = 'lock' . $CUS_ID . $ORDER_DATE;
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
        ], [
            'room.required' => 'กรุณาเลือกห้อง',
            'work_desc.required' => 'กรุณาเลือกลักษณะงาน',
            'pallet_type_id.required' => 'กรุณาเลือกประเภทใบล็อค',
            'product_name.0.required' => 'กรุณากรอกสินค้า',
        ]);

        $items = $this->UpSellProduct($data, $CUS_ID, $ORDER_DATE);

        // ดึงข้อมูล Cache ปัจจุบัน (ถ้ามี)
        $existingCache = cache()->get($key, []);

        // เพิ่มข้อมูลใหม่เข้าไป
        $UpSellLock = [
            'warehouse' => intval($data['room']),
            'order_number' => $data['order_number'],
            'pallet_type' => 2,
            'work_type' => intval($data['work_desc']),
            'items' => $items
        ];

        //dd($UpSellLock);
        // รวมข้อมูลใหม่เข้าไปใน Cache
        $existingCache[] = $UpSellLock;

        // อัปเดต Cache
        cache()->put($key, $existingCache, now()->addMinutes(30));

        // Debug ตรวจสอบค่าที่อยู่ใน Cache
        //dd(cache()->get($key));

        return redirect()->back();
    }

    function UpSellProduct($data, $CUS_ID, $ORDER_DATE)
    {
        $items = [];

        foreach ($data['product_id'] as $index => $product) {
            if (empty($product)) {
                continue; // ข้ามค่า null หรือ empty
            }

            $items[] = [
                'customer_id' => $CUS_ID,
                'order_date' => $ORDER_DATE,
                'product_id' => $product,
                'product_number' => $data['show_product_id'][$index] ?? null,
                'product_description' => $data['product_name'][$index] ?? null,
                'quantity' => $data['quantity'][$index] ?? 0,
                'quantity_um' => 'Kg',
                'quantity2' => 0,
                'product_um2' => 0,
                'warehouse' => $data['room'],
                'work_type' => $data['work_desc'],
            ];
        }

        return $items;
    }


    public function autoCreateLock(Request $request, $CUS_ID, $ORDER_DATE)
    {
        $order_number = $request->input('order_number');

        // Fetch the orders and join necessary tables
        $CustomerOrders = DB::table('orders')
            ->join('order_detail', 'orders.order_number', '=', 'order_detail.order_number')
            ->join('product', 'order_detail.product_id', '=', 'product.product_id')
            ->leftJoin('product_work_desc', 'product.product_work_desc_id', '=', 'product_work_desc.id')
            ->join('customer', 'orders.customer_id', '=', 'customer.customer_id')
            ->leftJoin('warehouse', 'warehouse.id', '=', 'product.warehouse_id')
            ->select(
                'orders.order_number as ORDER_NUMBER',
                'orders.customer_id as customer_id',
                'orders.order_date as order_date',
                'customer.customer_name as ORDER_BY_CUS',
                'order_detail.quantity as quantity',
                'order_detail.quantity2 as quantity2',
                'product.product_id',
                'product.product_number',
                'product.product_um as product_um',
                'product.product_um2 as product_um2',
                'product.product_work_desc_id as item_work_desc_id',
                'product.warehouse_id',
                'product.product_description',
                'customer.*',
            )
            ->where('orders.order_number', $order_number)
            ->orderBy('quantity')
            ->get();

        $this->splitItem($CustomerOrders, $CUS_ID, $order_number, $ORDER_DATE);

        return back();
    }

    function splitItem($CustomerOrders, $CUS_ID, $order_number, $ORDER_DATE)
    {
        $lock_items = collect(); // ใช้ Collection แทน array
        $key = 'lock' . $CUS_ID . $ORDER_DATE;
        $current_group = [];
        $current_weight = [];

        $CustomerOrders->each(function ($itemOrder) use (&$current_group, &$current_weight, &$lock_items, $order_number) {
            if (!$itemOrder->item_work_desc_id || !$itemOrder->warehouse_id) {
                session()->flash('LockErrorCreate', "{$itemOrder->product_number} {$itemOrder->product_description} ยังไม่ได้กำหนดข้อมูลห้องเก็บหรือลักษณะงาน");
                return false; // หยุดการทำงานของ each()
            }

            $warehouse = $itemOrder->warehouse_id;
            $work_type_name = $itemOrder->item_work_desc_id;

            // กำหนดค่าเริ่มต้นหากยังไม่มีข้อมูล
            $current_group[$warehouse][$work_type_name] = $current_group[$warehouse][$work_type_name] ?? [];
            $current_weight[$warehouse][$work_type_name] = $current_weight[$warehouse][$work_type_name] ?? 0;

            $quantityToProcess = ($itemOrder->product_um === 'Kg') ? $itemOrder->quantity : $itemOrder->quantity2;
            if ($itemOrder->product_um === 'Kg' || $itemOrder->product_um2 === 'Kg') {
                $this->splitProductWorkType($itemOrder, $quantityToProcess, $current_group[$warehouse][$work_type_name], $current_weight[$warehouse][$work_type_name], $lock_items);
            }
        });

        // เพิ่มกลุ่มสุดท้ายเข้า lock_items
        foreach ($current_group as $warehouse => $work_types) {
            foreach ($work_types as $work_type => $group) {
                if (!empty($group)) {
                    $lock_items->push([
                        'warehouse' => $warehouse,
                        'work_type' => $work_type,
                        'order_number' => $order_number,
                        'pallet_type' => 1,
                        'items' => $group,
                    ]);
                }
            }
        } 
        $exitCache = cache()->get($key,[]);
        if($exitCache){
            $newCache = $lock_items->toArray();
            cache()->put($key, array_merge($newCache , $exitCache), now()->addMinutes(30));
        }else{
            cache()->put($key, $lock_items->toArray(), now()->addMinutes(30));
        }
        
}

    function splitProductWorkType($itemOrder, $quantity, &$current_group, &$current_weight, &$lock_items)
    {
        $this->processItem($itemOrder, $quantity, $current_group, $current_weight, $lock_items);
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
            $lock_items->push(['items' => $current_group]);
            $current_group = [];
            $current_weight = 0;
        }

        $current_group[] = $this->createItemData($itemOrder, $quantity);
        $current_weight += $quantity;
    }

    function largeOrder($itemOrder, $quantity, &$lock_items)
    {
        // ใช้ array_chunk แบ่งกลุ่มละ 850
        foreach (array_chunk(range(1, $quantity), 850) as $chunk) {
            $this->largeOrderAddData($itemOrder, count($chunk), $lock_items);
        }
    }

    function largeOrderAddData($itemOrder, $quantity, &$lock_items)
    {
        $lock_items->push([
            'warehouse' => $itemOrder->warehouse_id,
            'work_type' => $itemOrder->item_work_desc_id,
            'order_number' => $itemOrder->ORDER_NUMBER ?? 'N/A',
            'pallet_type' => 1,
            'items' => [$this->createItemData($itemOrder, $quantity)],
        ]);
    }

    function createItemData($itemOrder, $quantity)
    {
        return [
            'order_number' => $itemOrder->ORDER_NUMBER ?? 'N/A',
            'customer_id' => $itemOrder->customer_id,
            'order_date' => $itemOrder->order_date,
            'product_id' => $itemOrder->product_id,
            'product_number' => $itemOrder->product_number,
            'product_description' => $itemOrder->product_description,
            'ORDER_BY_CUS' => $itemOrder->ORDER_BY_CUS,
            'quantity' => $quantity,
            'product_um' => $itemOrder->product_um,
            'quantity2' => $itemOrder->quantity2,
            'product_um2' => $itemOrder->product_um2,
            'warehouse' => $itemOrder->warehouse_id,
        ];
    }
}
