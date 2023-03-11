<?php

namespace App\Enums;

class UserRole
 {
    const ROLE_ADMIN = 0;
    const ROLE_USER = 1;
    const UNKNOWN = 2;

    public static function parse($type){
        switch (strtolower($type)){
            case "admin":
                return self::ROLE_ADMIN;

            case "user":
                return self::ROLE_USER;

            default:
                return self::UNKNOWN;
        }
    }



}
