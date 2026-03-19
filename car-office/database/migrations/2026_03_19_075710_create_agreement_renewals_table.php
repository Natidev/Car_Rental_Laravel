<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agreement_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_rent_agreement_id')->constrained()->onDelete('cascade');
            $table->decimal('new_monthly_rent', 12, 2);
            $table->date('new_start_date');
            $table->date('new_end_date');
            $table->text('amendment_notes')->nullable();
            $table->enum('status', ['draft', 'approved', 'rejected', 'active'])->default('draft');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('office_rent_agreement_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreement_renewals');
    }
};