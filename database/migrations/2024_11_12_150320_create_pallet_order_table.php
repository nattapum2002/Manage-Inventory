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
        Schema::create('pallet_detail', function (Blueprint $table) {
            $table->id();
            $table->string('pallet_id');
            $table->string('product_id');
            $table->string('product_number');
            $table->float('quantity');
            $table->float('quantity2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pallet_order');
    }
};
