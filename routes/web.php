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
Route::get('/snmp-check', function () {

    snmp_set_valueretrieval(SNMP_VALUE_PLAIN);

    $ip = '192.168.45.90';
    $community = 'public';

    return [
        'sysName' => @snmp2_get($ip, $community, '1.3.6.1.2.1.1.5.0'),
        'sysDesc' => @snmp2_get($ip, $community, '1.3.6.1.2.1.1.1.0'),
        'contact' => @snmp2_get($ip, $community, '1.3.6.1.2.1.1.4.0'),
    ];
});Route::get('/snmp-debug', function () {

    snmp_set_valueretrieval(SNMP_VALUE_PLAIN);

    return [
        '43' => @snmp2_walk('192.168.45.90', 'public', '1.3.6.1.2.1.43'),
        '43.11' => @snmp2_walk('192.168.45.90', 'public', '1.3.6.1.2.1.43.11'),
        '43.12' => @snmp2_walk('192.168.45.90', 'public', '1.3.6.1.2.1.43.12'),
        'private_hp' => @snmp2_walk('192.168.45.90', 'public', '1.3.6.1.4.1.11'),
    ];

});
Route::get('/snmp-full', function () {

    snmp_set_valueretrieval(SNMP_VALUE_PLAIN);

    $ip = '192.168.45.90';
    $community = 'public';

    return [
        'hp_root' => @snmp2_walk($ip, $community, '1.3.6.1.4.1.11'),
        'printer_mib' => @snmp2_walk($ip, $community, '1.3.6.1.2.1.43'),
    ];
});
// In routes/web.php

Route::get('/fetch-printer-status', [PrinterController::class, 'fetchPrinterStatus']);
Route::get('/printer-status/{ip}', [PrinterController::class, 'fetchPrinterStatus']);
Route::resource('printers', PrinterController::class);