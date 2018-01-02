<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class  PictureService
{
    public function addPicture($picture_data)
    {
        DB::table('pictures')->insert($picture_data);
    }

    public function deletePicture($picture_id)
    {
        DB::table('pictures')->where('picture_id', $picture_id)->delete();
    }
    public function issetPicture($picture_id){
        $data = DB::table('pictures')->where('picture_id', $picture_id)->first();
        if (isset($data->picture_id)) {
            return true;
        } else {
            return false;
        }
    }
    public function selectPicture($picture_id){
        return DB::table('pictures')->where('picture_id',$picture_id)->first();
    }
    public function selectAllPicture(){
        return DB::table('pictures')->where('picture_id','>',0)->get();
    }
}