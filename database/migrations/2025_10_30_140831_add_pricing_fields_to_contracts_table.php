<?php

use App\Models\States\PricingType;
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
        Schema::table('contracts', function (Blueprint $table) {
            // Add pricing type field
            $table->enum('pricing_type', PricingType::toArray())
                ->default(PricingType::MONTHLY->value)
                ->after('end_date');
            
            // Add individual pricing fields
            $table->decimal('monthly_rent', 10, 2)->nullable()->after('pricing_type');
            $table->decimal('weekly_rent', 10, 2)->nullable()->after('monthly_rent');
            $table->decimal('yearly_rent', 10, 2)->nullable()->after('weekly_rent');
            $table->decimal('daily_rent', 10, 2)->nullable()->after('yearly_rent');
            
            // Add deposit and additional fees
            $table->decimal('security_deposit', 10, 2)->nullable()->after('daily_rent');
            $table->decimal('service_fee', 10, 2)->nullable()->after('security_deposit');
            $table->decimal('cleaning_fee', 10, 2)->nullable()->after('service_fee');
            
            // Add index for pricing type
            $table->index('pricing_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'pricing_type',
                'monthly_rent',
                'weekly_rent',
                'yearly_rent',
                'daily_rent',
                'security_deposit',
                'service_fee',
                'cleaning_fee',
            ]);
        });
    }
};
