<?php


namespace App\Repositories;


use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{

    public function getAllCategories()
    {
        return Category::all();
    }

    public function createCategory($data)
    {
        return Category::create($data);
    }

    public function getSingleCategoryData($id)
    {
        return Category::where('id',$id)->get();
    }

    public function updateCategory($id,$upData)
    {
        $cate_data = Category::findOrFail($id);

        return $cate_data->update($upData);
    }
}
