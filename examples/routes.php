<?php

Route::group(['prefix' => 'messages'], function () {
    Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
    Route::get('/unread', ['as' => 'messages.unread', 'uses' => 'MessagesController@unread']); // ajax + Pusher
    Route::get('/create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
    Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
    Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
    Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
    Route::get('{id}/read', ['as' => 'messages.read', 'uses' => 'MessagesController@read']); // ajax + Pusher
});
