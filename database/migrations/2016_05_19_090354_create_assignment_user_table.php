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
			$table->enum('gradestatus', ['notstarted', 'inprogress', 'completed','archieve'])->default('notstarted');
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
