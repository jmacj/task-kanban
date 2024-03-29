<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('/user')->group( function () {
    Route::post('/store', [UserController::class, 'store']);
    Route::post('/login', [UserController::class, 'login'])->middleware('web');
});

Route::get('/tasks', [TaskController::class, 'index'])->middleware('auth')->name('tasks.index');
Route::prefix('/task')->middleware('auth')->group( function () {
    Route::post('/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

