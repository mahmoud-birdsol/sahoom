<?php

use App\Models\States\AvailabilityBlockSource;
use App\Models\States\AvailabilityBlockStatus;
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
        Schema::create('availability_blocks', function (Blueprint $table) {
            $table->id();
            
            // Property reference
            $table->foreignId('property_id')
                ->constrained('properties')
                ->cascadeOnDelete();
            
            // Date range
            $table->date('start_date');
            $table->date('end_date');
            
            // Status and source
            $table->enum('status', AvailabilityBlockStatus::toArray());
            $table->enum('source', AvailabilityBlockSource::toArray())
                ->default(AvailabilityBlockSource::ADMIN->value);
            
            // Contract/booking reference
            $table->string('contract_reference')->nullable();
            
            // Additional notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['property_id', 'start_date', 'end_date'], 'availability_blocks_property_dates');
            $table->index('start_date');
            $table->index('end_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability_blocks');
    }
};
