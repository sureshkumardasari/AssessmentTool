<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Templates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('templates', function(Blueprint $table)
		{
			//
			$table->increments('id');
			$table->text('header')->nullable();
			$table->text('footer')->nullable();
			$table->integer('assessment_id')->nullable();
			$table->text('pdf_content')->nullable();
			$table->string('type', 250)->nullable();
			$table->enum('changed', ['NO', 'YES']);
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
		//
		Schema::drop('templates', function(Blueprint $table)
		{
			//
		});
	}

}
