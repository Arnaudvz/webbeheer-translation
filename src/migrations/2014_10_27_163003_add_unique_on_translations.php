<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUniqueOnTranslations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('translations', function(Blueprint $table)
		{
			$table->unique(['locale', 'group_name', 'item'], 'unique_translations');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('translations', function(Blueprint $table)
		{
			$table->dropUnique('unique_translations');
		});

	}
}
