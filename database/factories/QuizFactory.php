<?php

namespace Database\Factories;

use App\Models\DifficultyLevel;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$difficultyLevelIds = DifficultyLevel::pluck('id')->toArray();

		return [
			'title'               => fake()->unique()->words(3, true),
			'intro_question'      => fake()->sentence(),
			'instructions'        => fake()->paragraph(),
			'duration'            => fake()->numberBetween(1, 20),
			'difficulty_level_id' => fake()->randomElement($difficultyLevelIds),
			'image'               => fake()->image(dir: 'public/storage', fullPath:false),
		];
	}

	/**
	 * Indicate that the quiz should have associated questions and answers.
	 *
	 * @param int $questionsCount
	 * @param int $answersPerQuestion
	 *
	 * @return \Illuminate\Database\Eloquent\Factories\Factory
	 */
	public function withQuestionsAndAnswers(): Factory
	{
		return $this->afterCreating(function (Quiz $quiz) {
			Question::factory(10)->create(['quiz_id' => $quiz->id]);
		});
	}
}
