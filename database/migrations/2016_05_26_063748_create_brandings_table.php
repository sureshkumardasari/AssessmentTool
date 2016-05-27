<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('brandings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('institution_id');
			$table->string('title');
			$table->string('filepath');
			$table->string('header_bg_color');
			$table->string('header_text_color');
			$table->string('box_header_bg_color');
			$table->string('box_header_text_color');
			$table->string('box_text_color');
			$table->string('button_color');
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
		Schema::drop('brandings');
	}

}
