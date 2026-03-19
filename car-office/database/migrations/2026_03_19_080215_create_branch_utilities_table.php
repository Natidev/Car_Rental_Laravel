<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_utilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['electricity', 'water', 'telephone', 'internet', 'other']);
            $table->string('provider', 100);
            $table->string('account_number', 50);
            $table->string('payment_cycle', 30);     // monthly, bi-monthly, etc.
            $table->timestamps();

            $table->index(['branch_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_utilities');
    }
};