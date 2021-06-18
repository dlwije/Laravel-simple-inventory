<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [CategoryController::class,'index'])->name('home');

Route::post('get-category-data-table-list',[CategoryController::class,'getDataTableList'])->name('getCategoryDataTableList');

Route::post('submit-category-data',[CategoryController::class,'submitNewCategoryData'])->name('submitNewCategoryData');

Route::post('get-parent-category-data',[CategoryController::class,'getParentCategoryList'])->name('getParentCategoryList');

Route::post('inactive-category',[CategoryController::class,'inactivateCategory'])->name('inactivateCategory');
