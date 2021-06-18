<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $categoryRepository;

    function __construct(CategoryRepositoryInterface $categoryRepository){

        $this->categoryRepository = $categoryRepository;
    }

    public function index(){

//        $category_list = $this->categoryRepository->getAllCategories();
        $parent_cate_list = $this->categoryRepository->getActiveParentCategoryList();

        $category_list = $this->categoryRepository->getCategoryWithChildren();
        return view('category_list',compact('category_list','parent_cate_list'));
    }

    public function getParentCategoryList(){

        return $this->categoryRepository->getParentCategoryList();
    }

    public function addNewView(){

        return view('');
    }

    public function submitNewCategoryData(Request $request){

        $msg = [
            'category_name.required' => 'Category field is required.',
        ];
        $validateData = Validator::make($request->all(),[
            'category_name'      => ['required'],
        ],$msg);

        if ($validateData->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => $validateData->getMessageBag()
            ],401);
        }

        $dataArray = [
            'parent_id' => $request->parent_cate_id,
            'c_name' => $request->category_name
        ];
        $resp_msg = $this->categoryRepository->createCategory($dataArray);


        if($resp_msg){
            $resp_msg = ['status' => true,'message' => 'Category has been created! '];
            return response()->json($resp_msg,200);
        }else{

            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }
    }

    public function getEditCategoryData(Request $request, $cate_id){


        return $this->categoryRepository->getSingleCategoryData($cate_id);
    }

    public function submitEditCategoryData(Request $request){

        $msg = [
            'category_name.required' => 'Category field is required.',
        ];
        $validateData = Validator::make($request->all(),[
            'category_name'      => ['required'],
        ],$msg);

        if ($validateData->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => $validateData->getMessageBag()
            ],401);
        }

        $id = $request->category_id;

        $dataArray = [
            'parent_id' => $request->parent_cate_id,
            'c_name' => $request->category_name
        ];
        $resp_msg = $this->categoryRepository->updateCategory($id,$dataArray);


        if($resp_msg){
            $resp_msg = ['status' => true,'message' => 'Category has been updated! '];
            return response()->json($resp_msg,200);
        }else{

            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }
    }
    public function inactivateCategory(Request $request){

        $resp = $this->categoryRepository->inactivateCategory($request->category_id);

        if($resp['active_state'])
            $message = "Category has been activated!";
        else
            $message = "Category has been inactivated!";

        if($resp['res_state']){
            $resp_msg = ['status' => true,'message' => $message];
            return response()->json($resp_msg,200);
        }else{

            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }
    }

}
