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
         Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('product_category');
            $table->string('certificate_number');
            $table->string('brand_name');
            $table->text('Classification');
            $table->string('generic_name');
            $table->string('dosage_form');
            $table->string('national_id_no');
            $table->string('active_ingredients');
            $table->string('product_strenght');
            $table->string('registrant');
            $table->string('registrant_country');
            $table->string('local_technical_representative');
            $table->string('manufacturer');
            $table->string('manufacturer_country');
            $table->string('registration_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
