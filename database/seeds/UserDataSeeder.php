<?php
use Illuminate\Database\Seeder;

class UserDataSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->insert([
			'id' => '1',
			'email' => 'admin@gmail.com',
			'name' => 'Admin',
			'password' => bcrypt('abc123'),
			'status' => 'Yes',
			'role_id' => '1',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'institution_id' => '1',
			'added_by' => '1',
			'updated_by' => '1',
   		]);

		DB::table('roles')->insert([
			'id' => '1',
			'name' => 'admin',
			'display_name' => 'Admin',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
		]);
		DB::table('role_user')->insert([
			'user_id' => '1',
			'role_id' => '1',
		]);
		DB::table('institution')->insert([
			'id' => '1',
			'name' => 'Institution1',
			'parent_id' => '0',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'added_by' => '1',
			'updated_by' => '1',
		]);
  	}

}