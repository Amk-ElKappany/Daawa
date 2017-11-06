<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewscategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('newscategories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title_en');
			$table->string('image')->nullable();
			$table->integer('views')->nullable();
			$table->boolean('active')->default('1');
			$table->boolean('home')->default('1');
			$table->text('description_en');
            $table->boolean('en')->default('1');
			$table->integer('created_by')->default('1');
			$table->integer('updated_by')->nullable();
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
		Schema::drop('newscategories');
	}

}
