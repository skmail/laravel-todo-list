<?php




Route::resource('todo', 'TodoController')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/todo/{todo}/assign/{user}','TodoAssignmentController@store')->middleware('auth');

Route::post('/todo/import','TodoImportController@import')->middleware('auth');