<?php

namespace App\Http\Controllers;

use App\Service\CatalogService;
use App\Tool\ValidationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatalogController extends Controller
{
    private $catalogService;
    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService=$catalogService;
    }
    public function addCatalog(Request $request){
        $rule=[
            'last_catalog_id' => 'required',
            'catalog_lv' =>'required',
            'catalog_name' =>'required'
        ];
        $validator = Validator::make($request->all(),$rule);
        if ($validator->fails()){
            return response()->json([
                'code' => 1001,
                'message' => '表单验证出错'
            ]);
        }
        else{
            $catalog_data=ValidationHelper::getInputData($request,$rule);
            $this->catalogService->addCatalog($catalog_data);
            return response()->json([
                'code' => 1000,
                'message' => '目录添加成功'
            ]);
        }
    }
    public function editCatalog(Request $request){
        $rule=[
            'catalog_id' =>'required',
            'last_catalog_id' => 'required',
            'catalog_lv' =>'required',
            'catalog_name' =>'required'
        ];
        $validator = Validator::make($request->all(),$rule);
        if ($validator->fails()){
            return response()->json([
                'code' => 1001,
                'message' => '表单验证出错'
            ]);
        }
        else{
            $catalog_data=ValidationHelper::getInputData($request,$rule);
            $this->catalogService->updateCatalog($catalog_data);
            return response()->json([
                'code' => 1000,
                'message' => '目录更新成功'
            ]);
        }
    }
    public function showCatalog(){
        $catalogsLv2=[];
        $Lv2catalog=$this->catalogService->selectCatalogByLv(2);
        foreach ($Lv2catalog as $key => $value){
            $thisLv2Catalog=[];
            $thisLv2Catalog['id']= $value->catalog_id;
            $thisLv2Catalog['name']=$value->catalog_name;
            $thisLv2Catalog['nextLvCatalog']=[];
            if (isset($catalogsLv2[$value->last_catalog_id])) {
                $catalogsLv2[$value->last_catalog_id] =array_merge($catalogsLv2[$value->last_catalog_id],[$thisLv2Catalog]);
            }
            else{
                $catalogsLv2[$value->last_catalog_id][0]=$thisLv2Catalog;
            }
            //$catalogs[$value->last_catalog_id]['nextLvCatalog'][$value->catalog_id]['nextLvCatalog']=[];
        }
        $Lv1catalog=$this->catalogService->selectCatalogByLv(1);
        $catalogs=[];
        $num=0;
        foreach ($Lv1catalog as $key => $value){
            $catalogsDate['id']=$value->catalog_id;
            $catalogsDate['name']=$value->catalog_name;
            $catalogsDate['nextLvCatalog']=$catalogsLv2[$value->catalog_id];
            $catalogs[$num]=$catalogsDate;
            $num++;
//            $catalogs[$value->catalog_id]['name']=$value->catalog_name;
//            $catalogs[$value->catalog_id]['nextLvCatalog']=[];
        }
        return response()->json([
            'code' => 1000,
            'catalog' => $catalogs
        ]);
    }
    public function deleteCatalog($catalog_id){
        if ($this->catalogService->isSetCatalog($catalog_id)){
            $this->catalogService->deleteCatalog($catalog_id);
            return response()->json([
                'code' => 1000,
                'message' => '删除目录成功'
            ]);
        }
        else{
            return response()->json([
                'code' => 4002,
                'message' =>'目录不存在'
            ]);
        }
    }
}
