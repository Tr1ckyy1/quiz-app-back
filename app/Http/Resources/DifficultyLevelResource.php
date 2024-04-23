<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DifficultyLevelResource extends JsonResource
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
			'name'                         => $this->name,
			'color'                        => $this->color,
			'bg_color_normal'              => $this->bg_color_normal,
		];
	}
}
