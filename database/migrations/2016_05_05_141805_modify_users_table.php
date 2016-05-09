<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			//
			$table->integer('institution_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->string('enrollno', 30)->nullable();
            $table->enum('status', ['Yes', 'No'])->default('Yes')->nullable();
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
		Schema::table('users', function(Blueprint $table)
		{
			//
			$table->dropColumn('institution_id');
			$table->dropColumn('role_id');
			$table->dropColumn('status');
			$table->dropColumn('enrollno');
			$table->dropColumn('added_by');
			$table->dropColumn('updated_by');
		});
	}

}
