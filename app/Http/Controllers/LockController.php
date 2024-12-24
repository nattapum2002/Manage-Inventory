<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use PHPUnit\Framework\Attributes\Large;
use Throwable;

class LockController extends Controller
{
    public $select_teams = [
        ['select_name' => 'A'],
        ['select_name' => 'B'],
        ['select_name' => 'C'],
        ['select_name' => 'D'],
        ['select_name' => 'E'],
        ['select_name' => 'F'],
    ];
    public function index()
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        // $CustomerOrders = DB::table('customer_order')
        //     ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
        // ->join('lock_team', 'customer_order.team_id', '=', 'lock_team.team_id')
        $CustomerOrders = DB::table('master_order_details')
            ->select('master_order_details.CUSTOMER_ID', 'master_order_details.ORDERED_DATE', 'customer.customer_name', 'customer.customer_grade')
            ->join('customer', 'customer.customer_id', '=', 'master_order_details.CUSTOMER_ID')
            ->orderBy('master_order_details.ORDERED_DATE')
            ->distinct()
            ->get();
        // dd($CustomerOrders);
        return view('Admin.ManageLockStock.managelockstock', compact('CustomerOrders'));
    }

    public function DetailLockStock($CUS_ID, $ORDER_DATE)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $CustomerOrders = DB::table('master_order_details')
            ->join('product', 'product.item_no', '=', 'master_order_details.ORDERED_ITEM')
            ->join('customer', 'customer.customer_id', '=', 'master_order_details.CUSTOMER_ID')
            ->whereDate('master_order_details.ORDERED_DATE', $ORDER_DATE)
            ->where('master_order_details.CUSTOMER_ID', $CUS_ID)
<<<<<<< HEAD
=======
            ->get();
        /* $CustomerOrders = DB::table('customer_order')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->join('customer_order_detail', 'customer_order.order_number', '=', 'customer_order_detail.order_number')
            ->join('product', 'customer_order_detail.product_id', '=', 'product.item_id')
            ->where('customer_order_detail.order_number', '=', $order_id)
>>>>>>> ae5a6fc80950f755bbc31442c9c0a22485b41237
            ->get();
        // $CustomerOrders = DB::table('customer_order')
        //     ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
        //     ->join('customer_order_detail', 'customer_order.order_number', '=', 'customer_order_detail.order_number')
        //     ->join('product', 'customer_order_detail.product_id', '=', 'product.item_id')
        //     ->where('customer_order_detail.order_number', '=', $order_id)
        //     ->get();
        // dd($CustomerOrders);
        $Pallets = DB::table('pallet')
            ->select(
                'pallet.*',
                'lock_team.team_name',
                'pallet_type.pallet_type'
            )
            ->leftJoin('lock_team', 'pallet.team_id', '=', 'lock_team.id')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->whereDate('order_date', $ORDER_DATE)
            ->where('customer_id', $CUS_ID)
            ->get();
        //dd($Pallets);
        return view('Admin.ManageLockStock.DetailLockStock', compact('CustomerOrders', 'Pallets', 'CUS_ID', 'ORDER_DATE'));
    }

<<<<<<< HEAD
    public function AddPallet($order_id)
    {
        // dd($CUS_ID , $ORDER_DATE);
=======
    public function AddPallet($order_number)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

>>>>>>> ae5a6fc80950f755bbc31442c9c0a22485b41237
        // $Pallets = DB::table('customer_order')
        //     ->join('pallet', 'customer_order.order_number', '=', 'customer_order.order_number')
        //     ->join('pallet_order', 'pallet.pallet_id', '=', 'pallet_order.pallet_id')
        //     ->join('customer_order_detail', 'pallet_order.product_id', '=', 'customer_order_detail.product_id')
        //     ->where('customer_order.order_number', '=', $order_number)
        //     ->get();
        $pallet_type = DB::table('pallet_type')->get();
        return view('Admin.ManageLockStock.AddPallet', compact('pallet_type'));
    }

    public function SavePallet($order_number, Request $request)
    {
        $data = $request->all();
        // dd($data);
        // dd(session()->get('pallet'));
        session()->push('pallet', $data);

        return redirect()->back()->with('success', 'Data saved successfully');
    }
    public function Remove_Pallet($key)
    {
        $pallet = session()->pull('pallet', []);

        // ตรวจสอบว่ามีข้อมูลในตำแหน่ง 0 หรือไม่
        if (isset($pallet[$key])) {
            unset($pallet[$key]); // ลบ array ตำแหน่ง 0
        }

        // รีเรียง index ใหม่ให้เรียงลำดับหลังลบ
        $pallet = array_values($pallet);

        // บันทึกข้อมูลกลับเข้า session
        session()->put('pallet', $pallet);

        return redirect()->back();
        // dd($key);
    }

    public function update_lock_team(Request $request, $id)
    {
        try {
            DB::table('pallet')->where('id', $id)
                ->update([
                    'team_id' => $request['team_id']
                ]);
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th);
        }
    }
    public function forgetSession($CUS_ID, $ORDERED_DATE)
    {
        // dd($id);
        session()->forget('lock' . $CUS_ID);

        return back()->with('success', 'Data cleared successfully');
    }
    public function insert_pallet($CUS_ID, $ORDER_DATE)
    {
        $data = session()->get('lock' . $CUS_ID);

        DB::table('pallet')->truncate();
        DB::table('pallet_order')->truncate();
        DB::table('confirmOrder')->truncate();
        try {
            DB::transaction(function () use ($data, $CUS_ID, $ORDER_DATE) {
                $counter = 1;
                $convertDate = str_replace("/", "", $ORDER_DATE);
                $palletId = sprintf('%s%s', $CUS_ID, $convertDate);

                foreach ($data as $data) {
                    $id = DB::table('pallet')->insertGetId([
                        'pallet_id' => $palletId,
                        'pallet_no' => $counter,
                        'room' => $data['warehouse'],
                        'customer_id' => $CUS_ID,
                        'order_date' => $ORDER_DATE,
                        'team_id' => null,
                        'pallet_type_id' => '1',
                        'note' => null,
                        'status' => false,
                        'recive_status' => false,
                        'created_at' => now(),
                    ]);

                    foreach ($data['items'] as $item) {
                        DB::table('confirmOrder')->insert([
                            'order_id' => $item['order_id'],
                            'pallet_id' => $id,
                            'product_id' => $item['item_id'],
                            'quantity' => $item['quantity'],
                            'quantity2' => null,
                            'product_work_desc' => $data['work_type'],
                            'confirm_order_status' => false,
                            'confirm_at' => null,
                            'created_at' => now()
                        ]);
                    }
                    $counter++;
                }
            });
            session()->forget('lock');
        } catch (Throwable $e) {
            return response()->json(['error' => $e], 500);
        }

        return redirect()->route('DetailLockStock', [$CUS_ID, $ORDER_DATE])->with('success', 'Data saved successfully');
    }

    public function DetailPallets($ORDER_DATE, $CUS_ID, $pallet_id)
    {
<<<<<<< HEAD
        $Pallets = DB::table('confirmOrder')
            ->select(
                'pallet.id',
                'pallet.pallet_id',
                'pallet.status as status',
                'warehouse.whs_name',
                'master_order_details.ORDERED_QUANTITY',
                'master_order_details.ORDERED_QUANTITY2',
                'master_order_details.UOM1',
                'master_order_details.UOM2',
                'confirmOrder.quantity',
                'pallet_type.pallet_type',
                'product.item_no',
                'product.item_desc1',
                'product_work_desc.product_work_desc',
                'lock_team.team_name'
            )
            ->join('pallet', 'pallet.id', '=', 'confirmOrder.pallet_id')
            ->leftJoin('lock_team', 'pallet.team_id', '=', 'lock_team.id')
            ->join('warehouse', 'warehouse.id', '=', 'pallet.room')
            ->join('pallet_type', 'pallet_type.id', '=', 'pallet.pallet_type_id')
            ->join('customer', 'customer.customer_id', '=', 'pallet.customer_id')
            ->join('master_order_details', 'master_order_details.id', '=', 'confirmOrder.order_id')
            ->join('product', 'product.item_id', '=', 'confirmOrder.product_id')
            ->join('product_work_desc', 'product_work_desc.id', '=', 'confirmOrder.product_work_desc')
            ->where('confirmOrder.pallet_id', $pallet_id)
=======
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $Pallets = DB::table('pallet_order')
            ->join('product', 'pallet_order.product_id', '=', 'product.item_id')
            ->join('pallet', 'pallet_order.pallet_id', '=', 'pallet.id')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->join('confirmOrder', 'pallet_order.id', '=', 'confirmOrder.pallet_order_id')
            ->join('customer_order', 'confirmOrder.order_id', '=', 'customer_order.order_number')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->leftJoin('customer_order_detail', function ($join) use ($order_number) {
                $join->on('pallet_order.product_id', '=', 'customer_order_detail.product_id')
                    ->where('customer_order_detail.order_number', '=', $order_number);
            })
            ->where('pallet_order.pallet_id', '=', $pallet_id)
>>>>>>> ae5a6fc80950f755bbc31442c9c0a22485b41237
            ->get();

        // dd($Pallets);
        return view('Admin.ManageLockStock.DetailPellets', compact('Pallets', 'ORDER_DATE', 'CUS_ID'));
    }

    public function EditPalletOrder($order_id, $product_id)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $data = DB::table('confirmOrder')
            ->join('product', 'confirmOrder.product_id', '=', 'product.item_id')
            ->where('confirmOrder.product_id', '=', $product_id)
            ->where('confirmOrder.order_id', '=', $order_id)
            ->get();
        // dd($data);
        return view('Admin.ManageLockStock.EditPalletOrder', compact('data'));
    }
    public function AutoCompleteAddPallet(Request $request)
    {
        $query = $request->get('query');
        $type = $request->get('type');
        // $order_number = $request->get('order_number');
        $data = DB::table('product')
            ->select('item_id', 'item_desc1', 'item_no', 'item_um', 'item_um2')
            ->where('item_desc1', 'like', '%' . $query . '%')
            ->distinct()
            ->limit(10)
            ->get();
        // ตรวจสอบเงื่อนไข และทำ Query
        /*if ($type == 2) {
            $data = DB::table('product')
                ->select('item_id', 'item_desc1', 'item_no', 'item_um', 'item_um2')
                ->where('item_desc1', 'like', '%' . $query . '%')
                ->distinct()
                ->limit(10)
                ->get();
        } else {
            $data = DB::table('product')
                ->join('customer_order_detail', 'customer_order_detail.product_id', '=', 'product.item_id')
                ->select(
                    'customer_order_detail.product_id',
                    'product.item_desc1',
                    'product.item_no',
                    'product.item_id',
                    'order_quantity',
                    'product.item_um',
                    'order_quantity2',
                    'product.item_um2'
                )
                ->where('item_desc1', 'like', '%' . $query . '%')
                ->where('order_number', '=', $order_number)
                ->distinct()
                ->limit(10)
                ->get();
        }*/

        // แปลงข้อมูลให้อยู่ในรูปแบบที่ jQuery autocomplete ต้องการ
        $results = $data->map(function ($item) {
            return [
                'label' => $item->item_desc1,
                'value' => $item->item_desc1,
                'product_no' => $item->item_no,
                'product_id' => $item->item_id,
                'ordered_quantity' => $item->order_quantity ?? 0,
                'ordered_quantity_UM' => $item->item_um,
                'ordered_quantity2' => $item->order_quantity2 ?? 0,
                'ordered_quantity_UM2' => $item->item_um2 ?? 'ไม่มี',
            ];
        });

        return response()->json($results);
    }

    function ShowPrelock($CUS_ID, $ORDER_DATE)
    {
<<<<<<< HEAD
        $pallet_type = DB::table('pallet_type')->get();
        return view('Admin.ManageLockStock.AutoLock', compact('CUS_ID', 'ORDER_DATE', 'pallet_type'));
    }

    //คุณธีรพล พูลเพิ่ม test
    function AutoLock($CUS_ID, $ORDER_DATE)
    {
        $CustomerOrders = DB::table('master_order_details')
            ->select(
                'master_order_details.id as order_id',
                'master_order_details.ORDER_NUMBER as ORDER_NUMBER',
                'master_order_details.CUSTOMER_ID as CUSTOMER_ID',
                'master_order_details.ORDERED_DATE as ORDERED_DATE',
                'master_order_details.ORDER_BY_CUS as ORDER_BY_CUS',
                'master_order_details.ORDERED_QUANTITY as ORDERED_QUANTITY',
                'master_order_details.ORDERED_QUANTITY2 as ORDERED_QUANTITY2',
                'master_order_details.UOM1 as UOM1',
                'master_order_details.UOM2 as UOM2',
                'product.*',
                'customer.*'
            )
=======
        return view('Admin.ManageLockStock.AutoLock', compact('CUS_ID', 'ORDER_DATE'));
    }

    function AutoLock($CUS_ID, $ORDER_DATE)
    {
        $CustomerOrders = DB::table('master_order_details')
>>>>>>> ae5a6fc80950f755bbc31442c9c0a22485b41237
            ->join('product', 'product.item_no', '=', 'master_order_details.ORDERED_ITEM')
            ->join('customer', 'customer.customer_id', '=', 'master_order_details.CUSTOMER_ID')
            ->whereDate('master_order_details.ORDERED_DATE', $ORDER_DATE)
            ->where('master_order_details.CUSTOMER_ID', $CUS_ID)
            ->orderBy('master_order_details.ORDER_BY_CUS')
            ->get();

        $this->splitItem($CustomerOrders, $CUS_ID);

        return back();
    }
<<<<<<< HEAD
    function splitItem($CustomerOrders, $CUS_ID)
=======
    function splitItem($CustomerOrders)
>>>>>>> ae5a6fc80950f755bbc31442c9c0a22485b41237
    {
        $lock_items = []; // สำหรับจัดกลุ่มสินค้า (ล็อก)
        $current_group = []; // กลุ่มสินค้าที่กำลังสร้าง
        $current_weight = []; // น้ำหนักสะสมของแต่ละ warehouse และแต่ละลักษณะงาน

        foreach ($CustomerOrders as $itemOrder) {
<<<<<<< HEAD
            $warehouse = $itemOrder->warehouse;
            $work_type = $itemOrder->item_work_desc;

            // ถ้ายังไม่มีข้อมูล warehouse นี้ใน lock_items และ current_weight ให้เริ่มต้น
            if (!isset($current_group[$warehouse][$work_type])) {
                $current_group[$warehouse][$work_type] = [];
                $current_weight[$warehouse][$work_type] = 0;
            }

            if ($itemOrder->UOM1 === 'Kg') {
                $this->split_product_work_type(
                    $itemOrder,
                    $itemOrder->ORDERED_QUANTITY,
                    $current_group[$warehouse][$work_type],
                    $current_weight[$warehouse][$work_type],
                        $lock_items
                );
            } else if ($itemOrder->UOM2 === 'Kg') {
                $this->split_product_work_type(
                    $itemOrder,
                    $itemOrder->ORDERED_QUANTITY2,
                    $current_group[$warehouse][$work_type],
                    $current_weight[$warehouse][$work_type],
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
                        'items' => $group,
                    ];
                }
            }
=======
            if ($itemOrder->ORDER_BY_CUS <= 850) {
                $this->smallOrder($itemOrder, $itemOrder->ORDER_BY_CUS, $current_group, $current_weight, $lock_items);
            } else {
                $this->largeOrder($itemOrder, $itemOrder->ORDER_BY_CUS, $current_group, $current_weight, $lock_items);
            }
        }

        if (!empty($current_group)) {
            $lock_items[] = $current_group;
        }

        dd($lock_items);
    }

    function smallOrder($itemOrder, $order_by_cus, &$current_group, &$current_weight, &$lock_items)
    {
        if ($current_weight + $order_by_cus >= 850) {
            $lock_items[] = $current_group;
            $current_group = [];
            $current_weight = 0;
>>>>>>> ae5a6fc80950f755bbc31442c9c0a22485b41237
        }

        session()->put('lock' . $CUS_ID, $lock_items);

<<<<<<< HEAD
        //dd($lock_items);
        //dd(session()->all());
    }

    function split_product_work_type($itemOrder, $quantity, &$current_group, &$current_weight, &$lock_items)
    {
        if ($itemOrder->item_work_desc === 'แยกจ่าย') {
            $this->processItem($itemOrder, $quantity, $current_group, $current_weight, $lock_items);
        } else if ($itemOrder->item_work_desc === 'รับจัด') {
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
                'warehouse' => $itemOrder->warehouse,
                'items' => $current_group
            ];
            $current_group = [];
            $current_weight = 0;
        }

        // เพิ่มสินค้าเข้าในกลุ่ม
        $current_group[] = [
            'order_id' => $itemOrder->order_id,
            'order_no' => $itemOrder->ORDER_NUMBER,
            'CUSTOMER_ID' => $itemOrder->CUSTOMER_ID,
            'ORDERED_DATE' => $itemOrder->ORDERED_DATE,
            'item_id' => $itemOrder->item_id,
            'item_no' => $itemOrder->item_no,
            'item_desc1' => $itemOrder->item_desc1,
            'ORDER_BY_CUS' => $itemOrder->ORDER_BY_CUS,
            'ORDERED_QUANTITY' => $itemOrder->ORDERED_QUANTITY,
            'UOM1' => $itemOrder->UOM1,
            'ORDERED_QUANTITY2' => $itemOrder->ORDERED_QUANTITY2,
            'UOM2' => $itemOrder->UOM2,
            'quantity' => $quantity,
            'quantity_um' => 'Kg',
            'warehouse' => $itemOrder->warehouse,
            'work_type' => $itemOrder->item_work_desc,
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
            'warehouse' => $itemOrder->warehouse,
            'items' =>
            [
                [
                    'order_id' => $itemOrder->id,
                    'order_no' => $itemOrder->ORDER_NUMBER,
                    'CUSTOMER_ID' => $itemOrder->CUSTOMER_ID,
                    'ORDERED_DATE' => $itemOrder->ORDERED_DATE,
                    'item_id' => $itemOrder->item_id,
                    'item_no' => $itemOrder->item_no,
                    'item_desc1' => $itemOrder->item_desc1,
                    'ORDER_BY_CUS' => $itemOrder->ORDER_BY_CUS,
                    'ORDERED_QUANTITY' => $itemOrder->ORDERED_QUANTITY,
                    'UOM1' => $itemOrder->UOM1,
                    'ORDERED_QUANTITY2' => $itemOrder->ORDERED_QUANTITY2,
                    'UOM2' => $itemOrder->UOM2,
                    'quantity' => $quantity,
                    'quantity_um' => 'Kg',
                    'warehouse' => $itemOrder->warehouse,
                    'work_type' => $itemOrder->item_work_desc,
                ]
            ]
=======
        $current_weight += $order_by_cus;
    }

    function largeOrder($itemOrder, $order_by_cus, &$current_group, &$current_weight, &$lock_items)
    {
        $remaining_quantity = $order_by_cus;
        while ($remaining_quantity > 850) {
            $lock_items[][] = [
                'item_no' => $itemOrder->item_no,
                'item_desc1' => $itemOrder->item_desc1,
                'quantity' => 850,
            ];
            $remaining_quantity -= 850; // ลดน้ำหนักที่เหลือ
        }
        // เพิ่มส่วนที่เหลือ (ถ้ามี)
        $current_group[] = [
            'item_no' => $itemOrder->item_no,
            'item_desc1' => $itemOrder->item_desc1,
            'quantity' => $remaining_quantity,
>>>>>>> ae5a6fc80950f755bbc31442c9c0a22485b41237
        ];
    }
}