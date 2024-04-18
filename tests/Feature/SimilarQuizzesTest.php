<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DifficultyLevel;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimilarQuizzesTest extends TestCase
{
	use RefreshDatabase;

	public function test_similar_quizzes_returns_quizzes_with_same_categories()
	{
		DifficultyLevel::create(
			['name' => 'Starter', 'bg_color_normal' => '#F0F9FF', 'color' => '#026AA2']
		);

		$categories = Category::factory()->create();

		$quizzes = Quiz::factory(5)->create(['difficulty_level_id' => 1]);

		foreach ($quizzes as $quiz) {
			$quiz->categories()->sync($categories);
		}

		$quizId = $quizzes->first()->id;

		$response = $this->getJson(route('quizzes.show', ['quiz' => $quizId]));

		$response->assertOk();
		$categoryIds = collect($response->json('data.categories'))->pluck('id')->toArray();

		$similarQuizzesResponse = $this->getJson(route('similar_quizzes', [
			'categoryIds' => $categoryIds,
			'excludeId'   => $quizId,
		]));

		$similarQuizzesResponse->assertStatus(200);
	}

	public function test_similar_quizzes_returns_quizzes_with_same_categories_excluding_quizzes_that_user_has_completed()
	{
		DifficultyLevel::create(
			['name' => 'Starter', 'bg_color_normal' => '#F0F9FF', 'color' => '#026AA2']
		);
		$user = User::factory()->create();

		$categories = Category::factory()->create();

		$quizzes = Quiz::factory(5)->create(['difficulty_level_id' => 1]);

		foreach ($quizzes as $quiz) {
			$quiz->categories()->sync($categories);
		}

		$completedQuizzes = $quizzes->random(3);
		foreach ($completedQuizzes as $completedQuiz) {
			$completedQuiz->users()->attach($user, ['total_time' => 4, 'total_points' => 22, 'created_at' => now()]);
		}

		$quizId = $quizzes->first()->id;

		$response = $this->getJson(route('quizzes.show', ['quiz' => $quizId]));

		$response->assertOk();
		$categoryIds = collect($response->json('data.categories'))->pluck('id')->toArray();

		$similarQuizzesResponse = $this->actingAs($user)->getJson(route('similar_quizzes', [
			'categoryIds' => $categoryIds,
			'excludeId'   => $quizId,
		]));

		$similarQuizzesResponse->assertStatus(200);
	}
}
