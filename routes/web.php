<?php

use Illuminate\Support\Facades\Route;

// Redirigir raíz a marcas
Route::get('/', fn() => redirect('/marcas'));

// Vistas del frontend
Route::view('/marcas',      'marcas.index');
Route::view('/categorias',  'categorias.index');
Route::view('/proveedores', 'proveedores.index');