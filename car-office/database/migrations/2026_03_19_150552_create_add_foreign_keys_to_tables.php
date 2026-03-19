<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('office_rent_agreements', function (Blueprint $table) {
            $table->foreignId('branch_id')
                  ->constrained('branches')
                  ->onDelete('cascade')
                  ->change(); 

            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->change();
        });

        Schema::table('agreement_renewals', function (Blueprint $table) {
            $table->foreignId('office_rent_agreement_id')
                  ->constrained('office_rent_agreements')
                  ->onDelete('cascade');

            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('branch_id')
                  ->constrained('branches')
                  ->onDelete('restrict');
        });

        Schema::table('vehicle_licenses', function (Blueprint $table) {
            $table->foreignId('vehicle_id')
                  ->constrained('vehicles')
                  ->onDelete('cascade');
        });

        Schema::table('vehicle_service_requests', function (Blueprint $table) {
            $table->foreignId('vehicle_id')
                  ->constrained('vehicles')
                  ->onDelete('cascade');

            $table->foreignId('requested_by')
                  ->constrained('users')
                  ->onDelete('restrict');
        });

        Schema::table('vehicle_maintenance_records', function (Blueprint $table) {
            $table->foreignId('vehicle_service_request_id')
                  ->constrained('vehicle_service_requests')
                  ->onDelete('cascade');

            $table->foreignId('vehicle_id')
                  ->constrained('vehicles')
                  ->onDelete('cascade');
        });

        Schema::table('branch_utilities', function (Blueprint $table) {
            $table->foreignId('branch_id')
                  ->constrained('branches')
                  ->onDelete('cascade');
        });

        Schema::table('utility_payments', function (Blueprint $table) {
            $table->foreignId('branch_utility_id')
                  ->constrained('branch_utilities')
                  ->onDelete('cascade');

            $table->foreignId('paid_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys in reverse order to avoid constraint violation
        Schema::table('utility_payments', function (Blueprint $table) {
            $table->dropForeign(['branch_utility_id']);
            $table->dropForeign(['paid_by']);
        });

        Schema::table('branch_utilities', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
        });

        Schema::table('vehicle_maintenance_records', function (Blueprint $table) {
            $table->dropForeign(['vehicle_service_request_id']);
            $table->dropForeign(['vehicle_id']);
        });

        Schema::table('vehicle_service_requests', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['requested_by']);
        });

        Schema::table('vehicle_licenses', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
        });

        Schema::table('agreement_renewals', function (Blueprint $table) {
            $table->dropForeign(['office_rent_agreement_id']);
            $table->dropForeign(['approved_by']);
        });

        Schema::table('office_rent_agreements', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['approved_by']);
        });
    }
};