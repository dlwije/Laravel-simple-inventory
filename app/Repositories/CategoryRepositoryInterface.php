<?php


namespace App\Repositories;


interface CategoryRepositoryInterface
{
    public function getAllCategories();

    public function getCategoryWithChildren();

    public function getActiveCategories();

    public function getParentCategoryList();

    public function getActiveParentCategoryList();

    public function createCategory($data);

    public function getSingleCategoryData($id);

    public function updateCategory($id,$upData);

    public function getDataTableList($data);

    public function inactivateCategory($id);

}
