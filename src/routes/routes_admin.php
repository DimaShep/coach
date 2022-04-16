<?php

Route::group(['prefix' => 'coach', 'as' => 'coach.'], function() {
    $namespacePrefix = '\\'.config('coach.controllers.namespace').'\\';

//    Route::get('position', $namespacePrefix.'PositionController@index')->name('position.index');
//    Route::get('position/{id}', $namespacePrefix.'PositionController@show')->name('position.show');
//    Route::get('position/edit/{id}', $namespacePrefix.'PositionController@edit')->name('position.edit');
//    Route::post('position/update/{id}', $namespacePrefix.'PositionController@update')->name('position.update');
//    Route::delete('position/{id}', $namespacePrefix.'PositionController@delete')->name('position.delete');
    if(strpos(url()->current(), '/admin')) {
        Route::get('/', $namespacePrefix . 'FrontendController@index')->name('index');
        Route::get('/map/{position}', $namespacePrefix . 'FrontendController@map')->name('map');
        Route::get('/task/{position}/{task}', $namespacePrefix . 'FrontendController@task')->name('task');
        Route::get('/results', $namespacePrefix . 'FrontendController@results')->name('results');
    }


    Route::resource('positions', $namespacePrefix.'PositionController');
    Route::get('positions/{position}/map', $namespacePrefix.'PositionController@map')->name('positions.map');
    Route::get('mentors', $namespacePrefix.'MentorsController@index')->name('mentors.index');
    Route::get('mentors/{result}', $namespacePrefix.'MentorsController@checked')->name('mentors.checked');
    Route::post('mentors/{result}', $namespacePrefix.'MentorsController@update')->name('mentors.update');

    Route::resource('maps', $namespacePrefix.'MapController');
    Route::resource('tasks', $namespacePrefix.'TaskController');
    Route::resource('users', $namespacePrefix.'UserController');

    Route::post('task/reset', $namespacePrefix . 'TaskController@reset')->name('task.reset');



    Route::get('settings/', $namespacePrefix.'SettingsController@index')->name('settings.index');

    Route::put('settings/update/{item}', $namespacePrefix.'SettingsController@updateByItem')->name('settings.update.item');

    Route::delete('settings/delete/{item}', $namespacePrefix.'SettingsController@deleteByItem')->name('settings.delete.item');


});
