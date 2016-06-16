<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assignment_user', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('assessment_id')->nullable();
			$table->integer('assignment_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->enum('status', ['upcoming', 'inprogress', 'test', 'completed','instructions'])->default('upcoming');
			$table->enum('gradestatus', ['notstarted', 'inprogress', 'completed','archieve','processed'])->default('notstarted');

			$table->integer('grader_id')->nullable();
			$table->decimal('score',19,2);
			$table->decimal('percentage',19,2);
			$table->decimal('rawscore',19,2);
			$table->decimal('grade',19,2);
			$table->string('scoretype',255);
			$table->decimal('percentile',19,2);

			$table->boolean('isgraded')->default(false);
			$table->dateTime('starttime')->nullable();
			$table->string('gradeprogress',255);

			$table->dateTime('takendate');
			$table->dateTime('gradeddate');
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
		Schema::drop('assignment_user');
	}

}
