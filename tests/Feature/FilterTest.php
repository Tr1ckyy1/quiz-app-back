<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DifficultyLevel;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FilterTest extends TestCase
{
	use RefreshDatabase;

	private DifficultyLevel $difficultyLevel;

	protected function setUp(): void
	{
		parent::setUp();
		$this->difficultyLevel = DifficultyLevel::create(
			['name' => 'Starter', 'bg_color_normal' => '#F0F9FF', 'color' => '#026AA2']
		);
	}

	public function test_can_filter_by_categories()
	{
		$category1 = Category::factory()->create(['name' => 'Music']);
		$category2 = Category::factory()->create(['name' => 'Geography']);
		$category3 = Category::factory()->create(['name' => 'Math']);

		$quiz1 = Quiz::factory()->create(['difficulty_level_id' => 1]);
		$quiz1->categories()->attach($category1);

		$quiz2 = Quiz::factory()->create(['difficulty_level_id' => 1]);
		$quiz2->categories()->attach($category1);

		$quiz3 = Quiz::factory()->create(['difficulty_level_id' => 1]);
		$quiz3->categories()->attach($category1);

		$quiz4 = Quiz::factory()->create(['difficulty_level_id' => 1]);
		$quiz4->categories()->attach($category2);
		$quiz5 = Quiz::factory()->create(['difficulty_level_id' => 1]);
		$quiz5->categories()->attach($category3);

		$response = $this->getJson(route('quizzes.index', ['categories' => 'Geography&Music']));

		$response->assertJsonCount(4, 'data');
		$response->assertStatus(200);
	}

	public function test_can_filter_by_difficulty_levels()
	{
		DB::table('difficulty_levels')->insert(
			[
				[
					'name'                 => 'Beginner',
					'bg_color_normal'      => '#EFF8FF',
					'color'                => '#175CD3',
					'created_at'           => fake()->dateTime(),
					'updated_at'           => fake()->dateTime(),
				],
				[
					'name'                 => 'Middle',
					'bg_color_normal'      => '#F9F5FF',
					'color'                => '#6941C6',
					'created_at'           => fake()->dateTime(),
					'updated_at'           => fake()->dateTime(),
				],
			]
		);
		Quiz::factory()->create(['difficulty_level_id' => 1]);
		Quiz::factory()->create(['difficulty_level_id' => 2]);
		Quiz::factory()->create(['difficulty_level_id' => 3]);

		$response = $this->getJson(route('quizzes.index', ['levels' => 'Starter&Beginner']));

		$response->assertJsonCount(2, 'data');
		$response->assertStatus(200);
	}

	public function test_can_filter_by_search()
	{
		$category = Category::factory()->create();

		$quiz1 = Quiz::factory()->create(['difficulty_level_id' => 1, 'title' => 'Test']);
		$quiz2 = Quiz::factory()->create(['difficulty_level_id' => 1, 'title' => 'Test2']);
		$quiz3 = Quiz::factory()->create(['difficulty_level_id' => 1, 'title' => 'Test3']);
		$quiz4 = Quiz::factory()->create(['difficulty_level_id' => 1]);
		$quiz5 = Quiz::factory()->create(['difficulty_level_id' => 1]);

		$quiz1->categories()->attach($category);
		$quiz2->categories()->attach($category);
		$quiz3->categories()->attach($category);
		$quiz4->categories()->attach($category);
		$quiz5->categories()->attach($category);

		$response = $this->getJson(route('quizzes.index', ['search' => 'test']));

		$response->assertJsonCount(3, 'data');
		$response->assertStatus(200);
	}

	public function test_can_filter_by_sorting_az()
	{
		Quiz::factory()->create(['title' => 'Quiz B', 'difficulty_level_id' => 1]);
		Quiz::factory()->create(['title' => 'Quiz C', 'difficulty_level_id' => 1]);
		Quiz::factory()->create(['title' => 'Quiz A', 'difficulty_level_id' => 1]);
		Quiz::factory()->create(['title' => 'Quiz Aa', 'difficulty_level_id' => 1]);
		Quiz::factory()->create(['title' => 'Quiz Aab', 'difficulty_level_id' => 1]);
		Quiz::factory()->create(['title' => 'Quiz Ca', 'difficulty_level_id' => 1]);

		$response = $this->getJson(route('quizzes.index', ['sort' => 'A-Z']));

		$response->assertStatus(200)
			->assertSeeInOrder(['Quiz A', 'Quiz Aa', 'Quiz Aab', 'Quiz B', 'Quiz C', 'Quiz Ca']);
	}

	public function test_can_filter_by_sorting_za()
	{
		Quiz::factory()->create(['title' => 'Quiz B', 'difficulty_level_id' => 1]);
		Quiz::factory()->create(['title' => 'Quiz C', 'difficulty_level_id' => 1]);
		Quiz::factory()->create(['title' => 'Quiz A', 'difficulty_level_id' => 1]);
		Quiz::factory()->create(['title' => 'Quiz Aa', 'difficulty_level_id' => 1]);
		Quiz::factory()->create(['title' => 'Quiz Aab', 'difficulty_level_id' => 1]);
		Quiz::factory()->create(['title' => 'Quiz Ca', 'difficulty_level_id' => 1]);

		$response = $this->getJson(route('quizzes.index', ['sort' => 'Z-A']));

		$response->assertStatus(200)
			->assertSeeInOrder(['Quiz Ca', 'Quiz C', 'Quiz B', 'Quiz Aab', 'Quiz Aa', 'Quiz A']);
	}

	public function test_can_filter_by_sorting_oldest()
	{
		Quiz::factory()->create(['title' => 'Quiz A',  'difficulty_level_id' => 1, 'created_at' => now()->subDays(3)]);
		Quiz::factory()->create(['title' => 'Quiz B',  'difficulty_level_id' => 1, 'created_at' => now()->subDays(2)]);
		Quiz::factory()->create(['title' => 'Quiz C',  'difficulty_level_id' => 1, 'created_at' => now()->subDays(1)]);

		$response = $this->getJson(route('quizzes.index', ['sort' => 'Oldest']));

		$response->assertStatus(200)
			->assertSeeInOrder(['Quiz A', 'Quiz B', 'Quiz C']);
	}

	public function test_can_filter_by_sorting_newest()
	{
		Quiz::factory()->create(['title' => 'Quiz A',  'difficulty_level_id' => 1, 'created_at' => now()->subDays(3)]);
		Quiz::factory()->create(['title' => 'Quiz B',  'difficulty_level_id' => 1, 'created_at' => now()->subDays(2)]);
		Quiz::factory()->create(['title' => 'Quiz C',  'difficulty_level_id' => 1, 'created_at' => now()->subDays(1)]);

		$response = $this->getJson(route('quizzes.index', ['sort' => 'Newest']));

		$response->assertStatus(200)
			->assertSeeInOrder(['Quiz C', 'Quiz B', 'Quiz A']);
	}

	public function test_can_filter_by_most_popular()
	{
		$quizzes = Quiz::factory(6)->create();

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

		$response = $this->getJson(route('quizzes.index', ['sort' => 'Most popular']));

		$response->assertStatus(200);

		$firstQuizPopularity = $response->json('data.0.users_count');
		$secondQuizPopularity = $response->json('data.1.users_count');
		$this->assertTrue($firstQuizPopularity >= $secondQuizPopularity);
	}
}
