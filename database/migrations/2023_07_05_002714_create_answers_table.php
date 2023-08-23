<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const TABLE = 'answers';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events');
                $table->foreignId('presentation_id')->constrained('presentations');
                $table->foreignId('question_id')->constrained('questions');
                $table->foreignId('user_id')->constrained('users');
                $table->foreignId('option_id')->constrained('options');
                $table->timestamp('created_at')->useCurrent();
                $table->unique(['event_id', 'presentation_id', 'question_id', 'user_id'], 'idx_answers');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE);
    }
};
