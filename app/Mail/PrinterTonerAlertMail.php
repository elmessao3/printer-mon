<?php
namespace App\Mail;

use App\Models\Printer;
use Illuminate\Mail\Mailable;

class PrinterTonerAlertMail extends Mailable
{
    public $printer;
    public $type;

    public function __construct($printer, $type)
    {
        $this->printer = $printer;
        $this->type = $type; // Toner or Drum
    }

    public function build()
    {
        return $this->subject("Besoin {$this->type} Imprimante {$this->printer->site}")
                    ->view('emails.printer-alert');
    }
}
?>