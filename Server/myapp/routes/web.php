<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $data = "Anudeep";
    return view('welcome' , ['data'=> $data]);
});
