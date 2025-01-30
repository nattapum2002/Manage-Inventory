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
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $CustomerOrders = DB::table('orders')
            ->join('customer', 'orders.customer_id', '=', 'customer.customer_id')
            ->select(
                'orders.customer_id',
                'orders.customer_number',
                'orders.order_date',
                'orders.ship_datetime',
                'customer.customer_name',
                'customer.customer_grade'
            )
            ->orderBy('orders.order_date')
            ->distinct()
            ->get();

        return view('Admin.ManageLockStock.managelockstock', compact('CustomerOrders'));
    }

    public function DetailLockStock($CUS_ID, $ORDER_DATE)
    {
        $CustomerOrders = DB::table('orders')
            ->join('order_detail', 'orders.order_number', '=', 'order_detail.order_number')
            ->join('product', 'order_detail.product_id', '=', 'product.product_id')
            ->join('customer', 'orders.customer_id', '=', 'customer.customer_id')
            ->select(
                'orders.order_number',
                'orders.order_date',
                'order_detail.quantity',
                'order_detail.quantity2',
                'customer.customer_name',
                'customer.customer_id',
                'product.product_number',
                'product.product_description',
                'product.product_um',
                'product.product_um2',
            )
            ->whereDate('orders.order_date', $ORDER_DATE)
            ->where('orders.customer_id', $CUS_ID)
            ->get();

        $Pallets = DB::table('pallet')
            ->join('orders', 'pallet.order_number', '=', 'orders.order_number')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->leftJoin('pallet_team', 'pallet.pallet_id', '=', 'pallet_team.pallet_id')
            ->leftJoin('team', 'pallet_team.team_id', '=', 'team.id')
            ->select(
                'pallet.*',
                'team.team_name',
                'pallet_team.team_id',
                'pallet_type.pallet_type'
            )
            ->whereDate('orders.order_date', $ORDER_DATE)
            ->where('orders.customer_id', $CUS_ID)
            ->get();

        // dd($Pallets, $CUS_ID, $ORDER_DATE);

        return view('Admin.ManageLockStock.DetailLockStock', compact('CustomerOrders', 'Pallets', 'CUS_ID', 'ORDER_DATE'));
    }

    public function AddPallet($order_id)
    {
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

    // public function update_lock_team(Request $request, $id)
    // {
    //     try {
    //         DB::table('pallet')->where('id', $id)
    //             ->update([
    //                 'team_id' => $request['team_id']
    //             ]);
    //         return back();
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return response()->json($th);
    //     }
    // }

    public function update_lock_team(Request $request, $id)
    {
        try {
            DB::table('pallet_team')
                ->updateOrInsert(
                    ['pallet_id' => $id],
                    ['team_id' => $request['team_id']]
                );
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th);
        }
    }

    public function forgetSession($CUS_ID, $order_date)
    {
        // dd($id);
        session()->forget('lock' . $CUS_ID);

        return back()->with('success', 'Data cleared successfully');
    }

    // public function insert_pallet($CUS_ID, $ORDER_DATE)
    // {
    //     $data = session()->get('lock' . $CUS_ID);

    //     dd($data);

    //     DB::table('pallet')->truncate();
    //     DB::table('pallet_order')->truncate();
    //     DB::table('confirmOrder')->truncate();
    //     try {
    //         DB::transaction(function () use ($data, $CUS_ID, $ORDER_DATE) {
    //             $counter = 1;
    //             $convertDate = str_replace("/", "", $ORDER_DATE);
    //             $palletId = sprintf('%s%s', $CUS_ID, $convertDate);

    //             foreach ($data as $data) {
    //                 $id = DB::table('pallet')->insertGetId([
    //                     'pallet_id' => $palletId,
    //                     'pallet_no' => $counter,
    //                     'room' => $data['warehouse'],
    //                     'customer_id' => $CUS_ID,
    //                     'order_date' => $ORDER_DATE,
    //                     'team_id' => null,
    //                     'pallet_type_id' => '1',
    //                     'note' => null,
    //                     'status' => false,
    //                     'recive_status' => false,
    //                     'created_at' => now(),
    //                 ]);

    //                 foreach ($data['items'] as $item) {
    //                     DB::table('confirmOrder')->insert([
    //                         'order_id' => $item['order_id'],
    //                         'pallet_id' => $id,
    //                         'product_id' => $item['product_id'],
    //                         'quantity' => $item['quantity'],
    //                         'quantity2' => null,
    //                         'product_work_desc' => $data['work_type'],
    //                         'confirm_order_status' => false,
    //                         'confirm_at' => null,
    //                         'created_at' => now()
    //                     ]);
    //                 }
    //                 $counter++;
    //             }
    //         });
    //         session()->forget('lock');
    //     } catch (Throwable $e) {
    //         return response()->json(['error' => $e], 500);
    //     }

    //     return redirect()->route('DetailLockStock', [$CUS_ID, $ORDER_DATE])->with('success', 'Data saved successfully');
    // }

    public function insert_pallet($CUS_ID, $ORDER_DATE)
    {
        $data = session()->get('lock' . $CUS_ID);

        DB::table('pallet')->truncate();
        DB::table('pallet_detail')->truncate();
        DB::table('confirmOrder')->truncate();
        try {
            DB::transaction(function () use ($data, $CUS_ID, $ORDER_DATE) {
                $counter = 1;
                $convertDate = str_replace(["/", "-"], "", $ORDER_DATE);
                // dd($data, $palletId, $CUS_ID, $ORDER_DATE);
                foreach ($data as $data) {
                    $palletId = sprintf('%s%s%s', $CUS_ID, $convertDate, $counter);
                    DB::table('pallet')->insert([
                        'pallet_id' => $palletId,
                        'order_number' => str_replace(" ", "", $data['order_no']),
                        'pallet_name' => $counter,
                        'pallet_type' => '1',
                        'warehouse_id' => $data['warehouse'],
                        'note' => null,
                        'arrange_pallet_status' => false,
                        'recive_status' => false,
                    ]);

                    $insertPalletDetail = [];
                    $insertConfirmOrder = [];

                    foreach ($data['items'] as $item) {
                        $insertPalletDetail[] = [
                            'pallet_id' => $palletId,
                            'product_id' => $item['product_id'],
                            'product_number' => $item['product_number'],
                            'quantity' => $item['quantity'],
                            'quantity2' => $item['quantity2'],
                        ];

                        $insertConfirmOrder[] = [
                            'order_id' => $data['order_id'],
                            'pallet_id' => $palletId,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'quantity2' => null,
                            'product_work_desc_id' => $data['work_type'],
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
            });
            session()->forget('lock');
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return redirect()->route('DetailLockStock', [$CUS_ID, $ORDER_DATE])->with('success', 'Data saved successfully');
    }

    // public function DetailPallets($ORDER_DATE, $CUS_ID, $pallet_id)
    // {
    //     $Pallets = DB::table('confirmOrder')
    //         ->select(
    //             'pallet.id',
    //             'pallet.pallet_id',
    //             'pallet.status as status',
    //             'warehouse.warehouse',
    //             'master_order_details.quantity',
    //             'master_order_details.quantity2',
    //             'product.product_um',
    //             'product.product_um2',
    //             'confirmOrder.quantity',
    //             'pallet.pallet_type',
    //             'product.product_number',
    //             'product.product_description',
    //             'product_work_desc.product_work_desc',
    //             'team.team_name'
    //         )
    //         ->join('pallet', 'pallet.id', '=', 'confirmOrder.pallet_id')
    //         ->leftJoin('team', 'pallet.team_id', '=', 'team.id')
    //         ->join('warehouse', 'warehouse.id', '=', 'pallet.room')
    //         ->join('customer', 'customer.customer_id', '=', 'pallet.customer_id')
    //         ->join('master_order_details', 'master_order_details.id', '=', 'confirmOrder.order_id')
    //         ->join('product', 'product.product_id', '=', 'confirmOrder.product_id')
    //         ->join('product_work_desc', 'product_work_desc.id', '=', 'confirmOrder.product_work_desc')
    //         ->where('confirmOrder.pallet_id', $pallet_id)->get();


    //     // dd($Pallets);
    //     return view('Admin.ManageLockStock.DetailPellets', compact('Pallets', 'ORDER_DATE', 'CUS_ID'));
    // }

    public function DetailPallets($ORDER_DATE, $CUS_ID, $pallet_id)
    {
        $Pallets = DB::table('pallet')
            // ->join('orders', 'pallet.order_number', '=', 'orders.order_number')
            // ->join('customer', 'customer.customer_id', '=', 'orders.customer_id')
            ->join('pallet_detail', 'pallet.pallet_id', '=', 'pallet_detail.pallet_id')
            ->join('product', 'pallet_detail.product_id', '=', 'product.product_id')
            ->join('product_work_desc', 'product.product_work_desc_id', '=', 'product_work_desc.id')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->leftJoin('warehouse', 'product.warehouse_id', '=', 'warehouse.id')
            ->leftJoin('pallet_team', 'pallet.pallet_id', '=', 'pallet_team.pallet_id')
            ->leftJoin('team', 'pallet_team.team_id', '=', 'team.team_id')
            ->select(
                'pallet.id',
                'pallet.pallet_id',
                'pallet.arrange_pallet_status as status',
                'warehouse.warehouse_name as warehouse',
                'pallet_detail.quantity',
                'pallet_detail.quantity2',
                'product.product_um',
                'product.product_um2',
                // 'confirmOrder.quantity',
                'pallet_type.pallet_type',
                'product.product_number',
                'product.product_description',
                'product_work_desc.product_work_desc',
                'team.team_name'
            )
            ->where('pallet.pallet_id', $pallet_id)
            ->get();


        // dd($Pallets, $pallet_id);
        return view('Admin.ManageLockStock.DetailPellets', compact('Pallets', 'ORDER_DATE', 'CUS_ID'));
    }

    public function EditPalletOrder($order_id, $product_id)
    {
        $data = DB::table('confirmOrder')
            ->join('product', 'confirmOrder.product_id', '=', 'product.product_id')
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
            ->select('product_id', 'product_description', 'product_number', 'product_um', 'product_um2')
            ->where('product_description', 'like', '%' . $query . '%')
            ->distinct()
            ->limit(10)
            ->get();
        // ตรวจสอบเงื่อนไข และทำ Query
        /*if ($type == 2) {
            $data = DB::table('product')
                ->select('product_id', 'product_description', 'product_number', 'product_um', 'product_um2')
                ->where('product_description', 'like', '%' . $query . '%')
                ->distinct()
                ->limit(10)
                ->get();
        } else {
            $data = DB::table('product')
                ->join('customer_order_detail', 'customer_order_detail.product_id', '=', 'product.product_id')
                ->select(
                    'customer_order_detail.product_id',
                    'product.product_description',
                    'product.product_number',
                    'product.product_id',
                    'order_quantity',
                    'product.product_um',
                    'order_quantity2',
                    'product.product_um2'
                )
                ->where('product_description', 'like', '%' . $query . '%')
                ->where('order_number', '=', $order_number)
                ->distinct()
                ->limit(10)
                ->get();
        }*/

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

    function ShowPrelock($CUS_ID, $ORDER_DATE)
    {
        $pallet_type = DB::table('pallet_type')->get();
        return view('Admin.ManageLockStock.AutoLock', compact('CUS_ID', 'ORDER_DATE', 'pallet_type'));
    }

    //คุณธีรพล พูลเพิ่ม test
    function AutoLock($CUS_ID, $ORDER_DATE)
    {
        $CustomerOrders = DB::table('orders')
            ->join('order_detail', 'orders.order_number', '=', 'order_detail.order_number')
            ->join('product', 'order_detail.product_id', '=', 'product.product_id')
            ->join('product_work_desc', 'product.product_work_desc_id', '=', 'product_work_desc.id')
            ->join('customer', 'orders.customer_id', '=', 'customer.customer_id')
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
                'product.*',
                'customer.*'
            )
            ->whereDate('orders.order_date', $ORDER_DATE)
            ->where('orders.customer_id', $CUS_ID)
            ->orderBy('quantity')
            ->get();

        $this->splitItem($CustomerOrders, $CUS_ID);

        return back();
    }

    function splitItem($CustomerOrders, $CUS_ID)
    {
        $lock_items = []; // สำหรับจัดกลุ่มสินค้า (ล็อก)
        $current_group = []; // กลุ่มสินค้าที่กำลังสร้าง
        $current_weight = []; // น้ำหนักสะสมของแต่ละ warehouse และแต่ละลักษณะงาน

        foreach ($CustomerOrders as $itemOrder) {
            $warehouse = $itemOrder->warehouse;
            $work_type = $itemOrder->item_work_desc;

            // ถ้ายังไม่มีข้อมูล warehouse นี้ใน lock_items และ current_weight ให้เริ่มต้น
            if (!isset($current_group[$warehouse][$work_type])) {
                $current_group[$warehouse][$work_type] = [];
                $current_weight[$warehouse][$work_type] = 0;
            }

            if ($itemOrder->product_um === 'Kg') {
                $this->split_product_work_type(
                    $itemOrder,
                    $itemOrder->quantity,
                    $current_group[$warehouse][$work_type],
                    $current_weight[$warehouse][$work_type],
                    $lock_items
                );
            } else if ($itemOrder->product_um2 === 'Kg') {
                $this->split_product_work_type(
                    $itemOrder,
                    $itemOrder->quantity2,
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
        }

        session()->put('lock' . $CUS_ID, $lock_items);
        //dd($lock_items);
        //dd(session()->all());
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

    // function smallOrder($itemOrder, $quantity, &$current_group, &$current_weight, &$lock_items)
    // {
    //     if ($current_weight + $quantity > 850) {
    //         // เก็บกลุ่มปัจจุบันลงใน lock_items และรีเซ็ต
    //         $lock_items[] = [
    //             'warehouse' => $itemOrder->warehouse,
    //             'items' => $current_group
    //         ];
    //         $current_group = [];
    //         $current_weight = 0;
    //     }

    //     // เพิ่มสินค้าเข้าในกลุ่ม
    //     $current_group[] = [
    //         'order_id' => $itemOrder->ORDER_NUMBER,
    //         'order_no' => $itemOrder->ORDER_NUMBER,
    //         'customer_id' => $itemOrder->customer_id,
    //         'order_date' => $itemOrder->order_date,
    //         'product_id' => $itemOrder->product_id,
    //         'product_number' => $itemOrder->product_number,
    //         'product_description' => $itemOrder->product_description,
    //         'ORDER_BY_CUS' => $itemOrder->ORDER_BY_CUS,
    //         'quantity' => $itemOrder->quantity,
    //         'product_um' => $itemOrder->product_um,
    //         'quantity2' => $itemOrder->quantity2,
    //         'product_um2' => $itemOrder->product_um2,
    //         'quantity' => $quantity,
    //         'quantity_um' => 'Kg',
    //         'warehouse' => $itemOrder->warehouse,
    //         'work_type' => null,
    //     ];
    //     $current_weight += $quantity;
    // }

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
            'order_id' => $itemOrder->id,
            'order_no' => $itemOrder->ORDER_NUMBER,
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

    // function LargeOrderAddData($itemOrder, $quantity, &$lock_items)
    // {
    //     $lock_items[] = [
    //         'warehouse' => $itemOrder->warehouse,
    //         'work_type' => null,
    //         'items' =>
    //         [
    //             [
    //                 'order_id' => $itemOrder->id,
    //                 'order_no' => $itemOrder->ORDER_NUMBER,
    //                 'customer_id' => $itemOrder->customer_id,
    //                 'order_date' => $itemOrder->order_date,
    //                 'product_id' => $itemOrder->product_id,
    //                 'product_number' => $itemOrder->product_number,
    //                 'product_description' => $itemOrder->product_description,
    //                 'ORDER_BY_CUS' => $itemOrder->ORDER_BY_CUS,
    //                 'quantity' => $itemOrder->quantity,
    //                 'product_um' => $itemOrder->product_um,
    //                 'quantity2' => $itemOrder->quantity2,
    //                 'product_um2' => $itemOrder->product_um2,
    //                 'quantity' => $quantity,
    //                 'quantity_um' => 'Kg',
    //                 'warehouse' => $itemOrder->warehouse,
    //                 'work_type' => null,
    //             ]
    //         ]
    //     ];
    // }

    function LargeOrderAddData($itemOrder, $quantity, &$lock_items)
    {
        $lock_items[] = [
            'warehouse' => $itemOrder->warehouse,
            'work_type' => null,
            'order_id' => $itemOrder->id,
            'order_no' => $itemOrder->ORDER_NUMBER,
            'customer_id' => $itemOrder->customer_id,
            'order_date' => $itemOrder->order_date,
            'items' =>
            [
                [
                    'product_id' => $itemOrder->product_id,
                    'product_number' => $itemOrder->product_number,
                    'product_description' => $itemOrder->product_description,
                    'ORDER_BY_CUS' => $itemOrder->ORDER_BY_CUS,
                    'quantity' => $itemOrder->quantity,
                    'product_um' => $itemOrder->product_um,
                    'quantity2' => $itemOrder->quantity2,
                    'product_um2' => $itemOrder->product_um2,
                    'quantity' => $quantity,
                    'quantity_um' => 'Kg',
                    'warehouse' => $itemOrder->warehouse,
                    'work_type' => $itemOrder->item_work_desc,
                ]
            ]
        ];
    }
}
