<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 1/6/2019
 * Time: 9:20 PM
 */

namespace App\Traits;


use App\Http\Controllers\Helpers\Helpers;
use App\Models\Role;
use App\Models\TypeUser;
use App\User;
use \Illuminate\Support\Collection;
use \RuntimeException;

trait UserRoleTrait
{
    use TypeUserTrait;

    public function getTypeUserId($title)
    {
        $typeUser = TypeUser::where('name', '=', $title)->first();
        if (isset($typeUser)) {
            return $typeUser->id;
        }
        return null;
    }

    /**
     * @param $title
     * @param User $user
     * @throws \Exception
     */

    public function makeSyncUserRoles($title, User $user): void
    {
        $assigned_roles = [];
        if ($this->isTypeUserExists($title)) {
            $assigned_roles[] = $this->getAssignedRoles($title);
        } else {
            throw new \Exception('The type of the user does not exist.');
        }
        $user->roles()->sync($assigned_roles);
    }

    public function getAssignedRoles($title): array
    {
        $assigned_roles = [];
        $roles = $this->getAllRoleIds();
        $default_user_roles = $this->getDefaultRoles($title);
        foreach ($default_user_roles as $role) {
            $assigned_roles[] = Helpers::getIdInArray($roles, $role);
        }
        return $assigned_roles;
    }

    public function getDefaultRoles($type_user): array
    {
        switch ($type_user) {
            case 'super_admin':
                {
                    return [
                        'manage_site_settings',
                        'create_users',
                        'delete_users',
                        'edit_users',
                        'list_users',
                        'import',
                        'export',
                        'publish_posts',
                        'edit_posts',
                        'edit_published_posts',
                        'delete_posts',
                        'delete_published_posts',
                        'delete_others_posts',
                        'approval_posts',
                        'approval_users',
                        'create_forum',
                        'delete_forum',
                        'update_forum',
                        'create_profile',
                        'update_profile',
                        'create_other_profile',
                        'update_other_profile',
                    ];
                    break;
                }
            case 'admin':
                {
                    return [
                        'manage_site_settings',
                        'import',
                        'export',
                        'publish_posts',
                        'edit_posts',
                        'edit_published_posts',
                        'delete_posts',
                        'delete_published_posts',
                        'delete_others_posts',
                        'create_forum',
                        'delete_forum',
                        'update_forum',
                        'create_profile',
                        'update_profile'
                    ];
                    break;
                }
            case 'user':
                {
                    return [
                        'publish_posts',
                        'edit_posts',
                        'edit_published_posts',
                        'delete_posts',
                        'delete_published_posts',
                        'delete_others_posts',
                        'create_forum',
                        'delete_forum',
                        'update_forum',
                        'create_profile',
                        'update_profile'
                    ];
                    break;
                }

            default:
                {
                    return [];
                }
        }
    }


    public function getAllRoleIds(): Collection
    {
        return Role::all()->pluck('name', 'id');
    }
}
