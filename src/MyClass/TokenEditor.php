<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 20.05.2018
 * Time: 11:01
 */

namespace App\MyClass;


class TokenEditor
{
    private $token;

    public function __construct()
    {
        $this->token = str_replace("/", "", password_hash(  rand(0, 10000) , PASSWORD_DEFAULT));
    }

    public function getToken()
    {
        return $this->token;
    }
}