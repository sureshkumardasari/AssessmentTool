<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuestionAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('question_answers', function(Blueprint $table)
		{
			//
			$table->increments('id');
			$table->text('ans_text')->nullable();
			$table->integer('question_id')->nullable();
			$table->enum('is_correct', ['0', '1']);
			$table->integer('order_id')->nullable();
			$table->text('explanation')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('question_answers', function(Blueprint $table)
		{
			//
		});
	}

}
