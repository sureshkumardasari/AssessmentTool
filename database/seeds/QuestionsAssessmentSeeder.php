<?php
use Illuminate\Database\Seeder;

class QuestionsAssessmentSeeder extends Seeder {

	public function run()
	{
		DB::table('questions')->insert([
			'id' => '1', 
			'title' => 'Question 1', 'qst_text' => 'A sum of money at simple interest amounts to Rs. 815 in 3 years and to Rs. 854 in 4 years. The sum is:',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '1', 'ans_text' => 'Rs. 650',
			'question_id' => '1',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '2', 'ans_text' => 'Rs. 698',
			'question_id' => '1',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 2
		DB::table('questions')->insert([
			'id' => '2', 
			'title' => 'Question 2', 'qst_text' => 'What percentage of numbers from 1 to 70 have 1 or 9 in the units digit?',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '3', 'ans_text' => '1',
			'question_id' => '2',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '4', 'ans_text' => '20',
			'question_id' => '2',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 3
		DB::table('questions')->insert([
			'id' => '3', 
			'title' => 'Question 3', 'qst_text' => 'If A = x% of y and B = y% of x, then which of the following is true?',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '5', 'ans_text' => 'A is smaller than B.',
			'question_id' => '3',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '6', 'ans_text' => 'A is greater than B',
			'question_id' => '3',
			'is_correct' => 'NO', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '7', 'ans_text' => 'None of these',
			'question_id' => '3',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 4
		DB::table('questions')->insert([
			'id' => '4', 
			'title' => 'Question 4', 'qst_text' => 'It was Sunday on Jan 1, 2006. What was the day of the week Jan 1, 2010?',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '8', 'ans_text' => 'Sunday',
			'question_id' => '4',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '9', 'ans_text' => 'Friday',
			'question_id' => '4',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 5
		DB::table('questions')->insert([
			'id' => '5', 
			'title' => 'Question 5', 'qst_text' => 'A clock is started at noon. By 10 minutes past 5, the hour hand has turned through:',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '10', 'ans_text' => '145º',
			'question_id' => '5',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '11', 'ans_text' => '155º',
			'question_id' => '5',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 6
		DB::table('questions')->insert([
			'id' => '6', 
			'title' => 'Question 6', 'qst_text' => 'Is PHP a case sensitive language?',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '12', 'ans_text' => 'Yes',
			'question_id' => '6',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '13', 'ans_text' => 'No',
			'question_id' => '6',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 7
		DB::table('questions')->insert([
			'id' => '7', 
			'title' => 'Question 7', 'qst_text' => 'A person crosses a 600 m long street in 5 minutes. What is his speed in km per hour?',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '14', 'ans_text' => '3.6',
			'question_id' => '7',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '15', 'ans_text' => '7.2',
			'question_id' => '7',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 8
		DB::table('questions')->insert([
			'id' => '8', 
			'title' => 'Question 8', 'qst_text' => 'The percentage increase in the area of a rectangle, if each of its sides is increased by 20% is:',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '16', 'ans_text' => '42%',
			'question_id' => '8',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '17', 'ans_text' => '44%',
			'question_id' => '8',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 9
		DB::table('questions')->insert([
			'id' => '9', 
			'title' => 'Question 9', 'qst_text' => 'What is the probability of getting a sum 9 from two throws of a dice?',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '18', 'ans_text' => '1/6',
			'question_id' => '9',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '19', 'ans_text' => '1/9',
			'question_id' => '9',
			'is_correct' => 'YES', 'order_id' => '2', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);

		//qst 10
		DB::table('questions')->insert([
			'id' => '10', 
			'title' => 'Question 10', 'qst_text' => 'Three unbiased coins are tossed. What is the probability of getting at most two heads?',
			'question_type_id' => '2', 'category_id' => '1', 'subject_id' => '1', 'lesson_id' => '1', 'institute_id' => '1', 
			'created_at' => new DateTime, 'updated_at' => new DateTime, 'added_by' => '1', 'updated_by' => '1', ]);
		DB::table('question_answers')->insert([
			'id' => '20', 'ans_text' => '3/8',
			'question_id' => '10',
			'is_correct' => 'NO', 'order_id' => '1', 'created_at' => new DateTime, 'updated_at' => new DateTime,]);
		DB::table('question_answers')->insert([
			'id' => '21', 'ans_text' => '7/8',
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