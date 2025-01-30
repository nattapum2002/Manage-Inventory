<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowStock extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function GetProducts($product_id = null)
    {
        $query = DB::table('product')
            ->leftJoin('warehouse', 'product.warehouse_id', '=', 'warehouse.id')
            ->leftJoin('product_work_desc', 'product.product_work_desc_id', '=', 'product_work_desc.id')
            ->select(
                'product_id',
                'product_number',
                'product_description',
                'product_um',
                'product_um2',
                'warehouse.id as warehouse_id',
                'warehouse_name as warehouse',
                'product_work_desc.id as product_work_desc_id',
                'product_work_desc.product_work_desc',
                'note',
                'status'
            );

        if ($product_id) {
            $query->where('product_number', $product_id);
        }

        return $product_id ? $query->first() : $query->get();
    }

    private function GetProductStock($Warehouse, $showAll = false)
    {
        return DB::table('product_stock')
            ->join('product', 'product.product_id', '=', 'product_stock.product_id')
            ->leftJoin('warehouse', 'product.warehouse_id', '=', 'warehouse.id')
            ->leftJoin('product_work_desc', 'product.product_work_desc_id', '=', 'product_work_desc.id')
            ->select(
                'product.product_id',
                'product.product_number',
                'product.product_description',
                'product_stock.quantity',
                'product.product_um',
                'product_stock.quantity2',
                'product.product_um2',
                'warehouse.warehouse_name as warehouse',
                'product_work_desc.id as product_work_desc_id',
                'product_work_desc.product_work_desc',
                'product.note',
                'product.status'
            )
            ->when($Warehouse !== 'All', function ($query) use ($Warehouse) {
                $query->where('warehouse.warehouse_name', $Warehouse);
            })
            ->when($showAll == false, function ($query) {
                $query->whereNotNull('warehouse.warehouse_name');
            })
            ->get();
    }

    private function GetWarehouse()
    {
        $warehouse = DB::table('warehouse')->get();

        return $warehouse;
    }

    private function GetProductWorkDesc()
    {
        $productWorkDesc = DB::table('product_work_desc')->get();

        return $productWorkDesc;
    }

    public function index()
    {
        $data = $this->GetProductStock('All');
        return view('showstock', compact('data'));
    }

    public function StockFilter(Request $request)
    {
        $ProductStock = $this->GetProductStock($request->input('warehouse'), $request->input('ShowAll'));

        return response()->json([
            'status' => 'success',
            'ProductStock' => $ProductStock,
        ]);
    }

    public function Admin_index()
    {
        $data = $this->GetProductStock('All');
        $warehouses = $this->GetWarehouse();

        return view('Admin.Stock.showstock', compact('data', 'warehouses'));
    }

    public function SyncProduct()
    {
        try {
            DB::statement('EXEC dbo.Add_product');

            return redirect()->route('AdminShowStock')->with('success', 'เพิ่มข้อมูลสินค้าเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'เกิดข้อผิดพลาดขณะบันทึกข้อมูล : ' . $e->getMessage()]);
        }
    }

    public function ProductDetail($product_id)
    {
        $data = $this->GetProducts($product_id);
        $warehouse = $this->GetWarehouse();
        $product_work_desc = $this->GetProductWorkDesc();

        return view('Admin.Stock.edititem', compact('data', 'warehouse', 'product_work_desc'));
    }

    public function SaveEditProduct(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|string|max:255',
            'product_name' => 'nullable|string|max:255',
            'room' => 'required|string|max:255',
            'product_work_desc' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                DB::table('product')
                    ->where('product_number', $validated['product_id'])
                    ->update([
                        'product_description' => $validated['product_name'],
                        'warehouse_id' => $validated['room'],
                        'product_work_desc_id' => $validated['product_work_desc'],
                    ]);
            });

            return redirect()
                ->route("Edit name", ['product_id' => $validated['product_id']])
                ->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()
                ->route("Edit name", ['product_id' => $validated['product_id']])
                ->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}
