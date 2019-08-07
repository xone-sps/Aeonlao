<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 1/6/2019
 * Time: 9:27 PM
 */

namespace App\Traits;

use App\Models\TypeUser;

trait TypeUserTrait
{
    protected $column_name = 'name';
    protected static $adminTypeName = ['admin', 'super_admin'];

    public static function isAdminUser($user): bool
    {
        return in_array($user->type_of_user->name, self::$adminTypeName, true);
    }

    public static function getNonAdminUserIds()
    {
        return TypeUser::whereNotIn('name', self::$adminTypeName)->pluck('id')->toArray();
    }

    public function isTypeUserExists($type)
    {
        $typeUser = TypeUser::where($this->column_name, strtolower($type))->first();
        return isset($typeUser);
    }
}
