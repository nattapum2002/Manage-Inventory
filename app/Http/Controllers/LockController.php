<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            ->get();
        /* $CustomerOrders = DB::table('customer_order')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->join('customer_order_detail', 'customer_order.order_number', '=', 'customer_order_detail.order_number')
            ->join('product', 'customer_order_detail.product_id', '=', 'product.item_id')
            ->where('customer_order_detail.order_number', '=', $order_id)
            ->get();
        // dd($CustomerOrders);
        $Pallets = DB::table('pallet')
            ->where('order_id', '=', $order_id)
            ->selectRaw('pallet.id,MAX(pallet_type.pallet_type) as pallet_type ,MAX(pallet.pallet_id) as pallet_id ,MAX(pallet_no) as pallet_no, MAX(room) as room, pallet.status ,pallet.recive_status ,MAX(pallet.note) as note, MAX(lock_team.team_name) as team_name')
            ->join('pallet_order', 'pallet.id', '=', 'pallet_order.pallet_id')
            ->join('product', 'pallet_order.product_id', '=', 'product.item_id')
            ->join('lock_team', 'pallet.team_id', '=', 'lock_team.team_id')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->groupBy('pallet.id', 'pallet.status', 'pallet.recive_status')
            ->get();*/
        // dd($CustomerOrders);
        return view('Admin.ManageLockStock.DetailLockStock', compact('CustomerOrders'));
    }

    public function AddPallet($order_number)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

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
    public function forgetSession()
    {
        // dd($id);
        session()->forget('pallet');

        return redirect()->back()->with('success', 'Data clean successfully');
    }
    public function insert_pallet($order_number)
    {
        $data = session()->get('pallet');
        // dd(vars: $data);
        DB::table('pallet')->truncate();
        DB::table('pallet_order')->truncate();
        DB::table('confirmOrder')->truncate();
        DB::transaction(function () use ($data, $order_number) {
            foreach ($data as $key => $value) {
                $id = DB::table('pallet')->insertGetId([
                    'pallet_id' => $value['pallet_id'],
                    'pallet_no' => $value['pallet_no'],
                    'room' => $value['room'],
                    'team_id' => $value['team_id'],
                    'order_id' => $order_number,
                    'pallet_type_id' => $value['pallet_type_id'],
                    'note' => $value['note'] ?? null,
                    'status' => 0,
                    'created_at' => now(),
                ]);
                foreach ($value['product_id'] as $key => $product) {
                    $pallet_order_id = DB::table('pallet_order')->insertGetId([
                        'pallet_id' => $id,
                        'product_id' => $product,
                        'created_at' => now(),
                    ]);
                    DB::table('confirmOrder')->insert([
                        'order_id' => $order_number,
                        'pallet_order_id' => $pallet_order_id,
                        'product_id' => $product,
                        'quantity' => $value['quantity'][$key],
                        'quantity2' => $value['quantity2'][$key] ?? 0,
                        'created_at' => now(),
                    ]);
                }
            }
        });
        session()->forget('pallet');
        return redirect()->route('DetailLockStock', ['order_number' => $order_number])->with('success', 'Data saved successfully');
    }

    public function DetailPallets($order_number, $pallet_id)
    {
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
            ->get();

        // dd($Pallets);
        return view('Admin.ManageLockStock.DetailPellets', compact('Pallets'));
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
    public function AutoCompleteAddPallet(Request $request, $order_number)
    {
        $query = $request->get('query');
        $type = $request->get('type');
        // $order_number = $request->get('order_number');

        // ตรวจสอบเงื่อนไข และทำ Query
        if ($type == 2) {
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
        }

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
        return view('Admin.ManageLockStock.AutoLock', compact('CUS_ID', 'ORDER_DATE'));
    }

    function AutoLock($CUS_ID, $ORDER_DATE)
    {
        $CustomerOrders = DB::table('master_order_details')
            ->join('product', 'product.item_no', '=', 'master_order_details.ORDERED_ITEM')
            ->join('customer', 'customer.customer_id', '=', 'master_order_details.CUSTOMER_ID')
            ->whereDate('master_order_details.ORDERED_DATE', $ORDER_DATE)
            ->where('master_order_details.CUSTOMER_ID', $CUS_ID)
            ->orderBy('master_order_details.ORDER_BY_CUS')
            ->get();

        $this->splitItem($CustomerOrders);
    }
    function splitItem($CustomerOrders)
    {
        $lock_items = []; // สำหรับจัดกลุ่มสินค้า (ล็อก)
        $current_group = []; // กลุ่มสินค้าที่กำลังสร้าง
        $current_weight = 0; // น้ำหนักสะสมของกลุ่ม

        foreach ($CustomerOrders as $itemOrder) {
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
        }

        $current_group[] = [
            'item_no' => $itemOrder->item_no,
            'item_desc1' => $itemOrder->item_desc1,
            'quantity' => $order_by_cus,
        ];

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
        ];
    }
}