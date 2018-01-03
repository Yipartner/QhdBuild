<?php

namespace App\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TokenService
{

    public function createToken()
    {
        $time = new Carbon();
        $expiredTime = $time->copy();
        $expiredTime->hour+=1;
        $tokenContent = md5($time);
        $tokenId = DB::table('tokens')->insertGetId([
            'token_content'=>$tokenContent,
            'created_at' => $time,
            'expired_at' => $expiredTime
        ]);
        $tokenData=[];
        $tokenData['tokenId']=$tokenId;
        $tokenData['tokenContent']=$tokenContent;
        return $tokenData;
    }
    public function deleteToken($tokenId)
    {
        DB::table('tokens')->where('token_id', $tokenId)->delete();
    }

    public function getToken($tokenId)
    {
       return DB::table('tokens')->where('token_id',$tokenId)->first();
    }
}