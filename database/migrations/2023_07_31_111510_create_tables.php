<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        
        Schema::create('acc_expenses', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 20, 2);
            $table->integer('expense_category_id');
            $table->integer('expense_sub_category_id')->nullable();
            $table->string('expense_description')->nullable();
            $table->integer('payment_method_id')->nullable();
            $table->timestamps();
            $table->integer('updated_by');
        });
        // Create other tables here...
     
        // Table: acc_expense_categories
        Schema::create('acc_expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
        });
       

        // Insert data into acc_expense_categories table
        DB::table('acc_expense_categories')->insert([
            ['id' => 14, 'name' => 'Cleaning & cleaning materials'],
            ['id' => 7, 'name' => 'Electricity'],
            ['id' => 9, 'name' => 'Generator fuel'],
            ['id' => 10, 'name' => 'Generator service'],
            ['id' => 4, 'name' => 'Marketing & commissions'],
            ['id' => 2, 'name' => 'Medicine'],
            ['id' => 3, 'name' => 'Other laboratory supplies'],
            ['id' => 13, 'name' => 'Packaging & packaging materials'],
            ['id' => 12, 'name' => 'Polyclinic medical supplies'],
            ['id' => 1, 'name' => 'Reagents'],
            ['id' => 11, 'name' => 'Repair & maintenance'],
            ['id' => 5, 'name' => 'Salary & wages'],
            ['id' => 6, 'name' => 'Stationeries'],
            ['id' => 8, 'name' => 'Water Bills'],
        ]);

        // Table: acc_expense_sub_categories
        Schema::create('acc_expense_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
        });

        // Insert data into other tables here...
      
        Schema::create('adjustment_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('reason')->nullable();
        });
        DB::table('adjustment_reasons')->insert([
            ['id' => 2, 'reason' => 'Broken'],
            ['id' => 1, 'reason' => 'Exipred'],
            ['id' => 4, 'reason' => 'stock taking'],
            ['id' => 3, 'reason' => 'wrong entry'],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop all the tables if you need to rollback the migration
        Schema::dropIfExists('acc_expenses');
        Schema::dropIfExists('acc_expense_categories');
        Schema::dropIfExists('acc_expense_sub_categories');
        Schema::dropIfExists('adjustment_reasons');
        // Drop other tables here...
    }
}
