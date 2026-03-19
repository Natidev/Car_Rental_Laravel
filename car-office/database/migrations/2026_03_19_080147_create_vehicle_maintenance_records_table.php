<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->string('service_provider', 120);
            $table->text('service_details')->nullable();
            $table->integer('mileage_at_service');
            $table->date('completed_date');
            $table->decimal('cost', 12, 2)->nullable();
            $table->string('report_path')->nullable();
            $table->timestamps();

            $table->index('vehicle_id');
            $table->index('completed_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenance_records');
    }
};