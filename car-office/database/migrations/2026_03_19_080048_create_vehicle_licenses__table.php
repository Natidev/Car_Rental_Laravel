<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->date('bolo_expiry_date')->nullable();
            $table->date('inspection_expiry_date')->nullable();
            $table->string('bolo_receipt_path')->nullable();
            $table->string('inspection_certificate_path')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id']);
            $table->index('bolo_expiry_date');
            $table->index('inspection_expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_licenses');
    }
};