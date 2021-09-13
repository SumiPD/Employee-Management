<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('/employee');
// });
Route::get('/', [App\Http\Controllers\UserController::class,'index']);

Route::resource('employee', UserController::class);
Route::get('/view_employee', [App\Http\Controllers\UserController::class,'view']);
// Route::get('employee/{id}/edit', [App\Http\Controllers\UserController::class,'edit']);
// Route::get('/add_employee',[App\Http\Controllers\UserController::class,'index']);
Route::post('update/{id}',[App\Http\Controllers\UserController::class,'updates'])->name('employee.updates');
Route::get('delete/{id}',[App\Http\Controllers\UserController::class,'delete'])->name('employee.delete');
