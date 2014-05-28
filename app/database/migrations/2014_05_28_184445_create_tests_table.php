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
                $table->integer('brightness_steps')->nullable();
                $table->string('network')->default('wifi');
                $table->integer('signal_strenght_steps')->nullable();
                $table->integer('volume_steps')->nullable();
                $table->integer('exploded')->default(0);
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
