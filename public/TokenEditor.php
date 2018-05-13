<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 13.05.2018
 * Time: 10:17
 */

namespace liw;


class TokenEditor
{
    public function tokenCreate()
    {
        return str_replace("/", "", password_hash(  rand(0, 10000) , PASSWORD_DEFAULT));
    }

}