<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('home');
});

// Plupload
Route::any('/upload', 'MainController@upload');


// === AJAX === //
Route::get('/ajax/queue-status', function() {
    return array(
        'queue' => TestElement::queue()->count(), 
        'completed' => TestElement::completed()->count(),
        'total' => TestElement::count());
});
Route::post('/ajax/delete-queue', function() {
    TestElement::queue()->delete();
});
Route::post('/ajax/create-test', 'TestController@create');