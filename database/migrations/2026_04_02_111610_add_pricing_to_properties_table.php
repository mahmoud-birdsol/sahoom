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
            $table->string('pricing_type', 20)->nullable()->after('size_sqm');
            $table->decimal('monthly_rent', 12, 2)->nullable()->after('pricing_type');
            $table->decimal('weekly_rent', 12, 2)->nullable()->after('monthly_rent');
            $table->decimal('yearly_rent', 12, 2)->nullable()->after('weekly_rent');
            $table->decimal('daily_rent', 12, 2)->nullable()->after('yearly_rent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['pricing_type', 'monthly_rent', 'weekly_rent', 'yearly_rent', 'daily_rent']);
        });
    }
};
