<?php

namespace App\Http\Controllers;

use App\Service\PasswordService;
use App\Service\TokenService;
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
        }
        else{
            return response()->json([
                'code'=> 8003,
                'message' => '密码错误'
            ]);
        }
    }
    public function changePassword(Request $request){
        $password=$request->input('password');
        $this->passwordService->changePassword($password);
    }

}
