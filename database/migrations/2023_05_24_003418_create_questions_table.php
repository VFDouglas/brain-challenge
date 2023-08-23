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
        if (!Schema::hasTable('questions')) {
            Schema::create('questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('presentation_id')->constrained('presentations');
                $table->string('description', 500)->nullable(false);
                $table->unsignedInteger('points')->nullable(false)->comment('Points achieved for a correct answer');
                $table->boolean('bonus')->default(0)->comment('Determines if the question gives extra points');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
