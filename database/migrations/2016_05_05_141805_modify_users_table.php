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
			$table->string('first_name', 250);
			$table->string('last_name', 250);

			$table->integer('institution_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->string('enrollno', 30)->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->nullable();
			$table->enum('gender', ['Male', 'Female']);
            $table->string('address1', 250);
			$table->string('address2', 250);
			$table->string('address3', 250);
			$table->string('city', 250);
			$table->string('state', 250);
			$table->string('phoneno', 250);
			$table->string('pincode', 10);
			$table->integer('country_id')->nullable();
			$table->string('pic_coords', 50);
			$table->string('profile_picture', 250);
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

			$table->dropColumn('address1');
			$table->dropColumn('address2');
			$table->dropColumn('address3');
			$table->dropColumn('city');
			$table->dropColumn('state');
			$table->dropColumn('phoneno');
			$table->dropColumn('pincode');
			$table->dropColumn('country_id');
			$table->dropColumn('pic_coords');
			$table->dropColumn('profile_picture');
		});
	}

}
