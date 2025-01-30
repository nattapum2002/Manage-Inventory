<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetDataController extends Controller
{
    private function getWarehouse()
    {
        $warehouse = DB::table('warehouse')->get();

        return $warehouse;
    }

    private function getProductWorkDesc()
    {
        $productWorkDesc = DB::table('product_work_desc')->get();

        return $productWorkDesc;
    }

    private function getPalletType()
    {
        $palletType = DB::table('pallet_type')->get();

        return $palletType;
    }

    public function index()
    {
        $warehouse = $this->getWarehouse();
        $productWorkDesc = $this->getProductWorkDesc();
        $palletType = $this->getPalletType();
        return view('Admin.SetData.SetData', compact('warehouse', 'productWorkDesc', 'palletType'));
    }

    public function getSetData()
    {
        $warehouse = $this->getWarehouse();
        $productWorkDesc = $this->getProductWorkDesc();
        $palletType = $this->getPalletType();

        return response()->json([
            'warehouse' => $warehouse,
            'productWorkDesc' => $productWorkDesc,
            'palletType' => $palletType
        ]);
    }

    public function SaveUpdateSetData(Request $request)
    {
        try {
            $response = [];
            DB::transaction(function () use ($request) {
                if ($request->input('warehouse_id') && $request->input('warehouse_name')) {
                    DB::table('warehouse')
                        ->where('id', $request->input('warehouse_id'))
                        ->update(['warehouse_name' => $request->input('warehouse_name')]);

                    $response = [
                        'status' => 'success',
                        'warehouse_id' => $request->input('warehouse_id'),
                        'warehouse_name' => $request->input('warehouse_name')
                    ];
                }

                if ($request->input('product_work_desc_id') && $request->input('product_work_desc_name')) {
                    DB::table('product_work_desc')
                        ->where('id', $request->input('product_work_desc_id'))
                        ->update(['product_work_desc' => $request->input('product_work_desc_name')]);

                    $response = [
                        'status' => 'success',
                        'product_work_desc_id' => $request->input('product_work_desc_id'),
                        'product_work_desc' => $request->input('product_work_desc_name')
                    ];
                }

                if ($request->input('pallet_type_id') && $request->input('pallet_type_name')) {
                    DB::table('pallet_type')
                        ->where('id', $request->input('pallet_type_id'))
                        ->update(['pallet_type' => $request->input('pallet_type_name')]);

                    $response = [
                        'status' => 'success',
                        'pallet_type_id' => $request->input('pallet_type_id'),
                        'pallet_type' => $request->input('pallet_type_name')
                    ];
                }
            });

            return response()->json($response ?: ['status' => 'error', 'message' => 'No data updated'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function SaveAddSetData(Request $request)
    {
        try {
            $response = [];

            DB::transaction(function () use ($request, &$response) {  // ใช้ & เพื่อให้ response เปลี่ยนแปลงได้
                if ($request->input('warehouse_name')) {
                    $id = DB::table('warehouse')->insertGetId([
                        'warehouse_name' => $request->input('warehouse_name')
                    ]);

                    $response = [
                        'status' => 'success',
                        'warehouse_id' => $id,
                        'warehouse_name' => $request->input('warehouse_name')
                    ];
                }

                if ($request->input('product_work_desc_name')) {
                    $id = DB::table('product_work_desc')->insertGetId([
                        'product_work_desc' => $request->input('product_work_desc_name')
                    ]);

                    $response = [
                        'status' => 'success',
                        'product_work_desc_id' => $id,
                        'product_work_desc' => $request->input('product_work_desc_name')
                    ];
                }

                if ($request->input('pallet_type_name')) {
                    $id = DB::table('pallet_type')->insertGetId([
                        'pallet_type' => $request->input('pallet_type_name')
                    ]);

                    $response = [
                        'status' => 'success',
                        'pallet_type_id' => $id,
                        'pallet_type' => $request->input('pallet_type_name')
                    ];
                }
            });

            return response()->json($response ?: ['status' => 'error', 'message' => 'No data inserted'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function DeleteSetData(Request $request)
    {
        try {
            $response = [];

            DB::transaction(function () use ($request) {
                if ($request->input('warehouse_id')) {
                    DB::table('warehouse')->where('id', $request->input('warehouse_id'))->delete();

                    $response = [
                        'status' => 'success'
                    ];
                }

                if ($request->input('product_work_desc_id')) {
                    DB::table('product_work_desc')->where('id', $request->input('product_work_desc_id'))->delete();

                    $response = [
                        'status' => 'success'
                    ];
                }

                if ($request->input('pallet_type_id')) {
                    DB::table('pallet_type')->where('id', $request->input('pallet_type_id'))->delete();

                    $response = [
                        'status' => 'success'
                    ];
                }
            });

            return response()->json($response ?: ['status' => 'error', 'message' => 'No data deleted'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
