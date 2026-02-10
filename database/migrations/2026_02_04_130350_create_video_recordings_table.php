<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trip_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('warning_id')->nullable()->constrained()->nullOnDelete();

            // Video file information
            $table->string('filename');
            $table->string('storage_path');
            $table->string('storage_disk')->default('minio');
            $table->enum('status', ['uploading', 'processing', 'ready', 'failed'])->default('uploading')->after('warning_id');
            $table->text('processing_error')->nullable()->after('status');
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->integer('duration')->nullable(); // in seconds

            // Location data
            $table->decimal('start_latitude', 10, 7)->nullable();
            $table->decimal('start_longitude', 10, 7)->nullable();
            $table->decimal('end_latitude', 10, 7)->nullable();
            $table->decimal('end_longitude', 10, 7)->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['vehicle_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_recordings');
    }
};
