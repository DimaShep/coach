<?php

Route::group(['prefix' => 'coach', 'as' => 'coach.', 'middleware' => ['coach']], function() {
    $namespacePrefix = '\\'.config('coach.controllers.namespace').'\\';
//    Route::get('demo', $namespacePrefix.'CoachController@demo');
    Route::get('/', $namespacePrefix.'FrontendController@index')->name('index');
    Route::get('/map/{position}', $namespacePrefix.'FrontendController@map')->name('map');
    Route::get('/task/{position}/{task}', $namespacePrefix.'FrontendController@task')->name('task');
    Route::get('/results', $namespacePrefix.'FrontendController@results')->name('results');
});
