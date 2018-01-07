<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class PasswordService
{
    public function insertPassword()
    {

    }

    public function changePassword($password)
    {
            DB::table('passwords')->where('id', 1)->update(['password_value', $password]);
            return response()->json([
                'code' => 1000,
                'message' => '密码修改成功'
            ]);
    }

    public function checkPassword($password)
    {
        $passwordValue = DB::table('passwords')->where('id', 1)->first()->password_value;
        if ($password == $passwordValue) {
            return true;
        } else
            return false;
    }
}