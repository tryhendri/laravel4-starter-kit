<?php

use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create the `Articles` table
		Schema::create('articles', function($table)
		{
			$table->increments('id');
			$table->boolean('published');
			$table->integer('user_id');
			$table->string('title');
			$table->string('slug');
			$table->text('content');
			$table->string('meta_title')->nullable();
			$table->string('meta_description')->nullable();
			$table->string('meta_keywords')->nullable();

			$table->engine = 'InnoDB';
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Delete the `Articles` table
		Schema::drop('articles');
	}

}
