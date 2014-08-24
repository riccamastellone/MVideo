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
		$table->string('volume');
		$table->string('used_battery');
		$table->string('voltage');
		$table->string('temperature');
		$table->string('health');
		$table->string('technology');
		$table->string('wifi');
		$table->string('ssid');
		$table->string('speed');
		$table->string('signal_strength');
		$table->string('mobile_status');
		$table->string('mobile_network_type');
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
