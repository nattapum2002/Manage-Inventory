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
        Schema::create('pallet', function (Blueprint $table) {
            $table->id();
            $table->string('pallet_id');
            $table->string('pallet_number');
            $table->string('pallet_name');
            $table->boolean('recipe_status')->default(false);
            $table->boolean('arrange_pallet_status')->default(false);
            $table->string('order_number');
            $table->string('pallet_desc');
            $table->string('warehouse_id');
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
        Schema::dropIfExists('pallet');
    }
};