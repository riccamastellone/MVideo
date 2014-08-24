<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    Schema::create('results', function($table)
            {
                $table->integer('test_id')->unique(); // Lo usiamo come chiave
                $table->string('imei');
		$table->string('brightness');
		$table->string('volume')->nullable();
		$table->string('used_battery');
		$table->string('voltage')->nullable();
		$table->string('temperature')->nullable();
		$table->string('health')->nullable();
		$table->string('technology')->nullable();
		$table->string('wifi')->nullable();
		$table->string('ssid')->nullable();
		$table->string('speed')->nullable();
		$table->string('signal_strength')->nullable();
		$table->string('mobile_status')->nullable();
		$table->string('mobile_network_type')->nullable();
		$table->string('ip');
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
		//
	}

}
