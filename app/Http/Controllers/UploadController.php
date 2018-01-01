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
    public function upPic(Request $request)
    {
        $accessKey = $request->input('accessKey');
        $secretKey = $request->input('secretKey');
        $bucket = $request->input('bucket');
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
        return response()->json([
            'token'=> $token
        ]);
    }
}