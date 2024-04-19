<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizUserSeeder extends Seeder
{
	public function run(): void
	{
		$quizzes = Quiz::factory(10)->withQuestionsAndAnswers()->create();

		$quizUserRecords = [];

		$quizzes->each(function ($quiz) use (&$quizUserRecords) {
			$numberOfUsers = rand(0, 50);

			for ($i = 0; $i < $numberOfUsers; $i++) {
				$quizUserRecords[] = [
					'quiz_id'      => $quiz->id,
					'user_id'      => null,
					'total_time'   => null,
					'total_points' => null,
				];
			}
		});

		DB::table('quiz_user')->insert($quizUserRecords);
	}
}
