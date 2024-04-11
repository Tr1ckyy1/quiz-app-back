<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizIndexResource;
use App\Http\Resources\QuizShowResource;
use App\Models\Quiz;

class QuizController extends Controller
{
	public function index()
	{
		$quizzes = Quiz::with('categories', 'difficultyLevel');
		return QuizIndexResource::collection($quizzes->filter(request(['categories', 'levels', 'sort', 'search']))->get());
	}

	public function show(Quiz $quiz)
	{
		return new QuizShowResource($quiz->load('categories', 'questions'));
	}

	public function similarQuizzes(Quiz $quiz)
	{
		$categoryIds = $quiz->categories->pluck('id');

		return QuizIndexResource::collection(
			$quiz->similarQuizzes($categoryIds)->with('categories', 'difficultyLevel')->take(3)->get()
		);
	}
}
