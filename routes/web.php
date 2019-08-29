<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });

Route::get('/by_date', 'RatesController@get_rates_by_date');
