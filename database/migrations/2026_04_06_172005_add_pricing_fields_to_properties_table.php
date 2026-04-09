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
        Schema::table('properties', function (Blueprint $table) {
            $table->string('currency', 10)->default('USD')->after('daily_rent');
            $table->decimal('security_deposit', 12, 2)->nullable()->after('currency');
            $table->decimal('application_fee', 12, 2)->nullable()->after('security_deposit');
            $table->unsignedSmallInteger('min_lease_months')->nullable()->after('application_fee');
            $table->unsignedSmallInteger('max_lease_months')->nullable()->after('min_lease_months');
            $table->json('nearby_places')->nullable()->after('max_lease_months');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'currency',
                'security_deposit',
                'application_fee',
                'min_lease_months',
                'max_lease_months',
                'nearby_places',
            ]);
        });
    }
};
