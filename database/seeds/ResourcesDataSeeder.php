<?php
use Illuminate\Database\Seeder;

class ResourcesDataSeeder extends Seeder {

	public function run()
	{
		DB::table('category')->insert([[
			'id' => '1',			
			'name' => 'category1',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'institution_id' => '1',
			'added_by' => '1',
			'updated_by' => '1',
   		],
   		[
			'id' => '2',			
			'name' => 'category2',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'institution_id' => '1',
			'added_by' => '1',
			'updated_by' => '1',
   		]
   		]);

		DB::table('subject')->insert([[
			'id' => '1',
			'name' => 'subject1',
			'institution_id' => '1',
			'category_id' => '1',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'added_by' => '1',
			'updated_by' => '1',
		],
		[
			'id' => '2',
			'name' => 'subject2',
			'institution_id' => '1',
			'category_id' => '1',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'added_by' => '1',
			'updated_by' => '1',
		],
		[
			'id' => '3',
			'name' => 'subject3',
			'institution_id' => '1',
			'category_id' => '2',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'added_by' => '1',
			'updated_by' => '1',
		]]);

		DB::table('lesson')->insert([[
			'id' => '1',
			'name' => 'lesson1',
			'institution_id' => '1',
			'category_id' => '1',
			'subject_id' => '1',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'added_by' => '1',
			'updated_by' => '1',
		],
		[
			'id' => '2',
			'name' => 'lesson2',
			'institution_id' => '1',
			'category_id' => '1',
			'subject_id' => '2',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'added_by' => '1',
			'updated_by' => '1',
		],
		[
			'id' => '3',
			'name' => 'lesson3',
			'institution_id' => '1',
			'category_id' => '2',
			'subject_id' => '2',
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'added_by' => '1',
			'updated_by' => '1',
		]]);
  	}

}