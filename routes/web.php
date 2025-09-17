<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // or the name of your main Blade file
})->where('any', '.*');