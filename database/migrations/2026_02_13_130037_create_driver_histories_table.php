<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained()->cascadeOnDelete();

            $table->timestamp('started_at')->index();
            $table->timestamp('ended_at')->nullable()->index();

            $table->timestamps();

            // Indexes para queries de histórico
            $table->index(['vehicle_id', 'started_at', 'ended_at']);
            $table->index(['driver_id', 'started_at', 'ended_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_histories');
    }
};
