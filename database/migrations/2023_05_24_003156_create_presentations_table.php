<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public const TABLE = 'presentations';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events');
                $table->string('name', 50)->comment('Presentation name');
                $table->foreignId('user_id')
                    ->comment('Professor id')
                    ->constrained('users');
                $table->string('qrcode')->comment('QR Code for the presentation')->default(Str::random(8));
                $table->dateTime('starts_at')->nullable(false);
                $table->dateTime('ends_at')->nullable(false);
                $table->boolean('confirmed')->comment('Determines if the professor confirmed presence')->default(false);
                $table->dateTime('confirmed_at')->nullable();
                $table->boolean('award_indicator')->default(0)
                    ->comment('Determines if the professor is gonna give some kind of award to some students');
                $table->string('award', 50)->nullable();
                $table->boolean('status')->default(1)->comment('Determines if the presentation is active or not');
                $table->timestamps();
                $table->index(['event_id', 'user_id'], 'idx_presentation_event_user');
            });
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'        => 1,
                    'event_id'  => 1,
                    'user_id'   => 3,
                    'name'      => 'Matemática',
                    'qrcode'    => '1234',
                    'confirmed' => 1,
                    'status'    => 1,
                    'starts_at' => now(),
                    'ends_at'   => date('Y-m-d H:i:s', strtotime('+1 month')),
                ]);
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'        => 2,
                    'event_id'  => 1,
                    'user_id'   => 4,
                    'name'      => 'Ciências',
                    'qrcode'    => '5678',
                    'confirmed' => 1,
                    'status'    => 1,
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
