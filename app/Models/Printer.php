<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class Printer extends Model
{
    use HasFactory;

    // ... $fillable array is here ...
    protected $fillable = [
    'name',
    'ip_address',
    'model',
    'serial_number',
    'site',
    'location',
    'snmp_community',
    'supplier_email',
    'status',
    'image_path',
];

    /**
     * Define the relationship to the printer's status logs.
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(PrinterStatus::class)->orderBy('created_at', 'desc');
    }
}
