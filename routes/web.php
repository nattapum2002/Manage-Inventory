<?php

use App\Http\Controllers\ExcelImportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::resource('Users', App\Http\Controllers\UserController::class);

Route::resource('ManageStock', App\Http\Controllers\managestock::class);
//Admin Routes

Route::get('/manageshift', function () {
    return view('Admin.ManageShift.manageshift');
});

// Route::get('/managestock', function () {
//     return view('Admin.ManageStock.managerecivestock');
// });
// Route::get('/manageslip', function () {
//     return view('Admin.ManageStock.manageslipstock');
// })->name('Manage slip');

// Route::get('/manageitem', function () {
//     return view('Admin.ManageStock.manageslipdetail');
// })->name('Manage item');

//Route::get('/ManageStock', function () {
//     return view('Admin.ManageStock.managerecivestock');
// })->name('ManageStock');

Route::get('/uploadExcel', [ExcelImportController::class, 'showUploadForm'])->name('excel.form');
Route::post('/upload-preview', [ExcelImportController::class, 'uploadAndPreview'])->name('excel.preview');
Route::post('/save-data', [ExcelImportController::class, 'saveExcelData'])->name('excel.save');

Route::get('/', [App\Http\Controllers\LoginController::class, 'index'])->name('Login.index');
Route::post('/Login', [App\Http\Controllers\LoginController::class, 'login'])->name('Login');
Route::get('/Logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('Logout');

Route::get('NewItem', function () {
    return view('Admin.Stock.additem');
})->name('NewItem');
Route::get('ShowStat/Dispense/{date}', [App\Http\Controllers\ShowStat::class, 'show_stat_dispense'])->name('Show_dispense_stat');
Route::get('ShowStat/Imported/{date}', [App\Http\Controllers\ShowStat::class, 'show_stat_imported'])->name('Show_imported_stat');
Route::get('ShowStatDate', [App\Http\Controllers\ShowStat::class, 'show_date'])->name('ShowStatDate');
// Route::get('/ShowStock', [App\Http\Controllers\ShowStock::class, 'index'])->name('ShowStock');
Route::get('/ShowStock/Cold-A', [App\Http\Controllers\ShowStock::class, 'stock_coldA'])->name('ShowStockA');
Route::get('/ShowStock/Cold-C', [App\Http\Controllers\ShowStock::class, 'stock_coldC'])->name('ShowStockC');
Route::get('/Admin/ShowStock', [App\Http\Controllers\ShowStock::class, 'Admin_index'])->name('AdminShowStock');
Route::get('/Edit-name/{product_id}', [App\Http\Controllers\ShowStock::class, 'Detail'])->name('Edit name');
Route::post('Updatename', [App\Http\Controllers\ShowStock::class, 'edit_name'])->name('Updatename');
Route::get('/Admin/Dashboard', function () {
    return view('Admin.Dashboard.index');
})->name('Dashboard.Admin');

Route::get('/ManageQueue', function () {
    return view('Admin.ManageQueue.managequeue');
})->name('ManageQueue');

Route::get('/ManageShift', [App\Http\Controllers\ShiftController::class, 'index'])->name('ManageShift');
Route::get('/ManageShift/Toggle/{shift_id}/{status}', [App\Http\Controllers\ShiftController::class, 'Toggle'])->name('ManageShift.Toggle');
Route::get('/ManageShift/EditShift/{shift_id}', [App\Http\Controllers\ShiftController::class, 'EditShift'])->name('EditShift');
Route::post('/ManageShift/EditShift/Save', [App\Http\Controllers\ShiftController::class, 'SaveEditShift'])->name('SaveEditShift');
Route::get('/ManageShift/AddShift', [App\Http\Controllers\ShiftController::class, 'AddShift'])->name('AddShift');
Route::get('/ManageShift/AddShift/AutoCompleteAddShift', [App\Http\Controllers\ShiftController::class, 'AutoCompleteAddShift'])->name('AutoCompleteAddShift');
Route::post('/ManageShift/AddShift', [App\Http\Controllers\ShiftController::class, 'AddShift'])->name('AddShift');
Route::get('/ManageShift/DeleteShift/{shift_id}/{user_id}', [App\Http\Controllers\ShiftController::class, 'DeleteShift'])->name('DeleteShift');

Route::get('/ManageLockStock', [App\Http\Controllers\LockController::class, 'index'])->name('ManageLockStock');
Route::get('/ManageLockStock/Detail/{order_id}', [App\Http\Controllers\LockController::class, 'DetailLockStock'])->name('DetailLockStock');
Route::get('/ManageLockStock/Detail/{order_id}/AddPallet', [App\Http\Controllers\LockController::class, 'AddPallet'])->name('AddPallet');
Route::get('/ManageLockStock/Detail/AddPallet/AutoCompleteAddPallet', [App\Http\Controllers\LockController::class, 'AutoCompleteAddPallet'])->name('AutoCompleteAddPallet');
Route::post('/ManageLockStock/Detail/{order_id}/Save', [App\Http\Controllers\LockController::class, 'SavePallet'])->name('SavePallet');
Route::get('/ManageLockStock/Detail/{order_id}/Pallet/{pallet_id}', [App\Http\Controllers\LockController::class, 'DetailPallets'])->name('DetailPallets');

Route::get('/ProductReceiptPlan', [App\Http\Controllers\ProductReceiptPlanController::class, 'index'])->name('ProductReceiptPlan');
Route::post('/ProductReceiptPlan/Add', [App\Http\Controllers\ProductReceiptPlanController::class, 'AddProductReceiptPlan'])->name('AddProductReceiptPlan');
Route::post('/ProductReceiptPlan/Add/Save', [App\Http\Controllers\ProductReceiptPlanController::class, 'SaveProductReceiptPlan'])->name('SaveProductReceiptPlan');
Route::get('/ProductReceiptPlan/Edit/{product_receipt_plan_id}', [App\Http\Controllers\ProductReceiptPlanController::class, 'EditProductReceiptPlan'])->name('EditProductReceiptPlan');
Route::post('/ProductReceiptPlan/Edit/Save', [App\Http\Controllers\ProductReceiptPlanController::class, 'SaveEditProductReceiptPlan'])->name('SaveEditProductReceiptPlan');

Route::get('AddItem', function () {
    return view('Admin.ManageStock.addstock');
})->name('Add item');

Route::get('/ManageStock', [App\Http\Controllers\managestock::class, 'index'])->name('ManageStock');
Route::get('/ManageSlip/{date}', [App\Http\Controllers\managestock::class, 'show_slip'])->name('ManageSlip');
Route::get('/SlipDetail/{slip_id}', [App\Http\Controllers\managestock::class, 'show_slip_detail'])->name('SlipDetail');
Route::post('/EditSlipDetail', [App\Http\Controllers\managestock::class, 'edit'])->name('EditSlip');
Route::post('/Add-Slip', [App\Http\Controllers\managestock::class, 'create'])->name('AddSlip');
Route::get('/check-slip/{id}', [App\Http\Controllers\managestock::class, 'check_slip'])->name('CheckSlip');
Route::get('autocomplete', [App\Http\Controllers\managestock::class, 'autocomplete'])->name('autocomplete');
//

Route::get('/ManageUsers', [App\Http\Controllers\UserController::class, 'index'])->name('ManageUsers');

Route::get('/ManageUsers/Createuser', function () {
    return view('Admin.ManageUsers.createuser');
})->name('Createuser');

Route::post('/ManageUsers/Createuser', [App\Http\Controllers\UserController::class, 'create'])->name('ManageUsers.Createuser');
Route::get('/ManageUsers/Toggle/{user_id}/{status}', [App\Http\Controllers\UserController::class, 'toggle'])->name('ManageUsers.Toggle');
Route::get('/ManageUsers/Edituser/{user_id}', [App\Http\Controllers\UserController::class, 'edit'])->name('Edituser');

Route::post('/ManageUsers/Edituser/{user_id}', [App\Http\Controllers\UserController::class, 'update'])->name('Edituser.update');

Route::get('/Profile', function () {
    return view('Admin.profile');
})->name('Profile');

//Manager Routes

Route::get('/Manager/Dashboard', [App\Http\Controllers\StatisticsController::class, 'index'])->name('Dashboard.Manager');

Route::get('/Manager/ProductStore', [App\Http\Controllers\StatisticsController::class, 'ProductStore'])->name('ProductStore');
Route::get('/Manager/ProductStore/{slip_id}', [App\Http\Controllers\StatisticsController::class, 'DetailProductStore'])->name('DetailProductStore');

Route::get('/Manager/ProductStock', [App\Http\Controllers\StatisticsController::class, 'ProductStock'])->name('ProductStock');

Route::get('/Manager/CustomerOrder', [App\Http\Controllers\StatisticsController::class, 'CustomerOrder'])->name('CustomerOrder');
Route::get('/Manager/CustomerOrder/{order_id}', [App\Http\Controllers\StatisticsController::class, 'DetailCustomerOrder'])->name('DetailCustomerOrder');

Route::get('/Manager/Pallet', [App\Http\Controllers\StatisticsController::class, 'Pallet'])->name('Pallet');
Route::get('/Manager/Pallet/{pallet_id}', [App\Http\Controllers\StatisticsController::class, 'DetailPallet'])->name('DetailPallet');

Route::get('/Manager/Profile', function () {
    return view('Manager.profile');
})->name('Profile');

//User Routes

Route::get('/User/Dashboard', function () {
    return view('Admin.Dashboard.index');
})->name('Dashboard.User');
