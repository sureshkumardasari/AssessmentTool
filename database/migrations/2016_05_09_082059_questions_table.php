<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('questions', function(Blueprint $table)
		{
			//
			$table->increments('id');
			$table->string('title', 250)->unique();
			$table->text('qst_text');
			$table->integer('question_type_id')->nullable();
			$table->integer('subject_id')->nullable();
			$table->integer('lesson_id')->nullable();
			$table->integer('passage_id')->nullable();
			$table->integer('institute_id')->nullable();
			$table->enum('status', ['0', '1']);
			$table->integer('difficulty_id')->nullable();
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
		Schema::drop('questions', function(Blueprint $table)
		{
			//

		});
	}

}
