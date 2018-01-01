<?php
/**
 * Created by PhpStorm.
 * User: andyhui
 * Date: 18-1-1
 * Time: 下午7:10
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Qiniu\Auth;


class UploadController extends Controller
{
    public function uppic(Request $request)
    {
        $accessKey = $request->accessKey;
        $secretKey = $request->secretKey;
        $bucket = $request->bucket;
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
        return response()->json([
            'token'=> $token
        ]);
    }
}