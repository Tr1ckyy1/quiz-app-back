<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizCollection;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
	public function index()
	{
		$quizzes = Quiz::with('categories', 'difficultyLevel');

		// return new QuizCollection($quizzes->filter($request->only(['categories', 'levels']))->get());
		return new QuizCollection($quizzes->filter(request(['categories', 'levels', 'sort']))->get());
		// return response()->json(request('levels'));
	}
}
