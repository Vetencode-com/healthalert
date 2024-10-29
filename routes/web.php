<?php

use App\Http\Controllers\PrescriptionController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/')->middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('prescriptions')->group(function () {
        Route::get('/', [PrescriptionController::class, 'index'])->name('prescriptions');
        Route::get('/new', [PrescriptionController::class, 'create'])->name('prescriptions.create');
    });

    Route::group(['prefix' => 'component', 'as' => 'component.'], function () {
        Route::get('accordion', function () {
            return view('mazer.components.accordion');
        })->name('accordion');
    });
});

require_once __DIR__ . "/auth.php";
