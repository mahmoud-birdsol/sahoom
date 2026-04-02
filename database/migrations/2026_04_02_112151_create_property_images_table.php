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
        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('path', 500);
            $table->smallInteger('order')->unsigned()->default(0);
            $table->timestamps();

            $table->index(['property_id', 'order']);
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_images');

        Schema::table('properties', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('rejection_reason');
        });
    }
};
