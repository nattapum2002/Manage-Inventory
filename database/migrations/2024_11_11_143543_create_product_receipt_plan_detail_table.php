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
        Schema::create('product_receipt_plan_detail', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
            $table->string('product_name');
            $table->integer('weight');
            $table->integer('increase_weight');
            $table->integer('reduce_weight');
            $table->integer('total_weight');
            $table->string('product_receipt_plan_id');
            $table->string('note')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_receipt_plan_detail');
    }
};
