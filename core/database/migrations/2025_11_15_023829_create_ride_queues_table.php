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
        Schema::create('ride_queues', function (Blueprint $table) {
            $table->id();
            $table->integer('ride_id')->unsigned()->default(0);
            $table->string('action_type')->nullable();
            $table->bigInteger('ordering')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_queues');
    }
};
