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
            $table->float('product_quantity');
            $table->float('increase_quantity');
            $table->float('reduce_quantity');
            $table->float('total_quantity');
            $table->string('product_receipt_plan_id');
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
        Schema::dropIfExists('product_receipt_plan_detail');
    }
};
