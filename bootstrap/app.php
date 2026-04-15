<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// Make sure to import the Inertia middleware at the top
use App\Http\Middleware\HandleInertiaRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withSchedule(function ($schedule) {
    $schedule->command('printers:scan')->everyFiveMinutes();
})
    ->withMiddleware(function (Middleware $middleware) {
        // This is the correct place to add the middleware.
        // We append it to the 'web' middleware group.
        $middleware->web(append: [
            HandleInertiaRequests::class, // <-- ADD THIS LINE
        ]);

        // You might see other middleware aliases here, which is fine.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();
