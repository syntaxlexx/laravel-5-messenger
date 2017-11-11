<?php

use Lexx\ChatMessenger\Models\Models;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Models::table('threads'), function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('starred')->default(0);
            $table->string('subject');
            $table->string('slug')->nullable()->comment('Unique slug for social media sharing. MD5 hashed string');
            $table->integer('max_participants')->nullable()->comment('Max number of participants allowed');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('avatar')->nullable()->comment('Profile picture for the conversation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Models::table('threads'));
    }
}
