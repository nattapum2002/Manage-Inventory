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
        Schema::create('customer_order_detail', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
            $table->float('ordered_quantity')->nullable();
            $table->float('ordered_quantity2')->nullable();
            $table->string('bag_color')->nullable();
            $table->string('order_number');
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
        Schema::dropIfExists('customer_order_detail');
    }
};
