<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PassageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('passage', function(Blueprint $table)
		{
			//
			$table->increments('id');
			$table->string('title', 250);
			$table->text('passage_text');
			$table->text('passage_lines');
			$table->integer('subject_id')->nullable();
			$table->integer('lesson_id')->nullable();
			$table->integer('institute_id')->nullable();
			$table->enum('status', ['0', '1']);
			$table->timestamps();
			$table->integer('added_by')->nullable();
			$table->integer('updated_by')->nullable();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('passage', function(Blueprint $table)
		{
			//
		});
	}

}
