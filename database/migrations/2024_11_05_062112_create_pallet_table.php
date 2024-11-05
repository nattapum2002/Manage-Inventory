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
            $table->string('pallet_id')->unique();
            $table->string('order_id');
            $table->string('product_id');
            $table->integer('order_amount');
            $table->integer('send_amount');
            $table->string('bag_color');
            $table->string('room');
            $table->time('pack_start_time');
            $table->time('pack_end_time');
            $table->string('checker_id');
            $table->string('shift_id');
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