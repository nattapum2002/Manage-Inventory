<?php

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

Route::get('/additem', function () {
    return view('Admin.ManageStock.addstock');
})->name('Add item');

Route::get('/', [App\Http\Controllers\LoginController::class, 'index'])->name('Login.index');
Route::post('/Login', [App\Http\Controllers\LoginController::class, 'login'])->name('Login');
Route::get('/Logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('Logout');

Route::get('/Admin/Dashboard', function () {
    return view('Admin.Dashboard.index');
})->name('Dashboard.Admin');

Route::get('/ManageLockStock', function () {
    return view('Admin.ManageLockStock.managelockstock');
})->name('ManageLockStock');

Route::get('/ManageQueue', function () {
    return view('Admin.ManageQueue.managequeue');
})->name('ManageQueue');

Route::get('/ManageShift', function () {
    return view('Admin.ManageShift.manageshift');
})->name('ManageShift');

Route::get('/ManageStock', [App\Http\Controllers\managestock::class, 'index'])->name('ManageStock');
Route::get('/ManageSlip/{date}', [App\Http\Controllers\managestock::class, 'show_slip'])->name('ManageSlip');
Route::get('/SlipDetail/{slip_id}', [App\Http\Controllers\managestock::class, 'show_slip_detail'])->name('SlipDetail');

Route::post('/AddSlip', [App\Http\Controllers\managestock::class, 'create'])->name('AddSlip');
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