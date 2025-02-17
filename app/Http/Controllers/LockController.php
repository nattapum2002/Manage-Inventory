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
            'orders.order_date', // ✅ รวมข้อมูลตามวัน
            'orders.customer_id',
            'customer.customer_name',
            'customer.customer_grade',
            DB::raw('COUNT(DISTINCT orders.order_number) AS total_orders'),
            DB::raw('COUNT(CASE WHEN orders.confirm_order_status = 1 THEN 1 END) AS complete_order'),
            DB::raw('COUNT(CASE WHEN orders.confirm_order_status = 0 THEN 1 END) AS not_complete_order')
        )
        ->groupBy(
            'orders.order_date', // ✅ Group ตามวัน
            'orders.customer_id',
            'customer.customer_name',
            'customer.customer_grade'
        )
        ->orderBy('orders.order_date') // ✅ เรียงตามวัน
        ->get();



        //dd($CustomerOrders);
        return view('Admin.ManageLockStock.managelockstock', compact('CustomerOrders'));
    }

    public function DetailLockStock($CUS_ID, $ORDER_DATE)
    {
        $orders = $this->getCustomerOrder($CUS_ID , $ORDER_DATE);
        $palletOrderId = $this->getPalletOrderId($CUS_ID,$ORDER_DATE);
        //dd($palletOrderId);
        $customer_name = DB::table('customer')
            ->select('customer_name')
            ->where('customer_id',$CUS_ID)->value('customer_name');

        $formatOrders = $orders->groupBy('order_number')
            ->map(function($data,$order_number){
                return [
                    'order_date' => $data->first()->order_date,
                    'order_number' => $order_number,
                    'ship_status' => $data->first()->confirm_order_status,
                    'order_ship_release' => [
                        'ship_datetime' => $data->first()->ship_datetime,
                        'entry_datetime' => $data->first()->entry_datetime,
                        'release_datetime' => $data->first()->release_datetime
                    ],
                    'order_detail' => $data->map(function($item){
                        return [
                            'product_number' => $item->product_number ,
                            'product_name' => $item->product_description,
                            'quantity1' => $item->quantity,
                            'quantity2' => $item->quantity2,
                        ];
                    })->values()
                ];
            })->values();

            $pallets = DB::table('pallet')
                ->join('orders','orders.order_number','=','pallet.order_number')
                ->join('pallet_type','pallet_type.id','=','pallet.pallet_type_id')
                ->leftJoin('pallet_team', 'pallet_team.pallet_id', '=', 'pallet.id')
                ->leftJoin('team', 'pallet_team.team_id', '=', 'team.team_id')
                ->whereDate('orders.order_date',$ORDER_DATE)
                ->where('orders.customer_id',$CUS_ID)
                ->select('pallet.*','pallet_type.pallet_type','team.team_name')
                ->get();
        
        

        return view('Admin.ManageLockStock.DetailLockStock', compact('formatOrders', 'customer_name', 'CUS_ID', 'ORDER_DATE','pallets','palletOrderId'));
    }

    public function updatePalletStatus(Request $request){
        $palletId = $request->query('palletId');
        $query = $request->query('query');

        if($query == 1){
            $this->updateArrangePallet($palletId);
        }else if($query == 2){
            $this->updateSendPallet($palletId);
        }
        return response()->json(['success',$palletId ,$query]);
    }

    private function updateArrangePallet($palletId){
        DB::table('pallet')->where('id',$palletId)
        ->update([
            'arrange_pallet_status' => DB::raw("
                CASE 
                    WHEN arrange_pallet_status = 1 THEN 0 
                    ELSE 1 
                END
            ")
        ]);
    }
    private function updateSendPallet($palletId){
        DB::table('pallet')->where('id',$palletId)
        ->update([
            'recipe_status' => DB::raw("
                CASE 
                    WHEN recipe_status = 1 THEN 0 
                    ELSE 1 
                END
            ")
        ]);
    }

    public function updateOrderConfirmStatus(Request $request){
        $orderNumber = $request->query('orderNumber');
        try {
            DB::table('orders')->where('order_number',$orderNumber)
                ->update([
                    'confirm_order_status' => true ,
                    //'confirm_at' => now(),
                ]);
        } catch (\Throwable $th) {
            throw $th;
        }
        return response()->json(['success']);
    }
    public function getCustomerOrder($CUS_ID,$ORDER_DATE){
        $customerOrder = DB::table('orders')
            ->join('order_detail','order_detail.order_number','=','orders.order_number')
            ->join('product','product.product_number','=','order_detail.product_number')
            ->where('orders.customer_id',$CUS_ID)
            ->whereDate('orders.order_date',$ORDER_DATE)
            ->get();

        return $customerOrder ;
    }

    public function getPalletOrderId($CUS_ID , $ORDER_DATE){
        $palletOrderId = DB::table('pallet')
            ->join('orders','orders.order_number','=','pallet.order_number')
            ->select('pallet.order_number')
            ->whereDate('orders.order_date',$ORDER_DATE)
            ->where('orders.customer_id',$CUS_ID)
            ->groupBy('pallet.order_number')
            ->pluck('order_number');
        
            return $palletOrderId;
    }
    public function updatePalletType(Request $request ,$pallet_id ){
        $data = $request->input();
        try {
            //code...
            DB::table('pallet')->where('id',$pallet_id)->update([
                'pallet_type_id' => $data['pallet_type_id'],
            ]);
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    

    public function AddPallet($order_id)
    {
        $pallet_type = DB::table('pallet_type')->get();
        return view('Admin.ManageLockStock.AddPallet', compact('pallet_type'));
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
            ->join('pallet_detail', 'pallet.id', '=', 'pallet_detail.pallet_id')
            ->join('product', 'pallet_detail.product_id', '=', 'product.product_id')
            ->join('product_work_desc', 'product.product_work_desc_id', '=', 'product_work_desc.id')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->leftJoin('warehouse', 'product.warehouse_id', '=', 'warehouse.id')
            ->leftJoin('pallet_team', 'pallet.pallet_id', '=', 'pallet_team.pallet_id')
            ->leftJoin('team', 'pallet_team.team_id', '=', 'team.team_id')
            ->select(
                'pallet.id as pallet_pr_id',
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
            ->where('pallet.id', $pallet_id)
            ->get();

            $pallet_type = DB::table('pallet_type')->get();

        return view('Admin.ManageLockStock.DetailPellets', compact('Pallets', 'ORDER_DATE', 'CUS_ID','pallet_type'));
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

    


    //คุณธีรพล พูลเพิ่ม test
   
}
