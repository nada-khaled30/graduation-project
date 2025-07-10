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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable(); // ✅ الاسم الأول
            $table->string('last_name')->nullable();  // ✅ الاسم الأخير
            $table->enum('gender', ['female', 'male', 'other'])->nullable(); // ✅ الجنس
            $table->enum('specialist', ['dentist', 'cardiologist', 'dermatologist', 'pediatrician'])->nullable(); // ✅ التخصص
            $table->string('email')->unique()->nullable();
            $table->char('user_id', 7)->unique(); // ✅ رقم المستخدم المكون من 7 أرقام
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
