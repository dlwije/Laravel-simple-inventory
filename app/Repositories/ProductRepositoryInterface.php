<?php


namespace App\Repositories;


interface ProductRepositoryInterface
{
    public function getAllProductList();

    public function createProduct();

    public function saveProductImages();

    public function saveProductCategories();

    public function saveProductPrices();

}
