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
        Schema::create('telemetry_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();

            $table->timestamp('recorded_at')->index();

            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);

            $table->unsignedSmallInteger('speed')->nullable();
            $table->unsignedSmallInteger('fuel')->nullable();

            $table->boolean('ignition_on')->nullable();

            $table->timestamps();

            $table->index(['trip_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetry_events');
    }
};
