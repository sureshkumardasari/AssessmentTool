<?php
use Illuminate\Database\Seeder;

class StatesSeeder extends Seeder {

	public function run()
	{
		DB::table('states')->delete();

		DB::table('states')->insert([
			            'country_id' => '1',

			'id' => '1',
			'state_name' =>'Andhra Pradhesh',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			
						
			]);
		DB::table('states')->insert([
			            'country_id' => '1',

			'id' => '2',
			'state_name' =>'Karnataka',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
						
			]);
		DB::table('states')->insert([
			            'country_id' => '1',

			'id' => '3',
			'state_name' =>'Telangana',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
						
			]);
		        DB::table('states')->insert([
            'country_id' => '1',
            'id' => '4',
            'state_name' =>'Tamil Nadu',
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
                        
            ]);
       



		
		
  	}

}