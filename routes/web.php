<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubcategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class)->except(['show']);
Route::resource('subcategories', SubcategoryController::class);

Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');



