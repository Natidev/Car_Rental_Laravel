<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_rent_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('agreement_id', 50)->unique();
            $table->string('landlord_name', 120);
            $table->text('property_address');
            $table->decimal('monthly_rent', 12, 2);
            $table->string('payment_schedule', 50);           // e.g. "monthly", "quarterly"
            $table->date('start_date');
            $table->date('end_date');
            $table->string('scanned_contract_path')->nullable(); // storage path
            $table->enum('status', ['draft', 'under_review', 'active', 'expired', 'terminated'])->default('draft');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['branch_id', 'status']);
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_rent_agreements');
    }
};