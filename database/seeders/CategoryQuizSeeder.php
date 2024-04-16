<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class CategoryQuizSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$quizzes = Quiz::all();
		$categories = Category::factory(20)->create()->pluck('id');

		foreach ($quizzes as $quiz) {
			$categoryIds = $categories->random(rand(1, $categories->count()))->toArray();
			$quiz->categories()->sync($categoryIds);
		}
	}
}
