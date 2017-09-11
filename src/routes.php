<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/seolo-get-festives', 'ayudat\seolo\SeoloController@readFestives')->name('seolo-get-festives');
    Route::post('/seolo-save-festives', 'ayudat\seolo\SeoloController@saveFestives')->middleware('auth')->name('seolo-save-festives');

    Route::post('/seolo-save-text', 'ayudat\seolo\SeoloController@saveText')->middleware('auth')->name('seolo-save-text');
    Route::post('/seolo-save-alt', 'ayudat\seolo\SeoloController@saveAlt')->middleware('auth')->name('seolo-save-alt');
    Route::post('/seolo-save-tags', 'ayudat\seolo\SeoloController@saveTags')->middleware('auth')->name('seolo-save-tags');

});
