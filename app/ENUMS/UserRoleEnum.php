<?php
namespace App\ENUMS;

class UserRoleEnum
{
    const ADMIN   = 'admin';
    const MANAGER = 'manager';
    const USER    = 'user';

    public static function getRoles()
    {
        return [
            self::ADMIN,
            self::MANAGER,
            self::USER,
        ];
    }
}
