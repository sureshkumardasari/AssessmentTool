<?php
use Illuminate\Database\Seeder;

class StatesSeeder extends Seeder {

	public function run()
	{
		DB::table('states')->insert([
			'id' => '1',
			'state_name' =>'Andhra Pradhesh',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			
						
			]);
		DB::table('states')->insert([
			'id' => '2',
			'state_name' =>'Karnataka',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
						
			]);
		DB::table('states')->insert([
			'id' => '3',
			'state_name' =>'Telangana',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
						
			]);

		
		
  	}

}