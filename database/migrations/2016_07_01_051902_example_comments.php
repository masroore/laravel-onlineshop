<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExampleComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('example_comments', function(Blueprint $table){
            $table->increments('id');
            $table->integer('on_post')->unsigned()->default(0);
            $table->foreign('on_post')->references('id')->on('example_posts')->onDelete('cascade');
            $table->integer('from_user')->unsigned()->default(0);
            $table->foreign('from_user')->references('id')->on('users')->onDelete('cascade');
            $table->text('body');
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
        Schema::drop('comments');
    }
}
