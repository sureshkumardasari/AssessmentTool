<?php
use Illuminate\Database\Seeder;

class QuestionsAssessmentSeeder extends Seeder {

	public function run()
	{
		DB::table('questions')->insert([
			'id' => '1', 
			'title' => 'QST 1', 'qst_text' => 'QST TEXT 1',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '1', 'ans_text' => 'QST 1 ANS 1',
			'question_id' => '1',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '2', 'ans_text' => 'QST 1 ANS 2',
			'question_id' => '1',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 2
		DB::table('questions')->insert([
			'id' => '2', 
			'title' => 'QST 2', 'qst_text' => 'QST TEXT 2',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '3', 'ans_text' => 'QST 2 ANS 1',
			'question_id' => '2',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '4', 'ans_text' => 'QST 2 ANS 2',
			'question_id' => '2',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 3
		DB::table('questions')->insert([
			'id' => '3', 
			'title' => 'QST 3', 'qst_text' => 'QST TEXT 3',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '5', 'ans_text' => 'QST 3 ANS 1',
			'question_id' => '3',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '6', 'ans_text' => 'QST 3 ANS 2',
			'question_id' => '3',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 4
		DB::table('questions')->insert([
			'id' => '4', 
			'title' => 'QST 4', 'qst_text' => 'QST TEXT 4',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '7', 'ans_text' => 'QST 4 ANS 1',
			'question_id' => '4',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '8', 'ans_text' => 'QST 4 ANS 2',
			'question_id' => '4',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 5
		DB::table('questions')->insert([
			'id' => '5', 
			'title' => 'QST 5', 'qst_text' => 'QST TEXT 5',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '9', 'ans_text' => 'QST 5 ANS 1',
			'question_id' => '5',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '10', 'ans_text' => 'QST 5 ANS 2',
			'question_id' => '5',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 6
		DB::table('questions')->insert([
			'id' => '6', 
			'title' => 'QST 6', 'qst_text' => 'QST TEXT 6',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '11', 'ans_text' => 'QST 6 ANS 1',
			'question_id' => '6',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '12', 'ans_text' => 'QST 6 ANS 2',
			'question_id' => '6',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 7
		DB::table('questions')->insert([
			'id' => '7', 
			'title' => 'QST 7', 'qst_text' => 'QST TEXT 7',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '13', 'ans_text' => 'QST 7 ANS 1',
			'question_id' => '7',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '14', 'ans_text' => 'QST 7 ANS 2',
			'question_id' => '7',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 8
		DB::table('questions')->insert([
			'id' => '8', 
			'title' => 'QST 8', 'qst_text' => 'QST TEXT 8',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '15', 'ans_text' => 'QST 8 ANS 1',
			'question_id' => '8',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '16', 'ans_text' => 'QST 8 ANS 2',
			'question_id' => '8',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 9
		DB::table('questions')->insert([
			'id' => '9', 
			'title' => 'QST 9', 'qst_text' => 'QST TEXT 9',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '17', 'ans_text' => 'QST 9 ANS 1',
			'question_id' => '9',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '18', 'ans_text' => 'QST 9 ANS 2',
			'question_id' => '9',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 2
		DB::table('questions')->insert([
			'id' => '10', 
			'title' => 'QST 10', 'qst_text' => 'QST TEXT 10',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '19', 'ans_text' => 'QST 10 ANS 1',
			'question_id' => '10',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '20', 'ans_text' => 'QST 10 ANS 2',
			'question_id' => '10',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);


		// //assessment
		// DB::table('assessment')->insert([
		// 	'id' => '1', 'name' => 'TEST ASSESSMENT','created_at' => new DateTime, 'updated_at' => new DateTime,]);

		// DB::table('assessment_question')->insert([
		// 	'id' => '1', 'assessment_id' => '1', 'question_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		// DB::table('assessment_question')->insert([
		// 	'id' => '2', 'assessment_id' => '1', 'question_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		// DB::table('assessment_question')->insert([
		// 	'id' => '3', 'assessment_id' => '1', 'question_id' => '3', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		// DB::table('assessment_question')->insert([
		// 	'id' => '4', 'assessment_id' => '1', 'question_id' => '4', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		// DB::table('assessment_question')->insert([
		// 	'id' => '5', 'assessment_id' => '1', 'question_id' => '5', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		// DB::table('assessment_question')->insert([
		// 	'id' => '6', 'assessment_id' => '1', 'question_id' => '6', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		// DB::table('assessment_question')->insert([
		// 	'id' => '7', 'assessment_id' => '1', 'question_id' => '7', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		// DB::table('assessment_question')->insert([
		// 	'id' => '8', 'assessment_id' => '1', 'question_id' => '8', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		// DB::table('assessment_question')->insert([
		// 	'id' => '9', 'assessment_id' => '1', 'question_id' => '9', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		// DB::table('assessment_question')->insert([
		// 	'id' => '10', 'assessment_id' => '1', 'question_id' => '10', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		
  	}

}