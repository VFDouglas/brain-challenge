<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Checking if database exists
        $query = "
            select schema_name
              from information_schema.schemata
             where schema_name = 'brain_challenge'
        ";
        $db    = DB::select($query);
        if (empty($db)) {
            Schema::createDatabase('brain_challenge');
        }
    }
};
