<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTestsTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::table('tests', function($table)
            {
                    $table->time('max_length')->nullable();
            });
	    Schema::table('tests_list', function($table)
            {
                    $table->time('max_length')->nullable();
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::table('tests', function($table)
            {
                    $table->dropColumn('max_length');
            });
	    Schema::table('tests_list', function($table)
            {
                    $table->dropColumn('max_length');
            });
	    
	}

}
