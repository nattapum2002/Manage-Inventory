<?php

use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\LockController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManageQueueController;
use App\Http\Controllers\ManageStock;
use App\Http\Controllers\ProductReceiptPlanController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ShowStat;
use App\Http\Controllers\ShowStock;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserWorkController;
use App\Http\Controllers\SlipController;
use App\Http\Controllers\CustomerQueueController;
use App\Http\Controllers\PayGoodsController;
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

Route::resource('ManageStock', App\Http\Controllers\ManageImportProduct::class);
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

Route::get('/', [LoginController::class, 'index'])->name('Login.index');
Route::post('/Login', [LoginController::class, 'login'])->name('Login');
Route::get('/Logout', [LoginController::class, 'logout'])->name('Logout');

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

Route::prefix('ManageQueue')->group(function () {
    Route::get('/', [CustomerQueueController::class, 'index'])->name('ManageQueue');
    Route::post('/FilterDate', [CustomerQueueController::class, 'ManageQueueFilterDate'])->name('ManageQueueFilterDate');
    Route::post('/Add', [CustomerQueueController::class, 'AddCustomerQueue'])->name('AddCustomerQueue');
    Route::post('/Add/Save', [CustomerQueueController::class, 'SaveAddCustomerQueue'])->name('SaveAddCustomerQueue');
    Route::get('/Detail/{order_number}', [CustomerQueueController::class, 'DetailCustomerQueue'])->name('DetailCustomerQueue');

    Route::get('/QueuePallet/Detail/{pallet_id}/{order_id}', [CustomerQueueController::class, 'PalletDetail'])->name('QueuePalletDetail');
    Route::get('/ConfirmReceive/{order_id}/{pallet_id}', [CustomerQueueController::class, 'confirmReceive'])->name('ConfirmReceive');
});

Route::prefix('ManageShift')->group(function () {
    Route::get('/', [ShiftController::class, 'index'])->name('ManageShift');
    Route::get('/Toggle/{shift_id}/{status}', [ShiftController::class, 'Toggle'])->name('ManageShift.Toggle');
    Route::get('/EditShift/{shift_id}', [ShiftController::class, 'EditShift'])->name('EditShift');
    Route::post('/EditShift/Save', [ShiftController::class, 'SaveEditShift'])->name('SaveEditShift');
    Route::get('/AddShift', [ShiftController::class, 'AddShift'])->name('AddShift');
    Route::get('/AddShift/AutoCompleteAddShift', [ShiftController::class, 'AutoCompleteAddShift'])->name('AutoCompleteAddShift');
    Route::post('/AddShift', [ShiftController::class, 'SaveAddShift'])->name('SaveAddShift');
    Route::get('/DeleteShift/{shift_id}/{user_id}', [ShiftController::class, 'DeleteShift'])->name('DeleteShift');
});

Route::prefix('ManageTeam')->group(function () {
    Route::get('/', [TeamController::class, 'index'])->name('ManageTeam');
    Route::get('/Toggle/{team_id}/{status}', [TeamController::class, 'Toggle'])->name('ManageTeam.Toggle');
    Route::get('/EditTeam/{team_id}', [TeamController::class, 'EditTeam'])->name('EditTeam');
    Route::post('/EditTeam/Save', [TeamController::class, 'SaveEditTeam'])->name('SaveEditTeam');
    Route::get('/AddTeam', [TeamController::class, 'AddTeam'])->name('AddTeam');
    Route::get('/AddTeam/AutoCompleteAddTeam', [TeamController::class, 'AutoCompleteAddTeam'])->name('AutoCompleteAddTeam');
    Route::post('/AddTeam', [TeamController::class, 'SaveAddTeam'])->name('SaveAddTeam');
    Route::get('/DeleteTeam/{team_id}/{user_id}', [TeamController::class, 'DeleteTeam'])->name('DeleteTeam');
    Route::get('/AutocompleteSearchTeam', [TeamController::class, 'AutocompleteSearchTeam'])->name('AutocompleteSearchTeam');
});

Route::prefix('ManageLockStock')->group(function () {
    Route::get('/', [LockController::class, 'index'])->name('ManageLockStock');
    Route::get('/{order_number}', [LockController::class, 'DetailLockStock'])->name('DetailLockStock');
    Route::get('/Detail/{order_number}/AddPallet', [LockController::class, 'AddPallet'])->name('AddPallet');
    Route::get('/Detail/AddPallet/AutoCompleteAddPallet/{order_number}', [LockController::class, 'AutoCompleteAddPallet'])->name('AutoCompleteAddPallet');
    Route::post('/Detail/{order_number}/Save', [LockController::class, 'SavePallet'])->name('SavePallet');
    Route::get('/Detail/{order_number}/Pallet/{pallet_id}', [LockController::class, 'DetailPallets'])->name('DetailPallets');
    Route::get('/Detail/forgetSession', [LockController::class, 'forgetSession'])->name('forgetSession');
    Route::get('/Detail/removePallet/{key}', [LockController::class, 'Remove_Pallet'])->name('Remove_Pallet');
    Route::get('/Detail/insertPallet/{order_number}', [LockController::class, 'insert_pallet'])->name('Insert_Pallet');
    Route::get('/Detail/editPalletOrde/{order_id}/{product_id}', [LockController::class, 'EditPalletOrder'])->name('EditPalletOrder');
});

Route::prefix('ProductReceiptPlan')->group(function () {
    Route::get('/', [ProductReceiptPlanController::class, 'index'])->name('ProductReceiptPlan');
    Route::post('/Add', [ProductReceiptPlanController::class, 'AddProductReceiptPlan'])->name('AddProductReceiptPlan');
    Route::post('/Add/Save', [ProductReceiptPlanController::class, 'SaveAddProductReceiptPlan'])->name('SaveAddProductReceiptPlan');
    Route::get('/Edit/{product_receipt_plan_id}', [ProductReceiptPlanController::class, 'EditProductReceiptPlan'])->name('EditProductReceiptPlan');
    Route::post('/Edit/AddProduct', [ProductReceiptPlanController::class, 'AddProduct'])->name('AddProduct');
    Route::post('/Edit/SaveEditDetail', [ProductReceiptPlanController::class, 'SaveEditDetail'])->name('SaveEditDetail');
    Route::post('/Edit/SaveEditProduct', [ProductReceiptPlanController::class, 'SaveEditProduct'])->name('SaveEditProduct');
    Route::get('/AutocompleteProduct', [ProductReceiptPlanController::class, 'AutocompleteProduct'])->name('AutocompleteProduct');
});

Route::prefix('slip')->group(function () {
    Route::get('/AutoCompleteSlip', [SlipController::class, 'AutoCompleteSlip'])->name('AutoCompleteSlip');
    Route::get('/TransferSlip', [SlipController::class, 'TransferSlip'])->name('TransferSlip');
    Route::post('/TransferSlip/Add', [SlipController::class, 'AddTransferSlip'])->name('AddTransferSlip');
    Route::post('/TransferSlip/Add/Save', [SlipController::class, 'SaveAddTransferSlip'])->name('SaveAddTransferSlip');
});

Route::prefix('PayGoods')->group(function () {
    Route::get('/', [PayGoodsController::class, 'index'])->name('PayGoods');
    Route::get('/SelectPayGoods/{order_number}', [PayGoodsController::class, 'SelectPayGoods'])->name('SelectPayGoods');
});

Route::get('AddItem', function () {
    return view('Admin.ManageStock.addstock');
})->name('Add item');

Route::get('/ManageStock', [App\Http\Controllers\ManageImportProduct::class, 'index'])->name('ManageStock');
Route::get('/ManageSlip/{date}', [App\Http\Controllers\ManageImportProduct::class, 'show_slip'])->name('ManageSlip');
Route::get('/SlipDetail/{slip_id}', [App\Http\Controllers\ManageImportProduct::class, 'show_slip_detail'])->name('SlipDetail');
Route::post('/EditSlipDetail', [App\Http\Controllers\ManageImportProduct::class, 'edit'])->name('EditSlip');
Route::post('/Add-Slip', [App\Http\Controllers\ManageImportProduct::class, 'create'])->name('AddSlip');
Route::get('/check-slip/{id}', [App\Http\Controllers\ManageImportProduct::class, 'check_slip'])->name('CheckSlip');
Route::get('autocomplete', [App\Http\Controllers\ManageImportProduct::class, 'autocomplete'])->name('autocomplete');
//

Route::get('/ManageUsers', [UserController::class, 'index'])->name('ManageUsers');

Route::get('/ManageUsers/Createuser', function () {
    return view('Admin.ManageUsers.createuser');
})->name('Createuser');
Route::post('/ManageUsers/Createuser', [UserController::class, 'create'])->name('ManageUsers.Createuser');
Route::get('/ManageUsers/Toggle/{user_id}/{status}', [UserController::class, 'toggle'])->name('ManageUsers.Toggle');
Route::get('/ManageUsers/Edituser/{user_id}', [UserController::class, 'edit'])->name('Edituser');
Route::post('/ManageUsers/Edituser/{user_id}', [UserController::class, 'update'])->name('Edituser.update');

Route::get('/Profile', function () {
    return view('Admin.profile');
})->name('Profile');

//Manager Routes

Route::get('/Manager/Dashboard', [StatisticsController::class, 'index'])->name('Dashboard.Manager');

Route::get('/Manager/ProductStore', [StatisticsController::class, 'ProductStore'])->name('ProductStore');
Route::get('/Manager/ProductStore/{slip_id}', [StatisticsController::class, 'DetailProductStore'])->name('DetailProductStore');

Route::get('/Manager/ProductStock', [StatisticsController::class, 'ProductStock'])->name('ProductStock');

Route::get('/Manager/CustomerOrder', [StatisticsController::class, 'CustomerOrder'])->name('CustomerOrder');
Route::get('/Manager/CustomerOrder/{order_id}', [StatisticsController::class, 'DetailCustomerOrder'])->name('DetailCustomerOrder');

Route::get('/Manager/Pallet', [StatisticsController::class, 'Pallet'])->name('Pallet');
Route::get('/Manager/Pallet/{pallet_id}', [StatisticsController::class, 'DetailPallet'])->name('DetailPallet');

Route::get('/Manager/Profile', function () {
    return view('Manager.profile');
})->name('Profile');

//User Routes

Route::get('/User/Dashboard', function () {
    return view('Admin.Dashboard.index');
})->name('Dashboard.User');

//employee Routes
Route::get('/Pallet/Work/pallet', [UserWorkController::class, 'index'])->name('Em.Work.pallet');
Route::get('/Pallet/Work/pallet/detail/{pallet_id}/{order_id}', [UserWorkController::class, 'showPalletDetail'])->name('Em.Work.palletDetail');
Route::get('/submit/{pallet_id}', [UserWorkController::class, 'submitPallet'])->name('Em.Work.palletSubmit');
//employee Routes
