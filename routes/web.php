<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\TaskController;
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

/* ------------- Admin Route -------------- */

Route::prefix('admin')->group(function (){

Route::get('/login',[AdminController::class, 'Index'])->name('login_from');

Route::post('/login',[AdminController::class, 'Login'])->name('admin.login');

Route::get('/dashboard',[AdminController::class, 'Dashboard'])->name('admin.dashboard')->middleware('admin');

Route::get('/logout',[AdminController::class, 'AdminLogout'])->name('admin.logout')->middleware('admin');

Route::get('/task',[TaskController::class, 'index'])->name('task')->middleware('admin');

Route::post('/task/action', [TaskController::class, 'ajax']);
Route::post('/task/create', [TaskController::class, 'create'])->name('create')->middleware('admin');
Route::post('/task/update', [TaskController::class, 'update'])->middleware('admin');
Route::post('/task/delete', [TaskController::class, 'delete'])->middleware('admin');

});

/* ------------- End Admin Route -------------- */


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
