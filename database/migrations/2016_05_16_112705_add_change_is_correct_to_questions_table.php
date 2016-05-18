<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangeIsCorrectToQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
 		DB::statement("ALTER TABLE question_answers CHANGE COLUMN is_correct is_correct ENUM('YES', 'NO')");
  	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE question_answers CHANGE COLUMN is_correct is_correct ENUM('YES', 'NO')");

	}
}
