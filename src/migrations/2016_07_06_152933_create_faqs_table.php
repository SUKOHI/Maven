<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('faqs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('question');
			$table->text('answer');
			$table->integer('sort');
			$table->text('tags');   // for JSON
			$table->string('locale');
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
		Schema::drop('faqs');
    }
}
