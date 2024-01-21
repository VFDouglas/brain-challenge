<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public const TABLE = 'questions';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->id();
                $table->foreignId('presentation_id')->constrained('presentations');
                $table->string('description', 500)->nullable(false);
                $table->unsignedInteger('points')->nullable(false)->comment('Points achieved for a correct answer');
                $table->boolean('bonus')->default(0)->comment('Determines if the question gives extra points');
                $table->timestamps();
            });
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'              => 1,
                    'presentation_id' => 1,
                    'description'     => 'What is the capital of Spain?',
                    'points'          => 10,
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
