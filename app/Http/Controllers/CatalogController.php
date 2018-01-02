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
                'message' => '目录添加成功'
            ]);
        }
    }
    public function showCatalog(){
        $Lv1catalog=$this->catalogService->selectCatalogByLv(1);
        $catalogs=[];
        foreach ($Lv1catalog as $key => $value){
            $catalogs[$value->catalog_id]=[];
            $catalogs[$value->catalog_id]['name']=$value->catalog_name;
            $catalogs[$value->catalog_id]['nextLvCatalog']=[];
        }

        $Lv2catalog=$this->catalogService->selectCatalogByLv(2);
        foreach ($Lv2catalog as $key => $value){
            $catalogs[$value->last_catalog_id]['nextLvCatalog'][$value->catalog_id]=[];
            $catalogs[$value->last_catalog_id]['nextLvCatalog'][$value->catalog_id]['name']=$value->catalog_name;
            //$catalogs[$value->last_catalog_id]['nextLvCatalog'][$value->catalog_id]['nextLvCatalog']=[];
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
