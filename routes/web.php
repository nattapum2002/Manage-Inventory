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

Route::resource('users', App\Http\Controllers\UserController::class);

Route::get('/manageshift', function () {
    return view('Admin.ManageShift.manageshift');
});
Route::get('/', function () {
    return view('admin.Dashboard.index');
})->name('Dashboard');

Route::get('/ManageLockStock', function () {
    return view('admin.ManageLockStock.managelockstock');
})->name('ManageLockStock');

Route::get('/ManageQueue', function () {
    return view('admin.ManageQueue.managequeue');
})->name('ManageQueue');

Route::get('/ManageShift', function () {
    return view('admin.ManageShift.manageshift');
})->name('ManageShift');

Route::get('/ManageStock', function () {
    return view('admin.ManageStock.managerecivestock');
})->name('ManageStock');

Route::get('/Profile', function () {
    return view('admin.Profile.profile');
})->name('Profile');