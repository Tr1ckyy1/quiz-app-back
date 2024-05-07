<?php

namespace App\Nova;

use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class DifficultyLevel extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\DifficultyLevel>
	 */
	public static $model = \App\Models\DifficultyLevel::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'name';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id', 'name',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param \Laravel\Nova\Http\Requests\NovaRequest $request
	 *
	 * @return array
	 */
	public function fields(NovaRequest $request)
	{
		$levels = [
			'Starter'          => 'Starter',
			'Beginner'         => 'Beginner',
			'Middle'           => 'Middle',
			'High'             => 'High',
			'Very high'        => 'Very High',
			'Dangerously high' => 'Dangerously High',
		];

		$colors = [
			'#026AA2' => 'Starter',
			'#175CD3' => 'Beginner',
			'#6941C6' => 'Middle',
			'#B54708' => 'High',
			'#C11574' => 'Very High',
			'#C01048' => 'Dangerously High',
		];

		$bgColors = [
			'#F0F9FF' => 'Starter',
			'#EFF8FF' => 'Beginner',
			'#F9F5FF' => 'Middle',
			'#FFFAEB' => 'High',
			'#FDF2FA' => 'Very High',
			'#FFF1F3' => 'Dangerously High',
		];

		return [
			ID::make()->sortable(),
			Select::make('Name')
				->options($levels)
				->sortable()
				->rules('required'),

			Select::make('Color')->options($colors)->sortable()->rules('required'),

			Select::make('Bg color normal')->options($bgColors)->sortable()->rules('required'),
			HasMany::make('Quizzes'),
		];
	}
}
