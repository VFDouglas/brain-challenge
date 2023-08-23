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
        if (!Schema::hasTable('page_user')) {
            Schema::create('page_user', function (Blueprint $table) {
                $table->foreignId('event_id')->constrained('events');
                $table->foreignId('user_id')->constrained('users');
                $table->foreignId('page_id')->constrained('pages');
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_user');
    }
};
