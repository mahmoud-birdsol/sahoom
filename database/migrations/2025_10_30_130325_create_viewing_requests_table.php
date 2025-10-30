<?php

use App\Models\States\ViewingRequestStatus;
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
        Schema::create('viewing_requests', function (Blueprint $table) {
            $table->id();
            
            // Property reference
            $table->foreignId('property_id')
                ->constrained('properties')
                ->cascadeOnDelete();
            
            // Renter information
            $table->string('renter_name');
            $table->string('renter_email');
            $table->string('renter_phone')->nullable();
            
            // Request details
            $table->text('message')->nullable();
            $table->date('preferred_date')->nullable();
            
            // Status and assignment
            $table->enum('status', ViewingRequestStatus::toArray())
                ->default(ViewingRequestStatus::NEW->value);
            
            $table->foreignId('handled_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
            $table->timestamps();
            
            // Indexes for filtering
            $table->index('status');
            $table->index('created_at');
            $table->index(['property_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viewing_requests');
    }
};
