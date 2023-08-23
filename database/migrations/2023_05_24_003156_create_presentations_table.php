<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('presentations')) {
            Schema::create('presentations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events');
                $table->string('name', 50)->comment('Presentation name');
                $table->foreignId('user_id')
                    ->comment('Professor id')
                    ->constrained('users');
                $table->string('qrcode')->comment('QR Code for the presentation')->default(Str::random(8));
                $table->dateTime('starts_at')->nullable(false);
                $table->dateTime('ends_at')->nullable(false);
                $table->boolean('confirmed')->comment('Determines if the professor confirmed presence');
                $table->dateTime('confirmed_at');
                $table->boolean('award_indicator')->default(0)
                    ->comment('Determines if the professor is gonna give some kind of award to some students');
                $table->string('award', 50);
                $table->boolean('status')->default(1)->comment('Determines if the presentation is active or not');
                $table->timestamps();
                $table->index(['event_id', 'user_id'], 'idx_presentation_event_user');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presentations');
    }
};
