<?php

namespace Database\Factories;

use App\Models\Answer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
	protected $model = Answer::class;

	public function definition()
	{
		return [
			'name'    => fake()->unique()->words(2, true),
			'correct' => $this->faker->boolean(),
		];
	}
}
