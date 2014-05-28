<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('tests_list', function($table)
            {
                $table->increments('id');
                $table->integer('test_id')->references('id')->on('tests');
                $table->string('media');
                $table->integer('brightness')->nullable();
                $table->string('network')->default('wifi');
                $table->integer('signal_strenght')->default(100);
                $table->integer('volume')->default(0);
                $table->timestamp('started')->nullable();
                $table->timestamp('completed')->nullable();
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
            Schema::drop('tests_list');
	}

}
