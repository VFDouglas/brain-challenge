<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const TABLE = 'notifications';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events');
                $table->string('title', 50);
                $table->longText('description');
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'          => 1,
                    'event_id'    => 1,
                    'title'       => 'Notification Example Title',
                    'description' => 'Notification Example Description',
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
