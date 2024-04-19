<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('name');
			$table->string('username')->unique()->nullabe();
			$table->string('profile_image')->nullable();
			$table->string('accept_terms')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('username');
			$table->dropColumn('profile_image');
			$table->dropColumn('accept_terms');
		});
	}
};
