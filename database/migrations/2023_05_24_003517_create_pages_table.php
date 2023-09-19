<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->string('name', 30)->nullable(false)->unique();
                $table->string('url', 50)->nullable(false)->unique();
                $table->boolean('status')->nullable(false);
                $table->timestamps();
            });
            DB::table('pages')
                ->upsert(
                    [
                        ['name' => 'Schedules', 'url' => '/schedules', 'status' => 1],
                        ['name' => 'Presentations', 'url' => '/presentations', 'status' => 1],
                        ['name' => 'Awards', 'url' => '/awards', 'status' => 1],
                        ['name' => 'Questions', 'url' => '/questions', 'status' => 1],
                        ['name' => 'QR Code', 'url' => '/qrcode', 'status' => 1],
                    ],
                    ['name'],
                    ['updated_at']
                );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
