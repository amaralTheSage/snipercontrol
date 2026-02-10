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
        Schema::create('audio_recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained(); // Assuming you have these tables
            $table->foreignId('driver_id')->nullable()->constrained();
            $table->foreignId('device_id')->constrained();
            $table->foreignId('trip_id')->nullable()->constrained();
            $table->foreignId('warning_id')->nullable()->constrained();

            $table->string('filename');
            $table->string('storage_path');
            $table->string('storage_disk')->default('local');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->unsignedInteger('duration')->nullable(); // in seconds

            $table->decimal('start_latitude', 10, 7)->nullable();
            $table->decimal('start_longitude', 10, 7)->nullable();
            $table->decimal('end_latitude', 10, 7)->nullable();
            $table->decimal('end_longitude', 10, 7)->nullable();

            $table->string('status')->default('pending'); // pending, processing, ready, failed
            $table->text('processing_error')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_recordings');
    }
};
