<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public const TABLE = 'page_user';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->foreignId('event_id')->constrained('events');
                $table->foreignId('user_id')->constrained('users');
                $table->foreignId('page_id')->constrained('pages');
                $table->timestamp('created_at')->useCurrent();
            });
            // Creating test data for the student to access the pages
            DB::table(self::TABLE)
                ->upsert(
                    [
                        ['event_id' => 1, 'user_id' => 2, 'page_id' => 1],
                        ['event_id' => 1, 'user_id' => 2, 'page_id' => 2],
                        ['event_id' => 1, 'user_id' => 2, 'page_id' => 3],
                        ['event_id' => 1, 'user_id' => 2, 'page_id' => 4],
                        ['event_id' => 1, 'user_id' => 2, 'page_id' => 5],
                    ],
                    ['event_id', 'user_id', 'page_id']
                );
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
