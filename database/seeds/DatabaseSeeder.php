<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('UserDataSeeder');
		$this->call('QuestionTypeSeeder');
		//$this->call('ResourcesDataSeeder');
		$this->call('StatesSeeder');
		//$this->call('QuestionsAssessmentSeeder');
        $this->call('DashboardWidgetsSeeder');

	}

}
