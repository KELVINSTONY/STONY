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
        Schema::create('wf_definitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level_id')->unsigned();
            $table->integer('unit_id')->unsigned()->index('unit_id');
            $table->integer('designation_id')->unsigned()->index('designation_id');
            $table->text('description', 65535)->nullable();
            $table->text('msg_next', 65535)->nullable()->comment('message which should be displayed to the next user.');
            $table->integer('wf_module_id')->unsigned()->index('wf_module_id');
            $table->boolean('active')->default(1)->comment('set whether workflow definitions is active or not, 1 - active, 0 - not active.');
            $table->timestamp('create_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('updated_at')->nullable();
            $table->integer('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wf_definitions');
    }
};
