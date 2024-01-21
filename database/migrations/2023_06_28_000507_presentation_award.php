<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const TABLE = 'presentation_awards';

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
                $table->foreignId('user_id')->constrained('users');
                $table->timestamp('created_at')->useCurrent();
            });
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'              => 1,
                    'event_id'        => 1,
                    'presentation_id' => 1,
                    'user_id'         => 2,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable(self::TABLE)) {
            Schema::drop(self::TABLE);
        }
    }
};
