<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
	use HasFactory;

	public function difficultyLevel()
	{
		return $this->belongsTo(DifficultyLevel::class);
	}

	public function categories()
	{
		return $this->belongsToMany(Category::class);
	}

	public function questions()
	{
		return $this->hasMany(Question::class);
	}

	public function users()
	{
		return $this->belongsToMany(User::class);
	}

	public function scopeSimilarQuizzes($query, $categoryIds, $excludeId)
	{
		return $query->whereNot('id', $excludeId)->whereHas('categories', function ($query) use ($categoryIds) {
			$query->whereIn('categories.id', $categoryIds);
		});
	}

	public function scopeFilter($query, array $filters)
	{
		$query->when($filters['categories'] ?? false, function ($query, $categories) {
			$categories = explode('&', $categories);
			$query->whereHas('categories', function ($query) use ($categories) {
				$query->whereIn('name', $categories);
			});
		});

		$query->when($filters['levels'] ?? false, function ($query, $difficultyLevels) {
			$difficultyLevels = explode('&', $difficultyLevels);
			$query->whereHas('difficultyLevel', function ($query) use ($difficultyLevels) {
				$query->whereIn('name', $difficultyLevels);
			});
		});

		$query->when($filters['search'] ?? false, function ($query, $search) {
			$query->where('title', 'like', '%' . $search . '%');
		});

		$sort = $filters['sort'] ?? 'Newest'; // Default Newest if no parameter for sort

		switch ($sort) {
			case 'A-Z':
				$query->orderBy('title');
				break;
			case 'Z-A':
				$query->orderByDesc('title');
				break;
			case 'Oldest':
				$query->oldest();
				break;
			default:
				$query->latest();
				break;
		}

		return $query;
	}
}
