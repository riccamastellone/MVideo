<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('tests', function($table)
            {
                $table->increments('id');
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
            Schema::drop('tests');
	}

}
