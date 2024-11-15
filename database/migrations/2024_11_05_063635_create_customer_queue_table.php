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
            $table->string('queue_number');
            $table->integer('no');
            $table->time('queue_time');
            $table->date('queue_date');
            $table->integer('queue_no');
            $table->time('entry_time');
            $table->date('entry_date');
            $table->time('release_time');
            $table->date('release_date');
            $table->string('customer_id');
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
        Schema::dropIfExists('customer_queue');
    }
};
