<?php


namespace App\Repositories;


interface ProductRepositoryInterface
{
    public function getAllProductList();

    public function createProduct($data);

    public function updateProduct($data);

    public function getSingleProductData($id);

    public function inactivateProduct($id);

    public function getProductCategoryData($pro_id);

    public function getProductPriceData($pro_id);

    public function getProductPhotoData($pro_id);
}
