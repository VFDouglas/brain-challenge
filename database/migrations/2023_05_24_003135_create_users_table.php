<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const TABLE = 'users';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->nullable()->constrained('events');
                $table->string('name', 50)->nullable(false);
                $table->string('email', 50);
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->boolean('accepted_terms')->default(0);
                $table->boolean('status')->default(1);
                $table
                    ->enum('role', ['A', 'P', 'S'])
                    ->comment('A = Admin, P = Professor, S = Student')
                    ->default('S');
                $table->rememberToken();
                $table->timestamps();
                $table->unique(['event_id', 'email'], 'users_email_unique');
            });
            // Example data
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'       => 1,
                    'event_id' => 1,
                    'name'     => 'Lucas Ferreira',
                    'email'    => 'admin@admin.com',
                    'role'     => 'A',
                    'password' => Hash::make('admin')
                ]);
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'       => 2,
                    'event_id' => 1,
                    'name'     => 'Douglas Vicentini',
                    'email'    => 'student@student.com',
                    'role'     => 'S',
                    'password' => Hash::make('student')
                ]);
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'       => 3,
                    'event_id' => 1,
                    'name'     => 'João da Silva',
                    'email'    => 'professor@professor.com',
                    'role'     => 'P',
                    'password' => Hash::make('professor')
                ]);
            DB::table(self::TABLE)
                ->insertOrIgnore([
                    'id'       => 4,
                    'event_id' => 1,
                    'name'     => 'Luiz Fernando',
                    'email'    => 'professor2@professor.com',
                    'role'     => 'P',
                    'password' => Hash::make('professor')
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
