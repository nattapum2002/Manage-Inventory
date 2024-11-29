<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CustomerQueueController extends Controller
{
    public function index(Request $request)
    {
        $CustomerQueues = $this->GetCustomerQueues($request->input('date') ?? now()->format('Y-m-d'));
        return view('Admin.ManageQueue.ManageQueue', compact('CustomerQueues'));
    }

    public function ManageQueueFilterDate(Request $request)
    {
        $CustomerQueues = $this->GetCustomerQueues($request->input('date') ?? now()->format('Y-m-d'));
        return view('Admin.ManageQueue.ManageQueue', compact('CustomerQueues'));
    }

    private function GetCustomerQueues($date)
    {
        return DB::table('customer_queue')
            ->join('customer_order', 'customer_queue.order_number', '=', 'customer_order.order_number')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->select(
                'customer_queue.queue_time',
                'customer_queue.order_number',
                'customer_queue.note',
                'customer_queue.status',
                'customer.customer_name',
                'customer_queue.queue_no',
                'customer_queue.queue_date',
                'customer.customer_grade'
            )
            ->whereDate('customer_queue.queue_date', $date)
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
        $customer_queue = DB::table('customer_queue')
            ->join('customer_order', 'customer_queue.order_number', '=', 'customer_order.order_number')
            ->join('customer', 'customer_order.customer_id', '=', 'customer.customer_id')
            ->where('customer_queue.order_number', '=', $order_number)
            ->first();

        return view('Admin.ManageQueue.DetailCustomerQueue', compact('customer_queue'));
    }
}


//     +"id": "1113"
//     +"order_number": "1141100526.0"
//     +"queue_no": "7"
//     +"queue_time": "12:00:00.0000000"
//     +"queue_date": "2024-11-27"
//     +"entry_time": null
//     +"entry_date": null
//     +"release_time": null
//     +"release_date": null
//     +"note": null
//     +"status": "1"
//     +"created_at": null
//     +"updated_at": null
//     +"order_id": "81"
//     +"customer_id": "48999"
//     +"team_id": null
//     +"customer_number": "EXP-160010"
//     +"customer_name": "OKAYA AND CO., LTD"
//     +"customer_grade": null
