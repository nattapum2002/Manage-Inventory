<?php

use App\Http\Controllers\AutoCreateLockController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\LockController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManageQueueController;
use App\Http\Controllers\ManageStock;
use App\Http\Controllers\ReceiptPlanController;
use App\Http\Controllers\ReceiptProductController;
use App\Http\Controllers\ShiftAndTeamController;
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
use App\Http\Controllers\IncentiveController;
use App\Http\Controllers\LogPdfController;
use App\Http\Controllers\SetDataController;
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
Route::prefix('ManageIncentive')->group(function () {
    Route::get('/Incentive', function () {
        return view('Admin.ManageIncentive.IncentiveDashbord');
    })->name('IncentiveDashbord');
    Route::get('/Incentive/Arrange', [IncentiveController::class, 'incentiveArrange'])->name('IncentiveArrange');
    Route::get('/Incentive/Arrange/{date}/Worker', [IncentiveController::class, 'incentiveArrangeWorker'])->name('IncentiveArrangeWorker');
    Route::get('/Incentive/Arrange/{date}/{user_id}/Detail', [IncentiveController::class, 'incentiveArrangeWorkerDetail'])->name('IncentiveArrangeWorkerDetail');

    Route::get('/Incentive/Drag', [IncentiveController::class, 'incentiveDrag'])->name('IncentiveDrag');
    Route::get('/Incentive/Drag/{month}/{year}/Worker', [IncentiveController::class, 'incentiveDragWorker'])->name('incentiveDragWorker');
    Route::get('/Incentive/Drag/{month}/{year}/{user_id}/Detail', [IncentiveController::class, 'incentiveDragWorkerDetail'])->name('IncentiveDragWorkerDetail');
});
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

Route::prefix('ShowStat')->group(function () {
    Route::get('/Dispense/{date}', [ShowStat::class, 'show_stat_dispense'])->name('Show_dispense_stat');
    Route::get('/Imported/{date}', [ShowStat::class, 'show_stat_imported'])->name('Show_imported_stat');
    Route::get('/ShowStatDate', [ShowStat::class, 'show_date'])->name('ShowStatDate');
    Route::post('/ProductTransactionsFilterMonth', [ShowStat::class, 'ProductTransactionsFilterMonth'])->name('ProductTransactionsFilterMonth');
});

Route::prefix('ShowStock')->group(function () {
    // Route::get('/ShowStock', [ShowStock::class, 'index'])->name('ShowStock');
    Route::get('/SyncProduct', [ShowStock::class, 'SyncProduct'])->name('SyncProduct');
    Route::post('/StockFilter', [ShowStock::class, 'StockFilter'])->name('StockFilter');
    Route::get('/Admin/ShowStock', [ShowStock::class, 'Admin_index'])->name('AdminShowStock');
    Route::get('/ProductDetail/{product_id}', [ShowStock::class, 'ProductDetail'])->name('ProductDetail');
    Route::post('/ProductDetail/SaveEditProduct', [ShowStock::class, 'SaveEditProduct'])->name('SaveEditProduct');
});

Route::get('/Admin/Dashboard', function () {
    return view('Admin.Dashboard.index');
})->name('Dashboard.Admin');

Route::prefix('ManageQueue')->group(function () {
    Route::get('/', [CustomerQueueController::class, 'index'])->name('ManageQueue');
    Route::get('/syncQueue', [CustomerQueueController::class, 'syncQueue'])->name('syncQueue');
    Route::post('/FilterDate', [CustomerQueueController::class, 'ManageQueueFilterDate'])->name('ManageQueueFilterDate');
    Route::post('/Add', [CustomerQueueController::class, 'AddCustomerQueue'])->name('AddCustomerQueue');
    Route::post('/Add/Save', [CustomerQueueController::class, 'SaveAddCustomerQueue'])->name('SaveAddCustomerQueue');
    Route::get('/Detail/{order_number}', [CustomerQueueController::class, 'DetailCustomerQueue'])->name('DetailCustomerQueue');

    Route::get('/QueuePallet/Detail/{pallet_id}/{order_id}', [CustomerQueueController::class, 'PalletDetail'])->name('QueuePalletDetail');
    Route::get('/ConfirmReceive/{order_id}/{pallet_id}', [CustomerQueueController::class, 'confirmReceive'])->name('ConfirmReceive');
});

Route::prefix('ManageLockStock')->group(function () {
    Route::get('/', [LockController::class, 'index'])->name('ManageLockStock');
    Route::get('Order-detail/{CUS_ID}/{order_date}', [LockController::class, 'DetailLockStock'])->name('DetailLockStock');
    Route::get('/Detail/{order_number}/', [LockController::class, 'AddPallet'])->name('AddPallet');
    Route::get('/Detail/AddPallet/AutoCompleteAddPallet', [AutoCreateLockController::class, 'AutoCompleteAddPallet'])->name('AutoCompleteAddPallet');
    Route::get('/Detail/{ORDER_DATE}/{CUS_ID}/Pallet/{pallet_id}', [LockController::class, 'DetailPallets'])->name('DetailPallets');
    Route::get('/forgetSession/{CUS_ID}/{ORDER_DATE}', [AutoCreateLockController::class, 'forgetSession'])->name('forgetSession');
    Route::get('/Detail/insertPallet/{CUS_ID}/{ORDER_DATE}', [AutoCreateLockController::class, 'insert_pallet'])->name('Insert_Pallet');
    Route::get('/Detail/editPalletOrde/{order_id}/{product_id}', [LockController::class, 'EditPalletOrder'])->name('EditPalletOrder');
    Route::get('/Arrange/{CUS_id}/{order_date}', [AutoCreateLockController::class, 'ShowPreLock'])->name('PreLock');
   
    Route::post('/UPDATE/{id}', [LockController::class, 'update_lock_team'])->name('UpdateLockTeam');
    Route::post('/UpSell/{CUS_ID}/{ORDER_DATE}', [AutoCreateLockController::class, 'addUpSellPallet'])->name('addUpSellPallet');
    Route::get('/Detail/PalletType/UPDATE/{pallet_id}', [LockController::class, 'updatePalletType'])->name('updatePalletType');
    Route::get('/AUTO/{CUS_ID}/{ORDER_DATE}', [AutoCreateLockController::class, 'autoCreateLock'])->name('AutoLock');
});

Route::prefix('ReceiptPlan')->group(function () {
    Route::get('/', [ReceiptPlanController::class, 'index'])->name('ReceiptPlan');
    Route::post('/GetShifts', [ReceiptPlanController::class, 'GetShifts'])->name('GetShifts');
    Route::post('/ReceiptPlanFilterMonth', [ReceiptPlanController::class, 'ReceiptPlanFilterMonth'])->name('ReceiptPlanFilterMonth');
    Route::post('/Add', [ReceiptPlanController::class, 'AddReceiptPlan'])->name('AddReceiptPlan');
    Route::post('/Add/Save', [ReceiptPlanController::class, 'SaveAddReceiptPlan'])->name('SaveAddReceiptPlan');
    Route::get('/Add/Cancel', [ReceiptPlanController::class, 'CancelAddReceiptPlan'])->name('CancelAddReceiptPlan');
    Route::get('/Edit/{receipt_plan_id}', [ReceiptPlanController::class, 'EditReceiptPlan'])->name('EditReceiptPlan');
    Route::post('/Edit/AddProduct', [ReceiptPlanController::class, 'AddProduct'])->name('AddProduct');
    Route::post('/Edit/SaveEditDetail', [ReceiptPlanController::class, 'SaveEditDetail'])->name('SaveEditDetail');
    Route::post('/Edit/SaveEditProductPlan', [ReceiptPlanController::class, 'SaveEditProductPlan'])->name('SaveEditProductPlan');
    Route::get('/Edit/{receipt_plan_id}/DeleteProduct/{product_id}', [ReceiptPlanController::class, 'DeleteProduct'])->name('DeleteProduct');
    Route::post('/AutocompleteProduct', [ReceiptPlanController::class, 'AutocompleteProduct'])->name('AutocompleteProduct');
});

Route::prefix('ReceiptProduct')->group(function () {
    Route::get('/', [ReceiptProductController::class, 'index'])->name('ReceiptProduct');
    Route::post('/ReceiptPlanFilter', [ReceiptProductController::class, 'ReceiptPlanFilter'])->name('ReceiptPlanFilter');
    Route::post('/SaveReceiptProduct', [ReceiptProductController::class, 'SaveReceiptProduct'])->name('SaveReceiptProduct');
});

Route::prefix('ManageShiftAndTeam')->group(function () {
    Route::get('/', [ShiftAndTeamController::class, 'index'])->name('ManageShiftTeam');
    Route::get('/AutoCompleteTeam', [ShiftAndTeamController::class, 'AutoCompleteTeam'])->name('AutoCompleteTeam');
    Route::post('/FilterShift', [ShiftAndTeamController::class, 'ShiftFilter'])->name('ShiftFilter');
    Route::post('/FilterMonth', [ShiftAndTeamController::class, 'ShiftFilterMonth'])->name('ShiftFilterMonth');
    Route::post('/AddShift', [ShiftAndTeamController::class, 'AddShift'])->name('AddShift');
    Route::post('/AddShift/Save', [ShiftAndTeamController::class, 'SaveAddShift'])->name('SaveAddShift');
    Route::post('/CopyShiftAndTeam', [ShiftAndTeamController::class, 'CopyShiftAndTeam'])->name('CopyShiftAndTeam');
    Route::get('/ShiftToggle/{shift_id}/{status}', [ShiftAndTeamController::class, 'ShiftToggle'])->name('ShiftToggle');
    Route::post('/DeleteShiftTeam/{Shift_id}', [ShiftAndTeamController::class, 'DeleteShiftTeam'])->name('DeleteShiftTeam');
    Route::get('/EditShiftTeam/{Shift_id}', [ShiftAndTeamController::class, 'EditShiftTeam'])->name('EditShiftTeam');
    Route::get('/EditShiftTeam/{Shift_id}/AddTeamForm', [ShiftAndTeamController::class, 'AddTeamForm'])->name('AddTeamForm');
    Route::post('/EditShiftTeam/{Shift_id}/Save', [ShiftAndTeamController::class, 'SaveEditShift'])->name('SaveEditShift');
    Route::post('/EditShiftTeam/{Shift_id}/AddTeam/Save', [ShiftAndTeamController::class, 'SaveAddTeam'])->name('SaveAddTeam');
    Route::post('/EditShiftTeam/{Shift_id}/EditTeam/Save', [ShiftAndTeamController::class, 'SaveEditTeam'])->name('SaveEditTeam');
    Route::get('/EditShiftTeam/{Shift_id}/Delete/{team_id}', [ShiftAndTeamController::class, 'DeleteTeam'])->name('DeleteTeam');
    Route::get('/AutocompleteSearchTeam', [TeamController::class, 'AutocompleteSearchTeam'])->name('AutocompleteSearchTeam');
    Route::get('/AutocompleteDMCPosition', [ShiftAndTeamController::class, 'AutocompleteDMCPosition'])->name('AutocompleteDMCPosition');
    Route::get('/AutocompleteWork', [ShiftAndTeamController::class, 'AutocompleteWork'])->name('AutocompleteWork');
});

Route::prefix('PayGoods')->group(function () {
    Route::get('/', [PayGoodsController::class, 'index'])->name('PayGoods');
    Route::post('/SelectPayGoods', [PayGoodsController::class, 'SelectPayGoods'])->name('SelectPayGoods');
    Route::post('/Incentive/StartWork', [PayGoodsController::class, 'StartWork'])->name('StartWork');
    Route::post('/Incentive/EndWork', [PayGoodsController::class, 'EndWork'])->name('EndWork');
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
Route::prefix('ManageUsers')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('ManageUsers');
    Route::get('/Profile', [UserController::class, 'Profile'])->name('Profile');
    Route::post('/Profile/Save', [UserController::class, 'SaveProfile'])->name('SaveProfile');
    Route::post('/Createuser', [UserController::class, 'create'])->name('CreateUser');
    Route::get('/syncUsers', [UserController::class, 'syncUsers'])->name('syncUsers');
    Route::get('/Toggle/{user_id}/{status}', [UserController::class, 'toggle'])->name('ManageUsers.Toggle');
    Route::get('/Edituser/{user_id}', [UserController::class, 'edit'])->name('Edituser');
    Route::post('/Edituser/{user_id}', [UserController::class, 'update'])->name('Edituser.update');
});

Route::prefix('SetData')->group(function () {
    Route::get('/', [SetDataController::class, 'index'])->name('SetData');
    Route::post('/getSetData', [SetDataController::class, 'getSetData'])->name('getSetData');
    Route::post('/SaveAddSetData', [SetDataController::class, 'SaveAddSetData'])->name('SaveAddSetData');
    Route::post('/SaveUpdateSetData', [SetDataController::class, 'SaveUpdateSetData'])->name('SaveUpdateSetData');
    Route::post('/DeleteSetData', [SetDataController::class, 'DeleteSetData'])->name('DeleteSetData');
});

//Manager Routes

Route::get('/Manager/Dashboard', [StatisticsController::class, 'index'])->name('Dashboard.Manager');

Route::get('/Manager/ProductStore', [StatisticsController::class, 'ProductStore'])->name('ProductStore');
Route::get('/Manager/ProductStore/{slip_id}', [StatisticsController::class, 'DetailProductStore'])->name('DetailProductStore');

Route::get('/Manager/ProductStock', [StatisticsController::class, 'ProductStock'])->name('ProductStock');

Route::get('/Manager/CustomerOrder', [StatisticsController::class, 'CustomerOrder'])->name('CustomerOrder');
Route::get('/Manager/CustomerOrder/{order_id}', [StatisticsController::class, 'DetailCustomerOrder'])->name('DetailCustomerOrder');

Route::get('/Manager/Pallet', [StatisticsController::class, 'Pallet'])->name('Pallet');
Route::get('/Manager/Pallet/{pallet_id}', [StatisticsController::class, 'DetailPallet'])->name('DetailPallet');

// Route::get('/Manager/Profile', function () {
//     return view('Manager.profile');
// })->name('Profile');

//User Routes

Route::get('/Employee/Dashboard', function () {
    return view('Admin.Dashboard.index');
})->name('Dashboard.Employee');

//employee Routes
Route::get('/Pallet/Work/pallet', [UserWorkController::class, 'index'])->name('Em.Work.pallet');
Route::get('/Pallet/Work/pallet/detail/{pallet_id}', [UserWorkController::class, 'showPalletDetail'])->name('Em.Work.palletDetail');
Route::get('/submit/{pallet_id}', [UserWorkController::class, 'submitPallet'])->name('Em.Work.palletSubmit');
//employee Routes

Route::get('/test/pdf', [LogPdfController::class, 'LogPdfDownload'])->name('LoadLog');
