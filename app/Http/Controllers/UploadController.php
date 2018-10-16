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
        $file_name = $request->input('fileName', null);
        $accessKey = $request->input('accessKey');
        $secretKey = $request->input('secretKey');
        $bucket = $request->input('bucket');
        $auth = new Auth($accessKey, $secretKey);

        $policy = [
            'saveKey' => $file_name
        ];
        if ($file_name)
            $token = $auth->uploadToken($bucket, null, 3600, $policy, true);
        else
            $token = $auth->uploadToken($bucket);
        return response()->json([
            'token' => $token
        ]);
    }
}