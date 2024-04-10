<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizIndexResource;
use App\Models\Quiz;

class QuizController extends Controller
{
	public function index()
	{
		$quizzes = Quiz::with('categories', 'difficultyLevel');
		return QuizIndexResource::collection($quizzes->filter(request(['categories', 'levels', 'sort', 'search']))->get());
	}
}
