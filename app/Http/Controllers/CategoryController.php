<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepository;

    function __construct(CategoryRepositoryInterface $categoryRepository){

        $this->categoryRepository = $categoryRepository;
    }

    public function index(){

        $category_list = $this->categoryRepository->getAllCategories();
        return view('category_list',compact('category_list'));
    }
}
