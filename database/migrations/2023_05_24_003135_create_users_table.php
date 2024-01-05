<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
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
            DB::table('users')
                ->insertOrIgnore([
                    'name'     => 'Administrator',
                    'email'    => 'admin@admin.com',
                    'role'     => 'A',
                    'password' => Hash::make('adm123A*@') // Change the password ASAP
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
