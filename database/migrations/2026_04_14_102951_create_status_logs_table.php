<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('status_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('printer_id')->constrained()->cascadeOnDelete();
    $table->string('status');
    $table->integer('toner_level')->nullable();
    $table->integer('drum_level')->nullable();
    $table->text('error_message')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_logs');
    }
};
