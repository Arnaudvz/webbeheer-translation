<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('translations', function(Blueprint $table)
		{
			$table->increments('id');

            // Webbeheer
            $table->integer('gebruiker');
            $table->integer('groep');
            $table->index(['gebruiker', 'groep']);

            $table->string('locale', 2);
            $table->string('group_name');
            $table->string('item');
            $table->string('content');

			$table->timestamps();
            $table->index(['locale', 'group_name']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('translations');
	}
}
