<?php

namespace App\Http\Controllers;

use App\Service\PictureService;
use App\Tool\ValidationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Integer;

class PictureController extends Controller
{
    private $pictureService;

    public function __construct(PictureService $pictureService)
    {
        $this->pictureService = $pictureService;
    }

    public function addPicture(Request $request)
    {
        $rule = [
            'picture_url' => 'required',
            'picture_type' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return response()->json([
                'code' => 1001,
                'message' => '表单验证出错'
            ]);
        } else {
            $pictureData = ValidationHelper::getInputData($request, $rule);
            $pictureId=$this->pictureService->addPicture($pictureData);
            return response()->json([
                'code' => 1000,
                'message' => '图片添加成功',
                'picture_id'=> $pictureId
            ]);
        }
    }

    public function deletePicture($picture_id)
    {
        $picture_data = $this->pictureService->selectPicture($picture_id);
        if (isset($picture_data->picture_id)) {
            $this->pictureService->deletePicture($picture_id);
            return response()->json([
                'code' => 1000,
                'message' => '删除成功'
            ]);
        } else
            return response()->json([
                'code' => 5002,
                'message' => '图片不存在'
            ]);
    }
    public function showPicture(){
        $picture_data=$this->pictureService->selectAllPicture();
        $pictures=[
            '1'=>[],
            '2'=>[],
            '3'=>[],
            '4'=>[],
            '5'=>[],
            '6'=>[]
        ];
        foreach ($picture_data as $key => $value){
            if(!isset($pictures[$value->picture_type][0]))
                $pictures[$value->picture_type][0]=[$value->picture_id=>$value->picture_url];
            else
                $pictures[$value->picture_type]=array_merge($pictures[$value->picture_type],[[$value->picture_id=>$value->picture_url]]);
        }
        return response()->json([
            'code' => 1000,
            'picture'=>$pictures
        ]);
    }

    public function createFriendUrl(Request $request){
        //最多6个友情链接
        $rules=[
            'url'=>'required',
            'title'=>'required',
            'position'=>'required|integer|min:1|max:6'
        ];
        $res=ValidationHelper::validateCheck($request->input(),$rules);
        if ($res->fails()){
            return response()->json([
                'code' => '1001',
                'message'=>$res->errors()
            ]);
        }
        $data= ValidationHelper::getInputData($request,$rules);
        $check=DB::table('friend_urls')->where('position',$data['position'])->first();
        if ($check){
            DB::table('friend_urls')
                ->where('position',$data['position'])
                ->update($data);
            return response()->json([
                'code' => 1000,
                'message' =>'位置'.$data['position'].'的友情链接更新成功'
            ]);
        }else{
        DB::table('friend_urls')->insert($data);
        return response()->json([
            'code' => 1000,
            'message' =>'友情链接创建成功'
        ]);
        }
    }
    public function deleteFriendUrl($id){
        DB::table('friend_urls')->where('id',$id)->delete();
        return response()->json([
            'code' => 1000,
            'message' => '删除成功'
        ]);
    }
    public function selectFriendUrl(Request $request){
        $urls = DB::table('friend_urls')->get();
        return response()->json([
            'code' => 1000,
            'message' => '查询友情链接成功',
            'data' => $urls
        ]);
    }

}
