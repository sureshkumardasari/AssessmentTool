<?php
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder {

	public function run()
	{
		DB::table('question_type')->insert([
			'id' => '1',
			'qst_type_text' => 'Multiple Choice - Multi Answer',
			'created_at' => new DateTime,
			'updated_at' => new DateTime
   		]);
		DB::table('question_type')->insert([
			'id' => '2',
			'qst_type_text' => 'Multiple Choice - Single Answer',
			'created_at' => new DateTime,
			'updated_at' => new DateTime
   		]);
   		DB::table('question_type')->insert([
			'id' => '3',
			'qst_type_text' => 'Essay',
			'created_at' => new DateTime,
			'updated_at' => new DateTime
   		]);

  	}

}