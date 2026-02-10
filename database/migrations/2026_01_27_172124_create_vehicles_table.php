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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            $table->string('plate');
            $table->string('model');
            $table->year('year');
            $table->enum('type', ['truck', 'van', 'car', 'pickup']);

            $table->enum('status', ['active', 'maintenance', 'blocked'])->default('active');

            $table->foreignId('current_driver_id')->nullable()->constrained('drivers')->nullOnDelete();

            $table->integer('current_speed')->nullable();
            $table->integer('fuel_level')->nullable(); // %

            $table->boolean('ignition_on')->default(false);

            $table->decimal('last_latitude', 10, 7)->nullable();
            $table->decimal('last_longitude', 10, 7)->nullable();

            $table->timestamp('last_update_at')->nullable();

            $table->foreignId('company_id')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
