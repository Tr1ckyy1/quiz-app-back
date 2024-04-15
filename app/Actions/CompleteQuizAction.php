<?php

namespace App\Actions;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\DB;

class CompleteQuizAction
{
	public function execute($quiz, $questionValues, $totalTime, $userId)
	{
		$userCompletedQuiz = $quiz->users()->where('user_id', $userId)->exists();

		if ($userCompletedQuiz) {
			return ['error' => 'You have already completed the quiz'];
		}

		$questionIds = array_keys($questionValues);
		$questions = Question::whereIn('id', $questionIds)->get();

		$correctAnswerIds = Answer::where('correct', true)
			->whereIn('question_id', $questionIds)
			->get()
			->groupBy('question_id')
			->map(function ($answers) {
				return $answers->pluck('id')->toArray();
			})
			->toArray();

		$totalPoints = 0;
		$correctQuestions = 0;
		$incorrectQuestions = 0;

		$remainingTime = ($quiz->duration * 60) - $totalTime;
		$timeTakenMinutes = floor($remainingTime / 60);
		$timeTakenSeconds = $remainingTime % 60;

		$timeTakenMinutesDatabase = ceil($remainingTime / 60);

		$timeTaken = sprintf('%02d:%02d', $timeTakenMinutes, $timeTakenSeconds);

		foreach ($questions as $question) {
			$questionPoints = $question->points;

			$providedAnswerIds = $questionValues[$question->id] ?? [];

			// Check if all correct answer IDs are selected by the user and if there are no extra answer IDs selected
			$correctlySelected = empty(array_diff($correctAnswerIds[$question->id], $providedAnswerIds)) &&
				empty(array_diff($providedAnswerIds, $correctAnswerIds[$question->id]));

			if ($correctlySelected && count($providedAnswerIds) === count($correctAnswerIds[$question->id])) {
				$correctQuestions++;
				$totalPoints += $questionPoints;
			} else {
				$incorrectQuestions++;
			}
		}

		$timeTakenMinutesDatabase = $userId ? $timeTakenMinutesDatabase : null;
		$totalPoints = $userId ? $totalPoints : null;

		DB::transaction(function () use ($quiz, $userId, $timeTakenMinutesDatabase, $totalPoints) {
			$quiz->users()->attach([$userId], [
				'total_time'   => $timeTakenMinutesDatabase,
				'total_points' => $totalPoints,
				'created_at'   => now(),
			]);
		});

		return [
			'quiz_name'           => $quiz->title,
			'correct_questions'   => $correctQuestions,
			'incorrect_questions' => $incorrectQuestions,
			'time'                => $timeTaken,
			'difficulty_level'    => $quiz->difficultyLevel,
		];
	}
}
