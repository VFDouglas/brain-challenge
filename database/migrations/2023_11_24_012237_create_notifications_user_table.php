<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const TABLE = 'notification_user';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->foreignId('notification_id')->constrained('notifications');
                $table->foreignId('event_id')->constrained('events');
                $table->foreignId('user_id')->constrained('users');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('read_at')->nullable();
            });
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'notification_id' => 1,
                    'event_id'        => 1,
                    'user_id'         => 2,
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
