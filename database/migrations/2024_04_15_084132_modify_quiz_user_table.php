<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyQuizUserTable extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('quiz_user', function (Blueprint $table) {
			$table->dropColumn('duration');
			$table->dropColumn('points');
			$table->integer('total_time')->nullable();
			$table->integer('total_points')->nullable();
		});
	}

	/*
	 * Reverse the migrations.
	 */
	public function down(): void
	{
	}
}
