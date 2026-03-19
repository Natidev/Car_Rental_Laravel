<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('restrict');
            $table->string('plate_number', 20)->unique();
            $table->string('registration_number', 50)->nullable();
            $table->string('make_model', 100);
            $table->integer('current_mileage')->default(0);
            $table->date('last_service_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('plate_number');
            $table->index('branch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};