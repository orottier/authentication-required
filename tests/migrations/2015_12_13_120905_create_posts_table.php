<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->boolean('hidden')->default(false);
            $table->datetime('published_at');
            $table->string('title');
            $table->text('contents');
        });
    }

    public function down()
    {
        Schema::drop('posts');
    }
}
