<?php

use App\Http\Controllers\PrinterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Make printer dashboard the homepage
Route::get('/', function () {
    return redirect()->route('printers.index');
});
Route::get('/printers', [PrinterController::class, 'index']);
// Printer routes
Route::prefix('printers')->name('printers.')->group(function () {

    Route::get('/', [PrinterController::class, 'index'])->name('index');

    Route::get('/create', [PrinterController::class, 'create'])->name('create');

    Route::post('/', [PrinterController::class, 'store'])->name('store');

    Route::get('/{printer}', [PrinterController::class, 'show'])->name('show');

});