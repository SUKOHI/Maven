<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMavenUniqueKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maven_unique_keys', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('sort');
            $table->string('unique_key');
            $table->boolean('draft_flag');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('maven_unique_keys');
    }
}
