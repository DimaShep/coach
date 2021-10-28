<?php

Route::group(['prefix' => 'coach', 'as' => 'coach.'], function() {
    $namespacePrefix = '\\'.config('coach.controllers.namespace').'\\';

//    Route::get('position', $namespacePrefix.'PositionController@index')->name('position.index');
//    Route::get('position/{id}', $namespacePrefix.'PositionController@show')->name('position.show');
//    Route::get('position/edit/{id}', $namespacePrefix.'PositionController@edit')->name('position.edit');
//    Route::post('position/update/{id}', $namespacePrefix.'PositionController@update')->name('position.update');
//    Route::delete('position/{id}', $namespacePrefix.'PositionController@delete')->name('position.delete');

    Route::resource('positions', $namespacePrefix.'PositionController');
    Route::get('positions/{position}/map', $namespacePrefix.'PositionController@map')->name('positions.map');
    Route::get('mentors', $namespacePrefix.'MentorsController@index')->name('mentors.index');
    Route::get('mentors/{result}', $namespacePrefix.'MentorsController@checked')->name('mentors.checked');
    Route::post('mentors/{result}', $namespacePrefix.'MentorsController@update')->name('mentors.update');

    Route::resource('maps', $namespacePrefix.'MapController');
    Route::resource('tasks', $namespacePrefix.'TaskController');
    Route::resource('users', $namespacePrefix.'UserController');

});
