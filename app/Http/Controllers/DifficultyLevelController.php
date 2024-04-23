<?php

namespace App\Http\Controllers;

use App\Http\Resources\DifficultyLevelResource;
use App\Models\DifficultyLevel;

class DifficultyLevelController extends Controller
{
	public function index()
	{
		return DifficultyLevelResource::collection(DifficultyLevel::all());
	}
}
