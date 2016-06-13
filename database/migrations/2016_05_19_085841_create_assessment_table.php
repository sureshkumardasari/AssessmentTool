<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assessment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 250)->unique();
			$table->text('description');

			$table->integer('institution_id')->nullable();
			$table->integer('category_id')->nullable();
			$table->integer('subject_id')->nullable();
			$table->integer('questiontype_id')->nullable();

			$table->integer('totaltime')->nullable();
			$table->enum('unlimitedtime', ['0','1']);
			$table->enum('guessing_panality',['0','0.25'])->default('0');

			$table->decimal('mcsingleanswerpoint', 5, 2)->default('1');
			$table->decimal('essayanswerpoint', 5, 2)->default('1');
			$table->text('header');
			$table->text('footer');
			$table->text('titlepage');
			$table->text('direction');
			$table->text('begin_instruction');
			$table->text('end_instruction');
			$table->text('print_view_file');
			$table->enum('status', ['active', 'inactive']);

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
		Schema::drop('assessment');
	}

}
