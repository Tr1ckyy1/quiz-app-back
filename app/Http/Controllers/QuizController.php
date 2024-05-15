<?php

namespace App\Http\Controllers;

use App\Actions\CompleteQuizAction;
use App\Http\Resources\QuizIndexResource;
use App\Http\Resources\QuizShowResource;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
	public function index()
	{
		$quizzes = Quiz::with('categories', 'difficultyLevel', 'users', 'questions')->hasQuestions();
		return QuizIndexResource::collection($quizzes->filter(request(['categories', 'levels', 'sort', 'search', 'my_quizzes', 'not_completed']))->paginate(9));
	}

	public function show(Quiz $quiz)
	{
		return new QuizShowResource($quiz->load('categories', 'questions'));
	}

	public function similarQuizzes(Request $request)
	{
		$quizHasCategories = Quiz::findOrFail($request->excludeId)->load('categories')->categories->count();
		if ($quizHasCategories) {
			return QuizIndexResource::collection(
				Quiz::similarQuizzes($request->categoryIds, $request->excludeId, auth()->id())
					->with('categories', 'difficultyLevel', 'questions')
					->orderBy('id', 'desc')->take(3)
					->get()
			);
		}
	}

	public function store(Request $request, CompleteQuizAction $completeQuizAction)
	{
		$quiz = Quiz::findOrFail($request->quizId);
		$response = $completeQuizAction->execute($quiz, $request->values, $request->totalTime, auth()->id());

		if (isset($response['error'])) {
			return response()->json($response, 400);
		}

		return response()->json($response);
	}
}
