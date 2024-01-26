<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

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

Route::get('/', [UserController::class, 'getQueueData'])->name('queue-data');

Route::get('/login', [AdminController::class, 'login'])->name('login');
Route::post('/login', [AdminController::class, 'doLogin'])->name('doLogin');

Route::middleware(['auth'])->group(function () {
    Route::get('/antrians', [AdminController::class, 'getAntrian'])->name('antrians');
    Route::get('/antrians/list', [AdminController::class, 'listAntrian']);
    Route::get('/antrians/finish', [AdminController::class, 'getFinishedQueues']);
    Route::post('/antrians/navigate', [AdminController::class, 'navigateAntrian']);
});
