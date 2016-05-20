<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assignment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 250)->unique();
			$table->text('description');
			$table->integer('assessment_id')->nullable();
			$table->integer('institution_id')->nullable();
			$table->dateTime('startdatetime');
			$table->dateTime('enddatetime');
			$table->enum('neverexpires', ['0','1']);
			$table->enum('launchtype', ['proctor','system']);
			$table->integer('proctor_user_id')->nullable();
			$table->text('proctor_instructions');
			$table->enum('delivery_method', ['online','print']);
			$table->integer('added_by')->nullable();
			$table->integer('updated_by')->nullable();
			$table->enum('status', ['0','1']);
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
		Schema::drop('assignment');
	}

}
