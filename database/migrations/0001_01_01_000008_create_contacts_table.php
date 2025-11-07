<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 191);
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender', ['male','female','other'])->nullable();
            $table->string('profile_image')->nullable();
            $table->string('additional_file')->nullable();
            $table->unsignedBigInteger('merged_into')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('merged_into')->references('id')->on('contacts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}

?>