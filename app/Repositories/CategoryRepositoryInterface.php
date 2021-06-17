<?php


namespace App\Repositories;


interface CategoryRepositoryInterface
{
    public function getAllCategories();

    public function createCategory($data);

    public function getSingleCategoryData($id);

    public function updateCategory($id,$upData);

}
