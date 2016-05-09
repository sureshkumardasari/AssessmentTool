<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('institution', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('address1', 50);
			$table->string('address2', 50);
			$table->string('address3', 50);
			$table->string('state', 50);
			$table->string('city', 50);
			$table->string('country', 50);
			$table->integer('parent_id')->nullable();
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
		Schema::drop('institution');
	}

}
