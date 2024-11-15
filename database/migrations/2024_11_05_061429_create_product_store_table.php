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
            $table->string('department');
            $table->date('store_date');
            $table->time('store_time');
            $table->boolean('product_checker');
            $table->boolean('domestic_checker');
            $table->string('shift_id');
            $table->string('note')->nullable();
            $table->boolean('status')->default(true);
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
