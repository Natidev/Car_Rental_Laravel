<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utility_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_utility_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->date('billing_period_start');
            $table->date('billing_period_end');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue', 'waived'])->default('pending');
            $table->string('receipt_path')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('due_date');
            $table->index(['branch_utility_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utility_payments');
    }
};