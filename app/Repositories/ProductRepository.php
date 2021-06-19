<?php


namespace App\Repositories;


use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;

class ProductRepository implements ProductRepositoryInterface
{

    public function createProduct($data)
    {
        // TODO: Implement createProduct() method.

        DB::beginTransaction();
        try {

            $p_arr = array(
                'p_name' => $data->pro_name
            );
            $resp = Product::create($p_arr);
            $this->saveProductPrices($resp->id,$data->pro_price_list);
            $this->saveProductCategories($resp->id,$data->category_ids);

            $photo_list = json_decode($data->pro_photo_list);

            foreach ($photo_list AS $p_list){

                $image_name = NULL;
                $resp_img = $this->uploadImage($p_list->photo);

                if($resp_img['status']) $image_name = $resp_img['image_name'];

                $this->saveProductImages($resp->id,$image_name);
            }

            DB::commit();
            return true;
        }catch (\Exception $e){

            DB::rollBack();
            Log::error('Error_on_pro_create'.$e->getMessage());
            return false;
        }
    }

    public function uploadImage($image_data){

        $messageDanger = "";
        if(!empty($image_data)) {

                $base64_img_user = ($image_data);
                $extension_u = explode('/', explode(':', substr($base64_img_user, 0, strpos($base64_img_user, ';')))[1])[1];

                $path = public_path() . '/pro_images/';

                try {
                    $resimg = Product::validatePhotoForAjax($extension_u);
                    $acc_extention_u = Product::fileMimeType($extension_u);

                    if (!$resimg['status']) $messageDanger .= $resimg['message'] . ' / ';

                    if ($resimg['status']) {

                        $imageName = uniqid() . '_photo.' .
                            strtolower($acc_extention_u);

                        if (file_exists($path . $imageName)) {
                            unlink($path . $imageName);
                        }

                        $path_modify_u = $path . $imageName;
                        $base64_img_user = substr($base64_img_user, strpos($base64_img_user, ",") + 1);
                        $decode_img_user = base64_decode($base64_img_user);

                        $success = file_put_contents($path_modify_u, $decode_img_user);

                        $canvas = Image::canvas(128, 128, '#ffffff');

                        $img = Image::make(file_get_contents($path . $imageName))->resize(520, 520, function ($constraint) {
                            $constraint->aspectRatio();
                        });

                        $canvas->insert($img, 'center');
                        $canvas->save($path . $imageName);

                        $resp_msg = ['status' => true, 'image_name' => $imageName];
                        return $resp_msg;
                    } else {

                        $resp_msg = ['status' => false, ['message' => $messageDanger]];
                        return $resp_msg;
                    }


                } catch (\Exception $e) {
                    $messageDanger .= $e->getMessage();
                    $resp_msg = ['status' => false, ['message' => $messageDanger]];
                    return $resp_msg;
                }
        }else{
            $resp_msg = ['status' => false,['message' => "Please select a valid image to upload! "]];
            return response()->json($resp_msg,401);
        }
    }

    public function saveProductImages($pro_id,$img_data)
    {
        // TODO: Implement saveProductImages() method.

        try {

            $ct_data = array(
                'product_id'=>$pro_id,
                'image_name'=>$img_data
            );

            ProductImage::create($ct_data);

            return true;
        }catch (\Exception $e){

            Log::error('Error_on_pro_img_create'.$e->getMessage());
            return false;
        }
    }

    public function saveProductCategories($pro_id, $cate_data)
    {
        // TODO: Implement saveProductCategories() method.
        try {

            foreach ($cate_data AS $c_data){
                $ct_data = array(
                    'product_id'=>$pro_id,
                    'category_id'=>$c_data
                );

                ProductCategory::create($ct_data);
            }

            return true;
        }catch (\Exception $e){

            Log::error('Error_on_pro_cate_create'.$e->getMessage());
            return false;
        }
    }

    public function saveProductPrices($pro_id,$price_list)
    {
        // TODO: Implement saveProductPrices() method.
        try {

            $product_price_list = json_decode($price_list);

            foreach ($product_price_list AS $ppl){

                if(!empty($ppl->lot_no_grid)) {
                    if(empty($ppl->cf_id)) {
                        $p_data = array(
                            'product_id' => $pro_id,
                            'lot_no' => $ppl->lot_no_grid,
                            'product_price' => str_replace(',', '', $ppl->pro_price_grid),
                            'product_qty' => str_replace(',', '', $ppl->pro_qty_grid),
                        );

                        ProductPrice::create($p_data);
                    }
                }
            }
            return true;
        }catch (\Exception $e){

            Log::error('Error_on_pro_price_create'.$e->getMessage());
            return false;
        }
    }

    public function getAllProductList()
    {
        // TODO: Implement getAllProductList() method.

        return Product::all();
    }

    public function updateProduct($up_data)
    {
        // TODO: Implement updateProduct() method.
        try {

            DB::beginTransaction();

            $id = $up_data->pro_id;
            $pro_data = Product::findOrFail($id);

            $p_arr = array(
                'p_name' => $up_data->pro_name
            );
            $pro_data->update($p_arr);

            $this->saveProductPrices($id, $up_data->pro_price_list);

            DB::commit();
            return true;
        }catch (\Exception $e){

            DB::rollBack();
            Log::error('Error_on_pro_create'.$e->getMessage());
            return false;
        }
    }

    public function getSingleProductData($id)
    {
        // TODO: Implement getSingleProductData() method.

        return Product::where('id',$id)->get();
    }

    public function inactivateProduct($id)
    {
        // TODO: Implement inactivateProduct() method.
        $course = DB::table('products')->where('id',$id)->get();

        $active_state = 1;
        if($course[0]->is_active) $active_state = 0;

        $dataArray = [
            'is_active' =>$active_state
        ];

        $resp = DB::table('products')->where('id',$id)->update($dataArray);

        return array('active_state' => $active_state, 'res_state'=>$resp);
    }

    public function getProductCategoryData($id)
    {
        // TODO: Implement getProductCategoryData() method.

        return ProductCategory::where('product_id',$id)->get();
    }

    public function getProductPriceData($id)
    {
        // TODO: Implement getProductPriceData() method.

        return ProductPrice::where('product_id',$id)->get();
    }
}
