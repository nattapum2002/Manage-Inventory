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
        Schema::create('product_store', function (Blueprint $table) {
            $table->id();
            $table->string('product_slip_id');
            $table->string('product_slip_number');
            $table->string('product_id');
            $table->string('department');
            $table->integer('weight');
            $table->integer('amount');
            $table->string('comment')->nullable();
            $table->date('store_date');
            $table->time('store_time');
            $table->boolean('check_status');
            $table->string('product_checker');
            $table->string('domestic_checker');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_store');
    }
};