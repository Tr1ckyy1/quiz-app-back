<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	use HasFactory;

	protected static function boot()
	{
		parent::boot();

		static::deleting(function ($category) {
			$category->quizzes()->whereDoesntHave('categories', function ($query) use ($category) {
				$query->whereNot('categories.id', $category->id);
			})->delete();
		});
	}

	public function quizzes()
	{
		return $this->belongsToMany(Quiz::class);
	}
}
