<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Printer;
use App\Models\PrinterStatus;
use App\Services\PrinterBrowserClient;

class ScanPrinters extends Command
{
    protected $signature = 'printers:scan';
    protected $description = 'Scan printers and update status';

    public function handle(PrinterBrowserClient $client)
    {
        foreach (Printer::all() as $printer) {

            $data = $client->get($printer->ip_address);

            PrinterStatus::updateOrCreate(
                [
                    'printer_id' => $printer->id
                ],
                [
                    'status' => $data['status'] ?? 'unknown',
                    'toner_level' => $data['toner'] ?? null,
                    'drum_level' => $data['drum'] ?? null,
                    'error_message' => null,
                    'last_checked_at' => now(),
                ]
            );

            $this->info(
                "{$printer->name} | Toner: {$data['toner']} | Drum: {$data['drum']}"
            );
        }

        return 0;
    }
}