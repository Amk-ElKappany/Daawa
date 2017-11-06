<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function(Blueprint $table)
		{
			$table->increments('id');
            $table->foreign('newscategory_id')->references('id')->on('newscategories')->onDelete('cascade');
            $table->integer('newscategory_id')->unsigned()->index();
			$table->string('title_en');
			$table->string('image');
			$table->string('date')->nullable();
			$table->integer('views')->nullable();
			$table->string('attachment')->nullable();
			$table->boolean('has_video')->nullable();
			$table->boolean('has_sound')->nullable();
			$table->boolean('active')->default('1');
			$table->boolean('home')->nullable();
			$table->text('description_en');
			$table->string('video_link')->nullable();
			$table->string('video_title_en')->nullable();
			$table->text('video_description_en')->nullable();
			$table->string('sound_link')->nullable();
			$table->string('sound_title_en')->nullable();
			$table->string('sound_description_en')->nullable();
            $table->boolean('en')->default('1');
			$table->integer('created_by');
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
		Schema::drop('news');
	}

}
