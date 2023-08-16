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
        Schema::create('goods_received', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicine_id');
            $table->integer('quantity');
            $table->integer('unit_cost');
            $table->integer('selling_price');
            $table->unsignedBigInteger('user_id');
            $table->integer('total_cost')->nullable();
            $table->integer('total_profit')->nullable();
            $table->date('expire_date')->nullable();
            $table->timestamp('received_at');
            $table->timestamps();
            
            // Define foreign keys, indexes, etc. if needed
            
            // ...
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_received');
    }
};
