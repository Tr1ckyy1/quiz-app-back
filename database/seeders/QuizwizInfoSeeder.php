<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizwizInfoSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		DB::table('quizwiz_infos')->insert(['name' => 'Quizzes', 'email' => 'quizwiz@gmail.com', 'phone' => '+995 328989', 'facebook' => 'https://facebook.com', 'linkedin' => 'https://linkedin.com']);
	}
}
