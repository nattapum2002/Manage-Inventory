<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CustomerQueueController extends Controller
{
    public function index(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $CustomerQueues = $this->GetCustomerQueues($request->input('date') ?? now()->format('Y-m-d'));

        // dd($CustomerQueues);

        return view('Admin.ManageQueue.ManageQueue', compact('CustomerQueues'));
    }

    public function ManageQueueFilterDate(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $date = $request->input('date');

        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        $CustomerQueues = $this->GetCustomerQueues($date ?? now()->format('Y-m-d'));
        return response()->json(['CustomerQueues' => $CustomerQueues, 'date' => $date]);
    }

    private function GetCustomerQueues($date)
    {
        return DB::table('master_order_details')
            ->leftJoin('order_details', 'master_order_details.ORDER_NUMBER', '=', 'order_details.ORDER_NUMBER')
            ->select(
                'master_order_details.ORDER_NUMBER',
                'master_order_details.CUSTOMER_ID',
                'order_details.CUSTOMER_NAME',
                'order_details.CUST_GRADE',
                'master_order_details.SCHEDULE_SHIP_DATE',
                'order_details.TIME_QUE',
            )
            ->whereDate('master_order_details.SCHEDULE_SHIP_DATE', $date)
            // ->where('order_details.TIME_QUE', '!=', null)
            ->where('order_details.CUSTOMER_NAME', '!=', null)
            ->orderBy('master_order_details.SCHEDULE_SHIP_DATE')
            ->orderBy('order_details.TIME_QUE')
            ->distinct()
            ->get();
    }

    public function AddCustomerQueue(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        // Store the uploaded file temporarily
        $path = $request->file('file')->store('temp');
        $filePath = storage_path('app/' . $path);

        try {
            // Read data from the Excel file
            $data = $this->readExcelFile($filePath);

            $detailHeader = $data[1] ?? [];
            $rows = array_slice($data, 2);

            // Sort rows by queue_time (Column 3)
            usort($rows, function ($a, $b) {
                try {
                    $timeA = Carbon::createFromFormat('H:i:s', $a[2]);
                    $timeB = Carbon::createFromFormat('H:i:s', $b[2]);
                    return $timeA->getTimestamp() <=> $timeB->getTimestamp();
                } catch (\Exception $e) {
                    return 0;
                }
            });

            // Format rows: add index, convert date/time to Thai format
            $rows = array_map(function ($row, $index) {
                array_unshift($row, $index + 1); // Add index (start from 1)

                try {
                    $row[5] = Carbon::createFromFormat('m/d/Y', $row[5])->format('d/m/Y'); // Format date
                    $row[4] = Carbon::createFromFormat('h:i:s A', $row[4])->format('H:i:s'); // Format time
                } catch (\Exception $e) {
                    // Skip conversion if format is invalid
                }

                return $row;
            }, $rows, array_keys($rows));

            // Fetch Customer data
            $customers = DB::table('customer')->get();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'Error reading Excel file: ' . $e->getMessage()]);
        }

        return view('Admin.ManageQueue.AddCustomerQueue', [
            'filePath' => $path,
            'detailHeader' => $detailHeader,
            'rows' => $rows,
            'customers' => $customers,
        ]);
    }

    public function SaveAddCustomerQueue(Request $request)
    {
        // Validate the filePath input

        $request->validate([
            'filePath' => 'required|string',
        ]);

        $filePath = $request->input('filePath');
        $fullFilePath = Storage::path($filePath);

        if (Storage::exists($filePath)) {
            DB::beginTransaction();
            try {
                // Read data from the file
                $data = $this->readExcelFile($fullFilePath);
                $rows = array_slice($data, 2);

                // Sort rows by queue_time (Column 3)
                usort($rows, function ($a, $b) {
                    try {
                        $timeA = Carbon::createFromFormat('H:i:s', $a[2]);
                        $timeB = Carbon::createFromFormat('H:i:s', $b[2]);
                        return $timeA->getTimestamp() <=> $timeB->getTimestamp();
                    } catch (\Exception $e) {
                        return 0;
                    }
                });

                // Format rows: add index, convert date/time to Thai format
                $rows = array_map(function ($row, $index) {
                    array_unshift($row, $index + 1); // Add index (start from 1)
                    try {
                        $row[5] = Carbon::createFromFormat('m/d/Y', $row[5])->subYears(543)->format('Y-m-d'); // Format date
                        $row[4] = Carbon::createFromFormat('h:i:s A', $row[4])->format('H:i:s'); // Format time
                    } catch (\Exception $e) {
                        // Skip conversion if format is invalid
                    }
                    return $row;
                }, $rows, array_keys($rows));

                // Prepare data for database insertion
                $rowsToInsert = [];
                foreach ($rows as $row) {
                    $rowsToInsert[] = [
                        'queue_no' => $row[0],
                        'order_number' => $row[1],
                        'queue_time' => $row[4],
                        'queue_date' => $row[5],
                        'note' => $row[6] ?? 'N/A',
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Insert data into the database
                DB::table('customer_queue')->insert($rowsToInsert);

                // Delete the file after successful processing
                Storage::delete($filePath);

                DB::commit();

                return redirect()->route('ManageQueue')->with('success', 'Customer queues added successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('ManageQueue')->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }

        return redirect()->route('ManageQueue')->with('error', 'File not found.');
    }

    private function readExcelFile(string $filePath): array
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            return $sheet->toArray();
        } catch (\Exception $e) {
            throw new \Exception('Error reading Excel file: ' . $e->getMessage());
        }
    }

    public function DetailCustomerQueue($order_number)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $customer_queue = DB::table('master_order_details')
            ->leftJoin('order_details', 'master_order_details.ORDER_NUMBER', '=', 'order_details.ORDER_NUMBER')
            ->where('master_order_details.ORDER_NUMBER', '=', $order_number)
            ->select(
                'master_order_details.ORDER_NUMBER',
                'master_order_details.CUSTOMER_ID',
                'order_details.CUSTOMER_NAME',
                'order_details.CUST_GRADE',
                'master_order_details.SCHEDULE_SHIP_DATE',
                'order_details.TIME_QUE',
                'order_details.TIME_EXIT',
                'order_details.ITEM_ID',
                'order_details.ITEM_DESC1',
                'order_details.ORDERED_QUANTITY',
                'order_details.ORDER_BY_CUS',
                'order_details.QUANTITY_UOM',
            )
            ->get()
            ->groupBy('ORDER_NUMBER')
            ->map(function ($groupOrder) {
                $firstOrder = $groupOrder->first();
                return [
                    'ORDER_NUMBER' => $firstOrder->ORDER_NUMBER,
                    'CUSTOMER_ID' => $firstOrder->CUSTOMER_ID,
                    'CUSTOMER_NAME' => $firstOrder->CUSTOMER_NAME,
                    'CUST_GRADE' => $firstOrder->CUST_GRADE,
                    'SCHEDULE_SHIP_DATE' => $firstOrder->SCHEDULE_SHIP_DATE,
                    'TIME_QUE' => $firstOrder->TIME_QUE,
                    'TIME_EXIT' => $firstOrder->TIME_EXIT,
                    'ITEMS' => $groupOrder->groupBy('ITEM_ID')->map(function ($groupItem) {
                        $firstItem = $groupItem->first();
                        return [
                            'ITEM_ID' => $firstItem->ITEM_ID,
                            'ITEM_DESC1' => $firstItem->ITEM_DESC1,
                            'ORDERED_QUANTITY' => $groupItem->sum('ORDERED_QUANTITY'),
                            'ORDER_BY_CUS' => $firstItem->ORDER_BY_CUS,
                            'QUANTITY_UOM' => $firstItem->QUANTITY_UOM,
                        ];
                    })->filter(),
                ];
            })->first();

        return view('Admin.ManageQueue.DetailCustomerQueue', compact('customer_queue'));
    }

    public function PalletDetail($pallet_id, $order_id)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        $Pallets = DB::table('pallet_order')
            ->select(
                'customer.customer_name',
                'pallet.id',
                'pallet.pallet_no',
                'pallet.room',
                'pallet_type.pallet_type',
                'pallet.status',
                'pallet.recive_status',
                'product.item_desc1',
                'product.item_no',
                'product.item_um',
                'product.item_um2',
                'confirmOrder.quantity',
                'confirmOrder.quantity2',
            )
            ->join('product', 'pallet_order.product_id', '=', 'product.item_id')
            ->join('pallet', 'pallet_order.pallet_id', '=', 'pallet.id')
            ->join('pallet_type', 'pallet.pallet_type_id', '=', 'pallet_type.id')
            ->join('confirmOrder', 'pallet_order.id', '=', 'confirmOrder.pallet_order_id')
            ->join('customer_order', 'confirmOrder.order_id', '=', 'customer_order.order_number')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->leftJoin('customer_order_detail', function ($join) use ($order_id) {
                $join->on('pallet_order.product_id', '=', 'customer_order_detail.product_id')
                    ->where('customer_order_detail.order_number', '=', $order_id);
            })
            ->where('pallet_order.pallet_id', '=', $pallet_id)
            ->get();
        // dd($Pallets);
        return view('Admin.ManageQueue.QueuePalletDetail', compact('Pallets', 'order_id', 'pallet_id'));
    }

    public function confirmReceive($order_id, $pallet_id)
    {
        // Ensure the user is authenticated
        if (!Auth::user()) {
            return redirect()->route('Login.index');
        }

        DB::table('pallet')->where('id', $pallet_id)->update(['recive_status' => 1]);

        return redirect()->route('DetailCustomerQueue', $order_id);
    }
}
