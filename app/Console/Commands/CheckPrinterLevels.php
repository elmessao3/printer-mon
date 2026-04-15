<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PrinterAlertService;

class CheckPrinterLevels extends Command
{
    protected $signature = 'printers:check-levels';

    protected $description = 'Check toner and drum levels and send alerts';

    public function handle(PrinterAlertService $service)
    {
        $service->check();

        $this->info('Printer levels checked successfully.');
    }
}




?>