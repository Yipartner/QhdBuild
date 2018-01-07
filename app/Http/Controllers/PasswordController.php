<?php

namespace App\Http\Controllers;

use App\Service\PasswordService;
use App\Service\TokenService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    private $tokenService;
    private $passwordService;

    public function __construct(TokenService $tokenService, PasswordService $passwordService)
    {
        $this->tokenService = $tokenService;
        $this->passwordService = $passwordService;
    }

    public function createToken(Request $request)
    {
        $password = $request->input('password');
        if ($this->passwordService->checkPassword($password)) {
            $token = $this->tokenService->createToken();
            return response()->json([
                'code' => 1000,
                'token' => $token
            ]);
        } else {
            return response()->json([
                'code' => 8003,
                'message' => '密码错误'
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        $password = $request->input('password');
        $newPassword = $request->input('newPassword');
        if ($this->passwordService->checkPassword($password))
            $this->passwordService->changePassword($newPassword);
        else {
            return response()->json([
                'code' => 8003,
                'message' => '密码错误'
            ]);
        }

    }

    public function checkToken($tokenId, $tokenContent)
    {
        if ($tokenId == 0) {
            return response()->json([
                'code' => 6001,
                'message' => 'token无效'
            ]);
        } else {
            $res = $this->tokenService->getToken($tokenId);
            $time = new Carbon();
            if ($res->token_content == $tokenContent && $res->expired_at > $time) {
                return response()->json([
                    'code' => 1000,
                    'message' => '验证通过'
                ]);
            } else {
                return response()->json([
                    'code' => 6001,
                    'message' => 'token过期'
                ]);
            }
        }
    }
}
