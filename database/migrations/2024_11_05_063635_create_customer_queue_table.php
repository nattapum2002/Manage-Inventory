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
        Schema::create('customer_queue', function (Blueprint $table) {
            $table->id();
            $table->string('queue_id')->unique();
            $table->string('order_id');
            $table->integer('queue');
            $table->time('time_queue');
            $table->time('recive_time');
            $table->boolean('recive_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_queue');
    }
};