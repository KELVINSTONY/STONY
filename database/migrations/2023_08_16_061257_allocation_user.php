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
        Schema::create('allocation_users', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('patient_id');
        $table->integer('allocated')->default(0);
        $table->integer('attended')->default(0);
        $table->integer('status')->default(0);
        $table->unsignedBigInteger('user_id');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocation_users');
    }
};
