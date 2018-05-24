<?php

namespace App\Http\Controllers;

use App\Tool\ValidationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RandPictureController extends Controller
{
    //
    public function addPicture(Request $request){
        $rule=[
            "type"=>'required',
            "picture_url" =>'required',
            'url'=>'required'
        ];
        $res=ValidationHelper::validateCheck($request->all(),$rule);
        if ($res->fails()){
            return response()->json([
                'code' => 5001,
                'message' => $res->errors()
            ]);
        }
        $data=ValidationHelper::getInputData($request,$rule);
        if (DB::table('rand_pictures')->insert($data))
        {
            return response()->json([
                'code'=>1000,
                'message' => '添加成功'
            ]);
        }
        else{
            return response()->json([
                'code' => 5010,
                'message' =>'出现未知错误'
            ]);
        }
    }
    public function updatePicture($pictureId,Request $request){
        $data['picture_url']=$request->input('picture_url');
        $data['url']=$request->input('url');
        if (DB::table('rand_pictures')->where('id',$pictureId)->update($data)){
            return response()->json([
                'code'=>1000,
                'message' => '修改成功'
            ]);
        }
        else{
            return response()->json([
                'code' =>5001,
                'message' =>'图片不存在'
            ]);
        }

    }
    public function deletePicture($pictureId){
        DB::table('rand_pictures')->where('id',$pictureId)->delete();
        return response()->json([
            'code'=>1000,
            'message'=>'删除成功'
        ]);
    }
    public function showTypePictures($typeId){
        $pictures=DB::table('rand_pictures')->where('type',$typeId)->get();

        return response()->json([
            'code' =>1000,
            'pictures'=>$pictures
        ]);
    }
    public function showPictures(){
        $pictures=DB::table('rand_pictures')->where('type','>',0)->get();
        $picturesArray=[];
        foreach ($pictures as $item){
            if (isset($picturesArray[$item->type])){
                array_push($picturesArray[$item->type],$item);
            }
            else{
                $picturesArray[$item->type]=[$item];
            }
        }
        return response()->json([
            'code' =>1000,
            'pictures'=>$picturesArray
        ]);
    }
}
