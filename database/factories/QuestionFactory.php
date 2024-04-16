<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
	protected $model = Question::class;

	public function definition()
	{
		return [
			'name'   => $this->faker->sentence,
			'points' => $this->faker->numberBetween(1, 10),
		];
	}

	public function configure()
	{
		return $this->afterCreating(function (Question $question) {
			$answers = Answer::factory(4)->create(['question_id' => $question->id]);
			$correctAnswer = $answers->random();
			$correctAnswer->update(['correct' => true]);
		});
	}
}
