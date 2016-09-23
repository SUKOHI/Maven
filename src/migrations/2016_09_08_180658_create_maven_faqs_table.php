<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMavenFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maven_faqs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('unique_key_id')
                ->references('id')
                ->on('maven_unique_keys')
                ->onDelete('cascade');
            $table->text('question');
            $table->text('answer');
            $table->string('locale');
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
        Schema::drop('maven_faqs');
    }
}
