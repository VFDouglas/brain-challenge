<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public const TABLE = 'schedules';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events');
                $table->string('title', 50)->nullable(false);
                $table->string('description', 100)->nullable(false);
                $table->dateTime('starts_at')->nullable(false);
                $table->dateTime('ends_at')->nullable(false);
                $table->timestamp('created_at')->useCurrent();
                $table->unique(['event_id', 'title'], 'schedules_event_title');
            });
            DB::table(self::TABLE)
                ->upsert(
                    [
                        [
                            'event_id'    => 1,
                            'title'       => 'Apresentação da feira',
                            'description' => 'Apresentação feita pelo diretor da escola no início do evento',
                            'starts_at'   => now(),
                            'ends_at'     => date('Y-m-d H:i:s', strtotime('+1 month'))
                        ],
                        [
                            'event_id'    => 1,
                            'title'       => 'Feira com professores',
                            'description' => 'Os alunos irão visitar os estandes dos professores e irão se apresentar',
                            'starts_at'   => now(),
                            'ends_at'     => date('Y-m-d H:i:s', strtotime('+1 month'))
                        ],
                        [
                            'event_id'    => 1,
                            'title'       => 'Apresentação do segundo dia',
                            'description' => 'Apresentação feita pelo diretor da escola no início do segundo dia',
                            'starts_at'   => date('Y-m-d H:i:s', strtotime('+1 day')),
                            'ends_at'     => date('Y-m-d H:i:s', strtotime('+1 month'))
                        ],
                        [
                            'event_id'    => 1,
                            'title'       => 'Feira com professores do segundo dia',
                            'description' => 'Os alunos irão visitar os estandes dos professores e irão se apresentar',
                            'starts_at'   => date('Y-m-d H:i:s', strtotime('+1 day')),
                            'ends_at'     => date('Y-m-d H:i:s', strtotime('+1 month'))
                        ]
                    ],
                    ['id']
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
