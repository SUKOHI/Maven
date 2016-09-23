<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMavenTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maven_tags', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('unique_key_id')
                ->references('id')
                ->on('maven_unique_keys')
                ->onDelete('cascade');
            $table->integer('faq_id')
                ->references('id')
                ->on('maven_faqs')
                ->onDelete('cascade');
            $table->string('tag');
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
        Schema::drop('maven_tags');
    }
}
