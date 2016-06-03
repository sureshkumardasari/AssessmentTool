<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAssignmentResultRetake extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_assignment_result_retake', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('assessment_id');
			$table->integer('assignment_id');
			$table->integer('user_id');
			$table->decimal('score',19,2);
			$table->decimal('percentage',19,2);
			$table->decimal('rawscore',19,2);
			$table->decimal('grade',19,2);
			$table->string('scoretype',255);
			$table->decimal('percentile',19,2);
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
		Schema::drop('user_assignment_result_retake');
	}

}
