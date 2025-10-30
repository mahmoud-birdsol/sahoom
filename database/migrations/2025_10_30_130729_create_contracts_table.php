<?php

use App\Models\States\ContractStatus;
use App\Models\States\PaymentStatus;
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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            
            // Property and landlord references
            $table->foreignId('property_id')
                ->constrained('properties')
                ->cascadeOnDelete();
            
            $table->foreignId('landlord_id')
                ->constrained('landlords')
                ->cascadeOnDelete();
            
            // Renter information
            $table->string('renter_name');
            $table->string('renter_company')->nullable();
            
            // Contract period
            $table->date('start_date');
            $table->date('end_date');
            
            // Financial details
            $table->decimal('total_value', 10, 2);
            $table->string('currency', 3)->default('USD');
            
            // Status fields
            $table->enum('payment_status', PaymentStatus::toArray())
                ->default(PaymentStatus::NOT_COLLECTED->value);
            
            $table->enum('contract_status', ContractStatus::toArray())
                ->default(ContractStatus::ACTIVE->value);
            
            // Internal notes
            $table->text('notes_internal')->nullable();
            
            $table->timestamps();
            
            // Indexes for filtering and queries
            $table->index(['property_id', 'start_date']);
            $table->index('contract_status');
            $table->index('payment_status');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
