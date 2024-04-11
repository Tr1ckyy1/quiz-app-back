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
		return [
			'id'                           => $this->id,
			'title'                        => $this->title,
			'image'                        => $this->image,
			'instructions'                 => $this->instructions,
			'intro_question'               => $this->intro_question,
			'duration'                     => $this->duration,
			'categories'                   => CategoryResource::collection($this->categories),
			'questions'                    => $this->questions,
		];
	}
}
