<?php

namespace App\Http\Controllers;

use App\Models\DifficultyLevel;

class DifficultyLevelController extends Controller
{
	public function index()
	{
		return DifficultyLevel::all();
	}
}
