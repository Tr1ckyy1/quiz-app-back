<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuizCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @return array<int|string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return $this->collection->map(function ($quiz) {
			return [
				'id'               => $quiz->id,
				'title'            => $quiz->title,
				'image'            => $quiz->image,
				'duration'         => $quiz->duration,
				'categories'       => $quiz->categories,
				'difficulty_level' => $quiz->difficultyLevel,
			];
		})->all();
	}
}
