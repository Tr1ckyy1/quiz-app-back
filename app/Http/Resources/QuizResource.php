<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		// return parent::toArray($request);
		return [
			'id'          => $this->id,
			'title'       => $this->title,
			// 'email'      => $this->email,
			// 'created_at' => $this->created_at,
			// 'updated_at' => $this->updated_at,
		];
	}
}
