<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DifficultyLevel extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	public function quizzes()
	{
		return $this->hasMany(Quiz::class);
	}
}
