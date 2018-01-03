<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class PasswordService
{
    public function insertPassword(){

    }
    public function changePassword(){

    }
    public function checkPassword($password){
        $passwordValue=DB::table('passwords')->where('id',1)->first()->password_value;
        if ($password==$passwordValue){
            return true;
        }
        else
            return false;
    }
}