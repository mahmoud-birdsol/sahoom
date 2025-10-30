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
        Schema::create('amenity_property', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amenity_id')
                ->constrained('amenities')
                ->cascadeOnDelete();
            $table->foreignId('property_id')
                ->constrained('properties')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['amenity_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_property');
    }
};
