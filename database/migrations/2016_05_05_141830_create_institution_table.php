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
			$table->string('name', 250)->unique();
			$table->string('address1', 250);
			$table->string('address2', 250);
			$table->string('address3', 250);
			$table->string('city', 250);
			$table->string('state', 250);
			$table->string('phoneno', 250);
			$table->string('pincode', 10);
			$table->integer('country_id')->nullable();
			$table->integer('parent_id')->nullable();
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
		Schema::drop('institution');
	}

}
