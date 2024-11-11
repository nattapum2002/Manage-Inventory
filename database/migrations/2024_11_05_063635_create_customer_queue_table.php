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
            $table->string('queue_id');
            $table->integer('no');
            $table->time('queue_time');
            $table->integer('queue_no');
            $table->time('entry_time');
            $table->time('release_time');
            $table->string('customer_id');
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
        Schema::dropIfExists('customer_queue');
    }
};
