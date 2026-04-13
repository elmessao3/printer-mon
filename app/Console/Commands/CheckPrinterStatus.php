<?php

namespace App\Console\Commands;

use App\Models\Printer;
use App\Models\PrinterStatus;
use Illuminate\Console\Command;

class CheckPrinterStatus extends Command
{
    protected $signature = 'printers:check-status';
    protected $description = 'Checks the status of all registered printers via SNMP';

    public function handle()
    {
        // Ensure SNMP extension exists
        if (!function_exists('snmp2_get')) {
            $this->error('FATAL ERROR: PHP SNMP extension is not enabled.');
            return 1;
        }

        $printers = Printer::all();

        $this->info('Starting status check for ' . $printers->count() . ' printers...');

        foreach ($printers as $printer) {

            // SNMP check (sysUpTime = basic availability test)
            $sysUpTime = @snmp2_get(
                $printer->ip_address,
                $printer->snmp_community,
                '1.3.6.1.2.1.1.3.0',
                1000000,
                2
            );

            if ($sysUpTime !== false) {
                $this->handleOnlinePrinter($printer);
            } else {
                $this->handleOfflinePrinter($printer);
            }
        }

        $this->info('All printers have been checked.');
        return 0;
    }

    /**
     * ONLINE printer logic
     */
    protected function handleOnlinePrinter(Printer $printer)
    {
        PrinterStatus::updateOrCreate(
            ['printer_id' => $printer->id],
            [
                'status' => 'Online',
                'error_message' => null,
                'last_checked_at' => now(),
            ]
        );

        $this->line("  SUCCESS: Printer '{$printer->name}' is Online.");
    }

    /**
     * OFFLINE printer logic
     */
    protected function handleOfflinePrinter(Printer $printer)
    {
        // Optional: update printer table status
        if ($printer->status !== 'offline') {
            $printer->status = 'offline';
            $printer->save();
        }

        PrinterStatus::updateOrCreate(
            ['printer_id' => $printer->id],
            [
                'status' => 'Error',
                'error_message' => 'Device did not respond to SNMP ping.',
                'last_checked_at' => now(),
            ]
        );

        $this->error("  FAILED: Could not connect to '{$printer->name}'. Marked as Offline.");
    }
}