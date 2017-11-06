<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsParagraphsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news_paragraphs', function(Blueprint $table)
		{
			$table->increments('id');
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->integer('news_id')->unsigned()->index();
			$table->string('title_en');
			$table->string('image')->nullable();
			$table->boolean('active')->default('1');
			$table->boolean('en')->default('1');
			$table->text('description_en');
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
		Schema::drop('news_paragraphs');
	}

}
