<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrinterStatus extends Model
{
    use HasFactory;

    protected $table = 'printer_status';

    protected $fillable = [
        'printer_id',
        'toner_level',
        'drum_level',
        'status',
        'error_code',
        'error_message',
        'last_checked_at',
    ];

    public $timestamps = false;
}