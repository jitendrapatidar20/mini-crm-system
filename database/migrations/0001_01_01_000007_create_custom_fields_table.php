<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldsTable extends Migration
{
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 191);
            $table->string('type')->default('text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_fields');
    }
}
