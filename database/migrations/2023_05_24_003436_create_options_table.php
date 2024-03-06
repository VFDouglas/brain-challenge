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
                    'presentation_id' => 1,
                    'question_id'     => 1,
                    'description'     => '1',
                    'correct'         => 0,
                ]);
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'presentation_id' => 1,
                    'question_id'     => 1,
                    'description'     => '3',
                    'correct'         => 1,
                ]);
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'presentation_id' => 1,
                    'question_id'     => 1,
                    'description'     => '5',
                    'correct'         => 0,
                ]);
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'presentation_id' => 2,
                    'question_id'     => 2,
                    'description'     => 'Lobo',
                    'correct'         => 0,
                ]);
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'presentation_id' => 2,
                    'question_id'     => 2,
                    'description'     => 'Cachorro',
                    'correct'         => 1,
                ]);
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'presentation_id' => 2,
                    'question_id'     => 2,
                    'description'     => 'Leão',
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
