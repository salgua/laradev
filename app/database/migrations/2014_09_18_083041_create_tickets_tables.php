<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//tickets category table
		Schema::create('tickets_categories', function($t)
			{
				$t->increments('id');
				$t->string('title', 255);
				$t->integer('manager_id')->unsigned(); //foreign key
				$t->timestamps();
				//foreign keys
				$t->foreign('manager_id')->references('id')->on('users');
			});

		//tickets table
		Schema::create('tickets', function($t)
			{
			    $t->increments('id');
                $t->string('author_email', 255); //ticket creator is optionally registered
                $t->string('subject', 255);
                $t->longText('description');
                $t->integer('category_id')->unsigned(); //foreign key
                $t->integer('assigned_to')->unsigned(); //foreign key
                $t->boolean('open');
                $t->timestamps();
                //foreign keys
                $t->foreign('category_id')->references('id')->on('tickets_categories');
                $t->foreign('assigned_to')->references('id')->on('users');
			});

		//tickets comments table
		Schema::create('tickets_comments', function($t)
			{
				$t->increments('id');
				$t->integer('parent_id')->unsigned()->default(0); //parent comment
				$t->string('author_email', 255); //comment creator is optionally registered
				$t->integer('ticket_id')->unsigned(); //foreign key
				$t->longText('description');
				$t->timestamps();
				//foreign keys
				$t->foreign('ticket_id')->references('id')->on('tickets');
			});


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
