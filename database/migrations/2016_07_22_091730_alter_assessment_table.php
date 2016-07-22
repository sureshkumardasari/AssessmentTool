<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAssessmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		DB::statement('ALTER TABLE assessment MODIFY COLUMN subject_id TEXT');
		Schema::table('assessment', function(Blueprint $table)
		{

			$table->text('lesson_id','')->after('subject_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::table('assessment', function(Blueprint $table)
		{
			//
		});
	}

}
