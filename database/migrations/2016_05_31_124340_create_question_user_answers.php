<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionUserAnswers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('question_user_answer', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('assignment_id');
			$table->integer('assessment_id');
			$table->integer('question_id');
			$table->integer('question_answer_id');
			$table->text('question_answer_text');
			$table->string('answer_option',10);
			$table->integer('user_id');
			$table->integer('points');
			$table->string('is_correct', 10);
			$table->string('original_answer_value',255);
			$table->integer('added_by')->nullable();
			$table->integer('updated_by')->nullable();
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
		Schema::drop('question_user_answer');
	}

}
