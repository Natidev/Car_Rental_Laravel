<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
           DB::statement('ALTER TABLE office_rent_agreements DROP CONSTRAINT IF EXISTS office_rent_agreements_branch_id_foreign');
        DB::statement('ALTER TABLE office_rent_agreements DROP CONSTRAINT IF EXISTS office_rent_agreements_approved_by_foreign');
        DB::statement('ALTER TABLE agreement_renewals DROP CONSTRAINT IF EXISTS agreement_renewals_office_rent_agreement_id_foreign');
        DB::statement('ALTER TABLE agreement_renewals DROP CONSTRAINT IF EXISTS agreement_renewals_approved_by_foreign');
        DB::statement('ALTER TABLE vehicles DROP CONSTRAINT IF EXISTS vehicles_branch_id_foreign');
        DB::statement('ALTER TABLE vehicle_licenses DROP CONSTRAINT IF EXISTS vehicle_licenses_vehicle_id_foreign');
        DB::statement('ALTER TABLE vehicle_service_requests DROP CONSTRAINT IF EXISTS vehicle_service_requests_vehicle_id_foreign');
        DB::statement('ALTER TABLE vehicle_service_requests DROP CONSTRAINT IF EXISTS vehicle_service_requests_requested_by_foreign');
        DB::statement('ALTER TABLE vehicle_maintenance_records DROP CONSTRAINT IF EXISTS vehicle_maintenance_records_vehicle_service_request_id_foreign');
        DB::statement('ALTER TABLE vehicle_maintenance_records DROP CONSTRAINT IF EXISTS vehicle_maintenance_records_vehicle_id_foreign');
        DB::statement('ALTER TABLE branch_utilities DROP CONSTRAINT IF EXISTS branch_utilities_branch_id_foreign');
        DB::statement('ALTER TABLE utility_payments DROP CONSTRAINT IF EXISTS utility_payments_branch_utility_id_foreign');
        DB::statement('ALTER TABLE utility_payments DROP CONSTRAINT IF EXISTS utility_payments_paid_by_foreign');

        Schema::table('office_rent_agreements', function (Blueprint $table) {
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->cascadeOnDelete();

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        Schema::table('agreement_renewals', function (Blueprint $table) {
            $table->foreign('office_rent_agreement_id')
                ->references('id')
                ->on('office_rent_agreements')
                ->cascadeOnDelete();

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->restrictOnDelete();
        });

        Schema::table('vehicle_licenses', function (Blueprint $table) {
            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->cascadeOnDelete();
        });

        Schema::table('vehicle_service_requests', function (Blueprint $table) {
            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->cascadeOnDelete();

            $table->foreign('requested_by')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
        });

        Schema::table('vehicle_maintenance_records', function (Blueprint $table) {
            $table->foreign('vehicle_service_request_id')
                ->references('id')
                ->on('vehicle_service_requests')
                ->cascadeOnDelete();

            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->cascadeOnDelete();
        });

        Schema::table('branch_utilities', function (Blueprint $table) {
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->cascadeOnDelete();
        });

        Schema::table('utility_payments', function (Blueprint $table) {
            $table->foreign('branch_utility_id')
                ->references('id')
                ->on('branch_utilities')
                ->cascadeOnDelete();

            $table->foreign('paid_by')
                ->references('id')
                ->on('users')
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