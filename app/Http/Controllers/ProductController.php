<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productRepository;
    protected $categoryRepository;

    function __construct(ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository){

        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(){

        $pro_list = $this->productRepository->getAllProductList();

        return view('product_list',compact('pro_list'));
    }

    public function productAddView(){

        $cate_list = $this->categoryRepository->getCategoryWithChildren();

        return view('product_add', compact('cate_list'));
    }
}
