<?php
/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| Your routes.
|
*/

Route::csrf()->method('post')->uri('By/ajax');
Route::csrf()->method('post')->uri('Personel/update');
Route::csrf()->method('post')->uri('Personel/register');
Route::csrf()->method('post')->uri('Personel/yetkiDuzenle');

Route::show404('Home/s404');
