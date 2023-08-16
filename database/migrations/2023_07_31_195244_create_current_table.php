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
        Schema::create('inv_current_stock', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->integer('unit_cost')->nullable();
            $table->integer('batch_number')->nullable();
            $table->integer('shelf_number')->nullable();
            $table->integer('store_id')->nullable();
            $table->integer('payment_method_id')->nullable();
            $table->timestamps();
            $table->integer('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_current_stock');
    }
};
