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
        if (!Schema::hasTable('parameters')) {
            Schema::create('parameters', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50)->nullable(false);
                $table->string('value', 100)->nullable(false);
                $table->integer('start_limit')->nullable(false);
                $table->integer('end_limit')->nullable(false);
                $table->string('description', 100)->nullable(false);
                $table->string('text_type', 100)->nullable(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameters');
    }
};
