<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionUserAnswerRetake extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('question_user_answer_retake', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('question_id');
			$table->integer('question_answer_id');
			$table->text('question_answer_text');
			$table->string('answer_option',10);
			$table->integer('user_id');
			$table->integer('assignment_id');
			$table->integer('points');
			$table->enum('is_correct', ['0', '1']);
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
		Schema::drop('question_user_answer_retake');
	}

}
