<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizIndexResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$data = [
			'id'                                                          => $this->id,
			'title'                                                       => $this->title,
			'image'                                                       => $this->getQuizImageUrl(),
			'categories'                                                  => CategoryResource::collection($this->categories),
			'difficulty_level'                                            => DifficultyLevelResource::collection($this->difficultyLevel),
			'total_users'                                                 => $this->totalUsers(),
		];
		if (auth()->check()) {
			$data['total_points'] = $this->questions->sum('points');
			$data['user_completed'] = $this->hasCompletedQuiz();
			$data['user_time'] = $this->quizTotalTime();
			$data['user_points'] = $this->quizTotalPoints();
			$data['quiz_completed_at'] = $this->quizCompletedAt();
		}
		return $data;
	}
}
