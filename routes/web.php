<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::get('get-edit-category-data/{cate_id}',[CategoryController::class,'getEditCategoryData'])->name('getEditCategoryData');

Route::post('submit-edit-category-data',[CategoryController::class,'submitEditCategoryData'])->name('submitEditCategoryData');

Route::post('get-parent-category-data',[CategoryController::class,'getParentCategoryList'])->name('getParentCategoryList');

Route::post('inactive-category',[CategoryController::class,'inactivateCategory'])->name('inactivateCategory');


Route::get('product-list', [ProductController::class,'index'])->name('productList');

Route::get('product-add', [ProductController::class,'productAddView'])->name('productAddView');

Route::get('product-edit/{pro_id}', [ProductController::class,'productEditView'])->name('productEditView');

Route::post('submit-product-data',[ProductController::class,'submitProductData'])->name('submitProductData');

Route::post('submit-edit-product-data',[ProductController::class,'submitEditProductData'])->name('submitEditProductData');

Route::post('get-product-price-list',[ProductController::class,'getProductPriceList'])->name('getProductPriceList');

Route::post('inactive-product',[ProductController::class,'inactivateProduct'])->name('inactivateProduct');
