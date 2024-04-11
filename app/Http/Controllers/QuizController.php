<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizIndexResource;
use App\Http\Resources\QuizShowResource;
use App\Models\Quiz;
use Illuminate\Http\Request;

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

	public function similarQuizzes(Request $request)
	{
		return QuizIndexResource::collection(Quiz::similarQuizzes($request->categoryIds, $request->excludeId)->with('categories', 'difficultyLevel')->take(3)->get());
	}
}
