<?php

namespace App\Services;

use App\Models\Printer;
use App\Mail\PrinterTonerAlertMail;
use Illuminate\Support\Facades\Mail;

class PrinterAlertService
{
    public function check()
    {
        $printers = Printer::with('latestStatus')->get();

        foreach ($printers as $printer) {

            $status = $printer->latestStatus;

            if (!$status) {
                continue;
            }

            $toner = $status->toner_level;
            $drum  = $status->drum_level;

            /* ---------------- TONER LOW ALERT ---------------- */

            if ($toner <= 25 && $printer->toner_mail_status == 0) {

                Mail::to('elmessa23@gmail.com')
                    ->send(new PrinterTonerAlertMail($printer, 'Toner'));

                $printer->update([
                    'toner_mail_status' => 1
                ]);
            }

            /* ---------------- DRUM LOW ALERT ---------------- */

            if ($drum <= 25 && $printer->drum_mail_status == 0) {

                Mail::to('elmessa23@gmail.com')
                    ->send(new PrinterTonerAlertMail($printer, "Unité d'image"));

                $printer->update([
                    'drum_mail_status' => 1
                ]);
            }

            /* ---------------- RESET WHEN REFILLED ---------------- */

            if ($toner >= 95) {

                $printer->update([
                    'toner_mail_status' => 0
                ]);
            }

            if ($drum >= 95) {

                $printer->update([
                    'drum_mail_status' => 0
                ]);
            }
        }
    }
}