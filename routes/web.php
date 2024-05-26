<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ImageController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ModelController::class, 'index'])->name('dashboard');
    Route::get('/update-model-info', [ModelController::class, 'updateModelInfo'])->name('update-model-info');

    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
    Route::post('/materials/{material}/images', [MaterialController::class, 'uploadImage'])->name('materials.images.upload');
    Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');
    Route::delete('/materials/{material}/images/{image}', [MaterialController::class, 'deleteImage'])->name('materials.images.delete');

    Route::get('/training', [ModelController::class, 'showTraining'])->name('show.training');
    Route::post('/train', [ModelController::class, 'trainModel'])->name('train.model');
    Route::post('/resume', [ModelController::class, 'resumeModel'])->name('resume.training');
});

