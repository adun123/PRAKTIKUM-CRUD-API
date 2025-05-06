<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('produk.index');
});


require __DIR__.'/auth.php';
