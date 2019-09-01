<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'RatesController@index');
Route::get('/by_date', 'RatesController@get_rates_by_date');
Route::match(['get', 'post'],'/by_date_by_iso', 'RatesController@get_rates_by_date_by_iso');
Route::get('/draw_graphic', 'RatesController@view_chart_form');
Route::get('/draw_graphic_debug', 'RatesController@get_data_for_chart');
