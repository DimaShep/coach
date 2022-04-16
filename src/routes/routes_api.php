<?php

Route::group(['prefix' => 'coach', 'as' => 'api.coach.'], function() {
    $namespacePrefix = '\\'.config('coach.controllers.namespace').'\\';

//    Route::get('position', $namespacePrefix.'PositionController@index')->name('position.index');
//    Route::get('position/{id}', $namespacePrefix.'PositionController@show')->name('position.show');
//    Route::get('position/edit/{id}', $namespacePrefix.'PositionController@edit')->name('position.edit');
//    Route::post('position/update/{id}', $namespacePrefix.'PositionController@update')->name('position.update');
//    Route::delete('position/{id}', $namespacePrefix.'PositionController@delete')->name('position.delete');



    Route::post('get_data_copy/{position}', $namespacePrefix.'ApiController@getCopyData')->name('get.data.copy');
    Route::post('task_copy/{position}', $namespacePrefix.'ApiController@taskCopy')->name('task.copy');
    Route::post('position_copy/{position}', $namespacePrefix.'ApiController@positionCopy')->name('position.copy');
    Route::post('task_create/{position}', $namespacePrefix.'ApiController@taskCreate')->name('task.create');
    Route::post('task_all/{position}', $namespacePrefix.'ApiController@taskAll')->name('task.all');
    Route::post('task_update/{position}', $namespacePrefix.'ApiController@taskUpdate')->name('task.update');
    Route::post('task_line_delete/{position}', $namespacePrefix.'ApiController@taskLineDelete')->name('task_line.delete');
    Route::post('task_delete/{position}', $namespacePrefix.'ApiController@taskDelete')->name('task.delete');
    Route::post('send_answer_test/{task}/{position}', $namespacePrefix.'ApiController@sendAnswerTest')->name('send_answer_test');
    Route::post('/send_answer/{task}', $namespacePrefix.'ApiController@sendAnswer')->name('send_answer');

    Route::post('uploader', $namespacePrefix.'UploaderController@upload')->name('uploader');
});
