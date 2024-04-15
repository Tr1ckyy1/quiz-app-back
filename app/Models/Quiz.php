<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

	public function totalUsers()
	{
		return DB::table('quiz_user')
		->where('quiz_id', $this->id)
		->count();
	}

	public function hasCompletedQuiz()
	{
		return $this->users()->where('user_id', auth()->id())->exists();
	}

	public function quizTotalTime()
	{
		return $this->users()
		->where('user_id', auth()->id())
		->pluck('total_time')
		->first();
	}

	public function quizTotalPoints()
	{
		return $this->users()
		->where('user_id', auth()->id())
		->pluck('total_points')
		->first();
	}

	public function quizCompletedAt()
	{
		return $this->users()
		->where('user_id', auth()->id())
		->where('quiz_user.quiz_id', $this->id)
		->select('quiz_user.created_at')
		->pluck('quiz_user.created_at')
		->first();
	}

	public function scopeSimilarQuizzes($query, $categoryIds, $excludeId, $userId)
	{
		return $query->whereNot('id', $excludeId)
		->whereHas('categories', function ($query) use ($categoryIds) {
			$query->whereIn('categories.id', $categoryIds);
		})
		->whereDoesntHave('users', function ($query) use ($userId) {
			$query->where('user_id', $userId);
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

		if (auth()->check()) {
			$query->when(isset($filters['my_quizzes']) && $filters['my_quizzes'] === 'true', function ($query) use ($filters) {
				if (isset($filters['not_completed']) && $filters['not_completed'] === 'true') {
					return $query;
				}

				$query->whereHas('users', function ($query) {
					$query->where('user_id', auth()->id());
				});
			});

			$query->when(isset($filters['not_completed']) && $filters['not_completed'] === 'true', function ($query) use ($filters) {
				if (isset($filters['my_quizzes']) && $filters['my_quizzes'] === 'true') {
					return $query;
				}

				$query->whereDoesntHave('users', function ($query) {
					$query->where('user_id', auth()->id());
				});
			});
		}

		$query->when($filters['search'] ?? false, function ($query, $search) {
			$query->where('title', 'like', '%' . $search . '%');
		});

		$sort = $filters['sort'] ?? 'Newest';

		switch ($sort) {
			case 'A-Z':
				$query->orderBy('title');
				break;
			case 'Z-A':
				$query->orderByDesc('title');
				break;
			case 'Most popular':
				$query->leftJoin('quiz_user', 'quizzes.id', '=', 'quiz_user.quiz_id')
				  ->select('quizzes.*', DB::raw('COUNT(quiz_user.quiz_id) as users_count'))
				  ->groupBy('quizzes.id')
				  ->orderByDesc('users_count');
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
