<?php
use Illuminate\Support\Facades\Route;

Route::get('/{any}', fn() => view('website'))->name('website')->where('any', '.*');
