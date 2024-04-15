<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizShowResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$data = [
			'id'                                             => $this->id,
			'title'                                          => $this->title,
			'duration'                                       => $this->duration,
			'categories'                                     => CategoryResource::collection($this->categories),
			'total_questions'                                => $this->questions->pluck('points')->count(),
			'total_points'                                   => $this->questions->sum('points'),
			'total_users'                                    => $this->totalUsers(),
			'user_completed'                                 => $this->when(auth()->check(), $this->hasCompletedQuiz()),
		];

		if (!$request->testPage) {
			$data['image'] = $this->image;
			$data['instructions'] = $this->instructions;
			$data['intro_questions'] = $this->intro_questions;
		} else {
			$data['questions'] = QuestionResource::collection($this->questions);
		}

		return $data;
	}
}
