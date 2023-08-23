<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const TABLE = 'detailed_score';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->foreignId('event_id')->constrained('events');
                $table->foreignId('user_id')->constrained('users');
                $table->foreignId('presentation_id')->nullable()->constrained('presentations');
                $table->foreignId('answer_id')->nullable()->constrained('answers');
                $table->foreignId('question_id')->nullable()->constrained('questions');
                $table->foreignId('option_id')->nullable()->constrained('options');
                $table->string('description', 500);
                $table->timestamp('created_at')->useCurrent();
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
