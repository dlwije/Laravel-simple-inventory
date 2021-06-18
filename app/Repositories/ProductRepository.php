<?php


namespace App\Repositories;


use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{

    public function createProduct()
    {
        // TODO: Implement createProduct() method.
    }

    public function saveProductImages()
    {
        // TODO: Implement saveProductImages() method.
    }

    public function saveProductCategories()
    {
        // TODO: Implement saveProductCategories() method.
    }

    public function saveProductPrices()
    {
        // TODO: Implement saveProductPrices() method.
    }

    public function getAllProductList()
    {
        // TODO: Implement getAllProductList() method.

        return Product::where('is_active',1)->get();
    }
}
