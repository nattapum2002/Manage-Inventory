<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $CustomerOrders = DB::table('customer_order')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            // ->join('lock_team', 'customer_order.team_id', '=', 'lock_team.team_id')
            ->get();
        // dd($CustomerOrders);
        return view('Admin.ManageLockStock.managelockstock', compact('CustomerOrders'));
    }

    public function DetailLockStock($order_id)
    {
        $CustomerOrders = DB::table('customer_order')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->join('customer_order_detail', 'customer_order.order_number', '=', 'customer_order_detail.order_number')
            ->join('product', 'customer_order_detail.product_id', '=', 'product.item_id')
            ->where('customer_order_detail.order_number', '=', $order_id)
            ->get();
        // dd($CustomerOrders);
        $LockTeams = DB::table('lock_team')
            ->join('lock_team_user', 'lock_team.team_id', '=', 'lock_team_user.team_id')
            ->join('users', 'lock_team_user.user_id', '=', 'users.user_id')
            ->get();
        $Pallets = DB::table('pallet')
            ->where('order_id', '=', $order_id)
            ->selectRaw('pallet.id,MAX(pallet_type.pallet_type) as pallet_type ,MAX(pallet.pallet_id) as pallet_id ,MAX(pallet_no) as pallet_no, MAX(room) as room, pallet.status ,pallet.recive_status ,MAX(pallet.note) as note, MAX(lock_team.team_name) as team_name')
            ->join('pallet_order', 'pallet.id', '=', 'pallet_order.pallet_id')
            ->join('product', 'pallet_order.product_id', '=', 'product.item_id')
            ->join('lock_team', 'pallet.team_id', '=', 'lock_team.team_id')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->groupBy('pallet.id', 'pallet.status', 'pallet.recive_status')
            ->get();
        // dd($Pallets);
        return view('Admin.ManageLockStock.DetailLockStock', compact('CustomerOrders', 'LockTeams', 'Pallets', 'order_id'));
    }

    public function AddPallet($order_number)
    {
        // $Pallets = DB::table('customer_order')
        //     ->join('pallet', 'customer_order.order_number', '=', 'customer_order.order_number')
        //     ->join('pallet_order', 'pallet.pallet_id', '=', 'pallet_order.pallet_id')
        //     ->join('customer_order_detail', 'pallet_order.product_id', '=', 'customer_order_detail.product_id')
        //     ->where('customer_order.order_number', '=', $order_number)
        //     ->get();
        $pallet_type = DB::table('pallet_type')->get();
        return view('Admin.ManageLockStock.AddPallet', compact('order_number', 'pallet_type'));
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

    function autoArrange()
    {
        $CustomerOrders = DB::table('customer_order_detail')
            ->join('customer_order', 'customer_order.order_number', '=', 'customer_order_detail.order_number')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->join('product', 'customer_order_detail.product_id', '=', 'product.item_id')
            ->where('customer_order_detail.order_number', '=', 1141248322.0)
            ->get();

        // dd($CustomerOrders);
        $this->splitOrder($CustomerOrders);
        return view('auto-arrange-lock', compact('CustomerOrders'));
    }

    public function splitOrder($Order)
    {
        $lock_items = []; // สำหรับจัดกลุ่มสินค้า (ล็อก)
        $split_items = []; // สำหรับสินค้าที่ถูกกระจายออก
        $current_group = []; // กลุ่มสินค้าที่กำลังสร้าง
        $current_weight = 0; // น้ำหนักสะสมของกลุ่ม

        foreach ($Order as $item) {
            if ($item->order_quantity_UM === 'Kg') {
                $this->processItem($item, $item->order_quantity, $lock_items, $split_items, $current_group, $current_weight);
            } elseif ($item->order_quantity_UM2 === 'Kg') {
                $this->processItem($item, $item->order_quantity2, $lock_items, $split_items, $current_group, $current_weight);
            }
        }

        // บันทึกกลุ่มสุดท้ายถ้ามีสินค้าเหลือ
        if (!empty($current_group)) {
            $lock_items[] = $current_group;
        }

        // แสดงผล
        dd($lock_items, $split_items);
    }

    /**
     * ประมวลผลสินค้าเพื่อจัดกลุ่มหรือกระจาย
     */
    private function processItem($item, $quantity, &$lock_items, &$split_items, &$current_group, &$current_weight)
    {
        if ($quantity <= 850) {
            $this->SmallItem($item, $quantity, $lock_items, $current_group, $current_weight);
        } else {
            $this->LargeItem($item, $quantity, $lock_items[]);
        }
    }

    /**
     * เพิ่มสินค้าในกลุ่มล็อก
     */
    private function SmallItem($item, $quantity, &$lock_items, &$current_group, &$current_weight)
    {
        // ตรวจสอบว่าสินค้าเพิ่มแล้วน้ำหนักเกินหรือไม่
        if ($current_weight + $quantity > 850) {
            $lock_items[] = $current_group; // บันทึกกลุ่มก่อนหน้า
            $current_group = [];           // เริ่มกลุ่มใหม่
            $current_weight = 0;           // รีเซ็ตน้ำหนักสะสม
        }

        // เพิ่มสินค้าในกลุ่ม
        $current_group[] = [
            'item_no' => $item->item_no,
            'item_desc1' => $item->item_desc1,
            'order_quantity' => $quantity,
        ];
        $current_weight += $quantity;
    }

    /**
     * กระจายสินค้าที่น้ำหนัก > 850
     */
    private function LargeItem($item, $quantity, &$lock_items)
    {
        $remaining_quantity = $quantity;

        while ($remaining_quantity > 850) {
            $lock_items[] = [
                'item_no' => $item->item_no,
                'item_desc1' => $item->item_desc1,
                'order_quantity' => 850,
            ];
            $remaining_quantity -= 850; // ลดน้ำหนักที่เหลือ
        }

        // เพิ่มส่วนที่เหลือ (ถ้ามี)
        if ($remaining_quantity > 0) {
            $lock_items[] = [
                'item_no' => $item->item_no,
                'item_desc1' => $item->item_desc1,
                'order_quantity' => $remaining_quantity,
            ];
        }
    }
}
