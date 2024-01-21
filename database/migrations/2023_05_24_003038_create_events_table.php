<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public const TABLE = 'events';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->id();
                $table->string('name', 50)->nullable(false);
                $table->string('location', 30)->nullable(false)->comment('Place where the event is gonna occur');
                $table->dateTime('starts_at')->nullable(false);
                $table->dateTime('ends_at')->nullable(false);
                $table->boolean('status')
                    ->nullable(false)
                    ->comment('Determines if the event is active or not')
                    ->default(1);
                $table->timestamps();
            });
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'        => 1,
                    'name'      => 'First Event',
                    'location'  => 'Brasília',
                    'starts_at' => now(),
                    'ends_at'   => date('Y-m-d H:i:s', strtotime('+1 month')),
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
