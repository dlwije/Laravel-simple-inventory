<?php


namespace App\Repositories;


use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CategoryRepositoryInterface
{

    public function getAllCategories()
    {
        return Category::leftjoin(DB::raw('categories subCateTbl'),'categories.id','=','subCateTbl.parent_id')
            ->select('categories.*',DB::raw('subCateTbl.c_name AS subCateName'))
            ->get();
    }

    public function getCategoryWithChildren(){

       return Category::with('children')->whereNull('parent_id')->get();
    }

    public function getActiveCategories()
    {
        return Category::where('is_active',1)->get();
    }

    public function getParentCategoryList()
    {
        return Category::whereNull('parent_id')->get();
    }

    public function getActiveParentCategoryList()
    {
        return Category::where('is_active',1)->whereNull('parent_id')->get();
    }

    public function createCategory($data)
    {
        return Category::create($data);
    }

    public function getSingleCategoryData($id)
    {
        $resp = Category::where('id',$id)->get();

        return array('data' => $resp,'dataCount'=>$resp->count());
    }

    public function updateCategory($id,$upData)
    {
        $cate_data = Category::findOrFail($id);

        return $cate_data->update($upData);
    }

    public function inactivateCategory($id){

        $course = DB::table('categories')->where('id',$id)->get();

        $active_state = 1;
        if($course[0]->is_active) $active_state = 0;

        $dataArray = [
            'is_active' =>$active_state
        ];

        $resp = DB::table('categories')->where('id',$id)->update($dataArray);

        return array('active_state' => $active_state, 'res_state'=>$resp);
    }

    public function getDataTableList($data){

        $page = $data->RecordsStart;
        $resultCount = $data->PageSize;
        $searchTerm = $data['SearchTerm']['value'];
        $res_list_count =Category::where('c_name','like','%'.$searchTerm.'%')->get();

        $res_list = Category::where('c_name','like','%'.$searchTerm.'%')->offset($page)
            ->limit($resultCount)
            ->get();

        return array(

            "TotalRecords" => $res_list_count->get()->count(),
            "RecordsFiltered" => $res_list_count->get()->count(),
            "Data" => $res_list
        );
    }
}
