<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Printer extends Model
{
    use HasFactory;

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
        'toner_mail_status',
        'drum_mail_status'
    ];

    protected $appends = ['current_status'];

    /* ---------------- HISTORY ---------------- */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(PrinterStatus::class, 'printer_id');
    }

    /* ---------------- LATEST STATUS ---------------- */
    public function latestStatus(): HasOne
    {
        return $this->hasOne(PrinterStatus::class, 'printer_id')
            ->latestOfMany();
    }

    /* ---------------- FINAL STATUS (USED IN UI) ---------------- */
    public function getCurrentStatusAttribute(): string
{
    // 1. DB status (FAST - main source)
    if ($this->latestStatus?->status) {
        return $this->latestStatus->status;
    }

    // 2. fallback (only if no DB record)
    try {
        $socket = @fsockopen($this->ip_address, 9100, $errno, $errstr, 1);

        if ($socket) {
            fclose($socket);
            return 'online';
        }

        return 'offline';

    } catch (\Throwable $e) {
        return 'unknown';
    }
}
}