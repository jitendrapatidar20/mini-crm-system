<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('block_users', function (Blueprint $table) {
            $table->boolean('permanent_block')
                  ->default(false)
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('block_users', function (Blueprint $table) {
            $table->enum('permanent_block', ['0', '1'])
                  ->default('0')
                  ->change();
        });
    }
};
