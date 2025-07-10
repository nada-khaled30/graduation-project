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
    Schema::create('diagnoses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('patient_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('image_path');
        $table->string('predicted_class');
        $table->text('description')->nullable();
        $table->text('analysis')->nullable();
        $table->text('detailed_analysis')->nullable();
        $table->text('recommendations')->nullable();
        $table->timestamps();
    });
  }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};
