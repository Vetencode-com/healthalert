<?php

use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PrescriptionMedicineController;
use App\Http\Controllers\WhatsAppController;
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
    return view('dashboard');
});

Route::prefix('/')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('prescriptions')->group(function () {
        Route::get('/', [PrescriptionController::class, 'index'])->name('prescriptions');
        Route::get('/new', [PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('/', [PrescriptionController::class, 'store'])->name('prescriptions.store');
        // Route::get('/medicines', [PrescriptionController::class, 'index'])->name('prescriptions');
        Route::post('/medicines', [PrescriptionMedicineController::class, 'store'])->name('prescriptions.medicines.store');
        Route::delete('/medicines/{id}', [PrescriptionMedicineController::class, 'delete'])->name('prescriptions.medicines.delete');
        Route::patch('/medicines/frequency/{id}', [PrescriptionMedicineController::class, 'change_frequency'])->name('prescriptions.medicines.frequency.change');
        Route::patch('/medicines/times/{id}', [PrescriptionMedicineController::class, 'change_time'])->name('prescriptions.medicines.times.change');
    });

    Route::get('/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp');

    Route::group(['prefix' => 'component', 'as' => 'component.'], function () {
        Route::get('accordion', function () {
            return view('mazer.components.accordion');
        })->name('accordion');
    });
});

require_once __DIR__ . "/auth.php";
