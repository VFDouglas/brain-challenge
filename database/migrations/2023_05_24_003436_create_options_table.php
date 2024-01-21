<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public const TABLE = 'options';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->id();
                $table->foreignId('presentation_id')->constrained('presentations');
                $table->foreignId('question_id')->constrained('questions');
                $table->string('description', 500)->nullable(false);
                $table->boolean('correct')->default(false);
                $table->timestamp('created_at')->useCurrent();
            });
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'              => 1,
                    'presentation_id' => 1,
                    'question_id'     => 1,
                    'description'     => 'Paris',
                    'correct'         => 0,
                ]);
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'              => 2,
                    'presentation_id' => 1,
                    'question_id'     => 1,
                    'description'     => 'Madrid',
                    'correct'         => 1,
                ]);
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'              => 3,
                    'presentation_id' => 1,
                    'question_id'     => 1,
                    'description'     => 'Barcelona',
                    'correct'         => 0,
                ]);
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
