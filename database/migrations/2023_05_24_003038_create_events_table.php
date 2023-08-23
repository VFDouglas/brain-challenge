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
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50)->nullable(false);
                $table->string('location', 30)->nullable(false)->comment('Place where the event is gonna occur');
                $table->dateTime('starts_at')->nullable(false);
                $table->dateTime('ends_at')->nullable(false);
                $table->boolean('status')->nullable(false)->comment('Determines if the event is active or not');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
