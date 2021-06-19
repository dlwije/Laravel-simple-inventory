<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

    public function productEditView($pro_id){

        $cate_list = $this->categoryRepository->getCategoryWithChildren();
        $pro_data = $this->productRepository->getSingleProductData($pro_id);
        $cate_edit_list = $this->productRepository->getProductCategoryData($pro_id);

        return view('product_edit', compact('cate_list','pro_data','cate_edit_list'));
    }

    public function getProductPriceList(Request $request){

        $id = ($request->pro_id);

        $resp = $this->productRepository->getProductPriceData($id);

        $resp_array = array(
            'dataCount' => $resp->count(),
            'data' => $resp
        );

        return response()->json($resp_array);

    }
    public function submitProductData(Request $request){

        $validateData = Validator::make($request->all(),[
            'pro_name'      => ['required', 'string', 'max:199'],
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => $validateData->getMessageBag()
            ],401);
        }

        try {

            $resp_msg = $this->productRepository->createProduct($request);

            if($resp_msg){
                $resp_msg = ['status' => true,'message' => 'Product has been inserted! '];
                return response()->json($resp_msg,200);
            }else{

                $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
                return response()->json($resp_msg,401);
            }
        }catch(\Exception $e){

            Log::error('pro_add_data_ERRO: '.$e->getMessage());
            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }
    }

    public function submitEditProductData(Request $request){

        $validateData = Validator::make($request->all(),[
            'pro_name'      => ['required', 'string', 'max:199'],
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => $validateData->getMessageBag()
            ],401);
        }

        try {

            $resp_msg = $this->productRepository->updateProduct($request);

            if($resp_msg){
                $resp_msg = ['status' => true,'message' => 'Product has been updated! '];
                return response()->json($resp_msg,200);
            }else{

                $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
                return response()->json($resp_msg,401);
            }
        }catch(\Exception $e){

            Log::error('pro_add_data_ERRO: '.$e->getMessage());
            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }
    }

    public function inactivateProduct(Request $request){

        $resp = $this->productRepository->inactivateProduct($request->pro_id);

        if($resp['active_state'])
            $message = "Product has been activated!";
        else
            $message = "Product has been inactivated!";

        if($resp['res_state']){
            $resp_msg = ['status' => true,'message' => $message];
            return response()->json($resp_msg,200);
        }else{

            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }

    }

}
