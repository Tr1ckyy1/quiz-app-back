<?php

namespace App\Http\Controllers;

use App\Models\QuizwizInfo;

class QuizwizInfoController extends Controller
{
	public function show()
	{
		return QuizwizInfo::firstOrFail();
	}
}
