<?php

namespace App\Traits\Roles;

use Illuminate\Support\Facades\DB;

trait RolesAndPermissionsHelpersTrait
{

    /**
     * Delete a permission.
     *
     * @param int $id The identifier
     *
     * @return collection
     */
    public function deletePermission($id)
    {
        $permission = $this->getPermission($id);
        $permission->delete();
        return $permission;
    }

    /**
     * Gets the roles.
     *
     * @return collection The roles.
     */
    public function getRoles()
    {
        //return \Spatie\Permission\Models\Role::class::all();
        return config('permission.models.role')::all();
    }

    /**
     * Gets the permissions.
     *
     * @return collection The permissions.
     */
    public function getPermissions()
    {
        //return \Spatie\Permission\Models\Permission::class::where('parent_id', '!=', 0)->get();
        //return \Spatie\Permission\Models\Permission::class::all();
        return config('permission.models.permission')::all();
    }
    /**
     * Gets the role.
     *
     * @param int $id The identifier
     *
     * @return collection The role.
     */
    public function getRole($id)
    {
        return config('permission.models.role')::findOrFail($id);
    }
    /**
     * Gets the permission.
     *
     * @param int $id The identifier
     *
     * @return collection The permission.
     */
    public function getPermission($id)
    {
        return config('permission.models.permission')::findOrFail($id);
    }

    /**
     * Get Soft Deleted Role.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response || collection
     */
    public function getDeletedRole($id)
    {
        $item = config('permission.models.role')::onlyTrashed()->where('id', $id)->get();
        if (count($item) != 1) {
            return abort(redirect('roles.index')
                ->with('error', trans('roles.errors.errorDeletedRoleNotFound')));
        }

        return $item[0];
    }

    /**
     * Gets the deleted roles.
     *
     * @return collection The deleted roles.
     */
    public function getDeletedRoles()
    {
        //return \Spatie\Permission\Models\Role::class::onlyTrashed();
        return config('permission.models.role')::onlyTrashed();
    }
    /**
     * Delete a role.
     *
     * @param int $id The identifier
     *
     * @return collection
     */
    public function deleteRole($id)
    {
        $role = $this->getRole($id);
        $this->removeUsersAndPermissionsFromRole($role);
        $role->delete();

        return $role;
    }
    /**
     * Destroy a role from storage.
     *
     * @param int $id The identifier
     *
     * @return collection
     */
    public function destroyRole($id)
    {
        $role = $this->getDeletedRole($id);

        $this->removeUsersAndPermissionsFromRole($role);
        $role->forceDelete();

        return $role;
    }

    /**
     * Destroy all the deleted roles.
     *
     * @return array
     */
    public function destroyAllTheDeletedRoles()
    {
        $deletedRoles = $this->getDeletedRoles()->get();
        $deletedRolesCount = $deletedRoles->count();
        $status = 'error';

        if ($deletedRolesCount > 0) {
            foreach ($deletedRoles as $deletedRole) {
                $this->removeUsersAndPermissionsFromRole($deletedRole);
                $deletedRole->forceDelete();
            }
            $status = 'success';
        }

        return [
            'status' => $status,
            'count'  => $deletedRolesCount,
        ];
    }
    /**
     * Removes an users and permissions from role.
     *
     * @param Role$role   The role
     *
     * @return void
     */
    public function removeUsersAndPermissionsFromRole($role)
    {

        $users = $this->getUsers();
        $roles = $this->getRoles();
        //$roles = $this->getDeletedRoles()->get();
        $sortedRolesWithUsers = $this->getSortedUsersWithRoles($roles, $users);
        $roleUsers = [];
        //return dd($sortedRolesWithUsers);
        // Remove Users Attached to Role
        foreach ($sortedRolesWithUsers as $sortedRolesWithUsersKey => $sortedRolesWithUsersValue) {

            // return dd($role);
            if ($sortedRolesWithUsersValue['role'] == $role) {
                $roleUsers[] = $sortedRolesWithUsersValue['users'];
            }
        }
        foreach ($roleUsers as $roleUserKey => $roleUserValue) {
            // return dd($roleUserValue[0]);
            if (!empty($roleUserValue)) {
                // $roleUserValue[$roleUserKey]->detachRole($role);
                $roleUserValue[0]->syncRoles('unverified');
                $role = config('permission.models.role')::findByName('unverified');
                //return dd($role);
                $permissions = $role->permissions;
                $roleUserValue[0]->syncPermissions($permissions);
            }
        }

        // Remove Permissions from Role
        // $this->removeAllPermissionsFromRole($role);

        //$role->detachAllPermissions();
    }
    public function removeAllPermissionsFromRole($role)
    {
        // $role = config('permission.models.role')::findByName($role);
        $permissions = $role->permissions;
        // return dd($permissions);
        foreach ($permissions as $permission) {
            $role->revokePermissionTo($permission);
        }
    }
    /**
     * Gets the deleted permissions.
     *
     * @return collection The deleted permissions.
     */
    public function getDeletedPermissions()
    {
        //return \Spatie\Permission\Models\Permission::class::onlyTrashed();
        return config('permission.models.permission')::onlyTrashed();
    }

    /**
     * Gets the users.
     *
     * @return collection The users.
     */
    public function getUsers()
    {
        return \App\Models\User::class::all();
    }

    /**
     * Gets the permissions with roles.
     *
     * @param int $roleId The role Id
     *
     * @return collection The permissions with roles.
     */
    public function getPermissionsWithRoles($roleId = null)
    {
        $query = DB::connection(config('roles.connection'))->table('role_has_permissions');

        if ($roleId) {
            //$query->where('role_id', '=', $roleId);
            $query->where('role_id', $roleId);
        }

        return $query->get();
    }

    /**
     * Gets the permission users.
     *
     * @param int $permissionId The permission identifier
     *
     * @return Collection The permission users.
     */
    public function getPermissionUsers($permissionId = null)
    {
        //==============
        $query = DB::connection(config('roles.connection'))->table('model_has_permissions');

        if ($permissionId) {
            $query->where('permission_id', '=', $permissionId);
        }

        return $query->get();
    }

    /**
     * Gets the permission models.
     *
     * @return The permission models.
     */
    public function getPermissionModels()
    {
        $permissionModel = config('permission.models.permission');

        return DB::table(config('permission.table_names.permissions'))->pluck('name')->merge(collect(class_basename(new $permissionModel())))->unique();
    }
    /**
     * Gets the permission item data.
     *
     * @param int $id The Permission ID
     *
     * @return array The Permission item data.
     */
    public function getPermissionItemData($id)
    {
        $permission = config('permission.models.permission')::findOrFail($id);
        $users = $this->getUsers();
        $roles = $this->getRoles();
        $permissions = $this->getPermissions();
        $sortedRolesWithUsers = $this->getSortedUsersWithRoles($roles, $users);
        $sortedPermissionsRolesUsers = $this->getSortedPermissonsWithRolesAndUsers($sortedRolesWithUsers, $permissions, $users);

        $data = [];

        foreach ($sortedPermissionsRolesUsers as $item) {
            if ($item['permission']->id === $permission->id) {
                $data = [
                    'item' => $item,
                ];
            }
        }

        return $data;
    }

    /**
     * Gets the role permissions identifiers.
     *
     * @param int $id The Role Id
     *
     * @return array The role permissions Ids.
     */
    public function getRolePermissionsIds($id)
    {
        $permissionPivots = $this->getPermissionsWithRoles($id);
        $permissionIds = [];

        if (count($permissionPivots) != 0) {
            foreach ($permissionPivots as $permissionPivot) {
                $permissionIds[] = $permissionPivot->permission_id;
            }
        }

        return $permissionIds;
    }

    /**
     * Gets the sorted users with roles.
     *
     * @param collection $roles The roles
     * @param collection $users The users
     *
     * @return collection The sorted users with roles.
     */
    public function getSortedUsersWithRoles($roles, $users)
    {
        $sortedUsersWithRoles = [];

        foreach ($roles as $rolekey => $roleValue) {
            $sortedUsersWithRoles[] = [
                'role'   => $roleValue,
                'users'  => [],
            ];
            foreach ($users as $user) {
                foreach ($user->roles as $userRole) {
                    if ($userRole->id === $sortedUsersWithRoles[$rolekey]['role']['id']) {
                        $sortedUsersWithRoles[$rolekey]['users'][] = $user;
                    }
                }
            }
        }

        return collect($sortedUsersWithRoles);
    }

    /**
     * Gets the sorted roles with permissions.
     *
     * @param collection $sortedRolesWithUsers The sorted roles with users
     * @param collection $permissions          The permissions
     *
     * @return collection The sorted roles with permissions.
     */
    public function getSortedRolesWithPermissionsAndUsers($sortedRolesWithUsers, $permissions)
    {
        $sortedRolesWithPermissions = [];
        $permissionsAndRoles = $this->getPermissionsWithRoles();

        foreach ($sortedRolesWithUsers as $sortedRolekey => $sortedRoleValue) {
            $role = $sortedRoleValue['role'];
            $users = $sortedRoleValue['users'];
            $sortedRolesWithPermissions[] = [
                'role'          => $role,
                'permissions'   => collect([]),
                'users'         => collect([]),
            ];

            // Add Permission with Role
            foreach ($permissionsAndRoles as $permissionAndRole) {
                if ($permissionAndRole->role_id == $role->id) {
                    foreach ($permissions as $permissionKey => $permissionValue) {
                        if ($permissionValue->id == $permissionAndRole->permission_id) {
                            $sortedRolesWithPermissions[$sortedRolekey]['permissions'][] = $permissionValue;
                        }
                    }
                }
            }

            // Add Users with Role
            foreach ($users as $user) {
                foreach ($user->roles as $userRole) {
                    if ($userRole->id === $sortedRolesWithPermissions[$sortedRolekey]['role']['id']) {
                        $sortedRolesWithPermissions[$sortedRolekey]['users'][] = $user;
                    }
                }
            }
        }

        return collect($sortedRolesWithPermissions);
    }

    /**
     * Gets the sorted permissons with roles and users.
     *
     * @param collection $sortedRolesWithUsers The sorted roles with users
     * @param collection $permissions          The permissions
     * @param colection  $users                The users
     *
     * @return collection The sorted permissons with roles and users.
     */
    public function getSortedPermissonsWithRolesAndUsers($sortedRolesWithUsers, $permissions, $users)
    {
        $sortedPermissionsWithRoles = [];
        $permissionsAndRolesPivot = $this->getPermissionsWithRoles();
        $permissionUsersPivot = $this->getPermissionUsers();

        foreach ($permissions as $permissionKey => $permissionValue) {
            $sortedPermissionsWithRoles[] = [
                'permission'    => $permissionValue,
                'roles'         => $this->retrievePermissionRoles($permissionValue, $permissionsAndRolesPivot, $sortedRolesWithUsers),
                'users'         => $this->retrievePermissionUsers($permissionValue, $permissionsAndRolesPivot, $sortedRolesWithUsers, $permissionUsersPivot, $users),
            ];
        }

        return collect($sortedPermissionsWithRoles);
    }

    /**
     * Gets the dashboard data.
     *
     * @return array The dashboard data and view.
     */
    public function getDashboardData()
    {
        $roles = $this->getRoles();
        $permissions = $this->getPermissions();
        //  $deletedRoleItems = $this->getDeletedRoles();
        //  $deletedPermissionsItems = $this->getDeletedPermissions();
        $users = $this->getUsers();
        $sortedRolesWithUsers = $this->getSortedUsersWithRoles($roles, $users);
        $sortedRolesWithPermissionsAndUsers = $this->getSortedRolesWithPermissionsAndUsers($sortedRolesWithUsers, $permissions);
        $sortedPermissionsRolesUsers = $this->getSortedPermissonsWithRolesAndUsers($sortedRolesWithUsers, $permissions, $users);

        $data = [
            'roles'                              => $roles,
            'permissions'                        => $permissions,
            // 'deletedRoleItems'                   => $deletedRoleItems,
            //  'deletedPermissionsItems'            => $deletedPermissionsItems,
            'users'                              => $users,
            'sortedRolesWithUsers'               => $sortedRolesWithUsers,
            'sortedRolesWithPermissionsAndUsers' => $sortedRolesWithPermissionsAndUsers,
            'sortedPermissionsRolesUsers'        => $sortedPermissionsRolesUsers,
        ];

        $view = 'livewire.admin.roles.crud.dashboard';

        return [
            'data' => $data,
            'view' => $view,
        ];
    }


    /**
     * Retrieves permission roles.
     *
     * @param Permission $permission               The permission
     * @param Collection $permissionsAndRolesPivot The permissions and roles pivot
     * @param Collection $sortedRolesWithUsers     The sorted roles with users
     *
     * @return Collection of permission roles
     */
    public function retrievePermissionRoles($permission, $permissionsAndRolesPivot, $sortedRolesWithUsers)
    {
        $roles = [];
        foreach ($permissionsAndRolesPivot as $permissionAndRoleKey => $permissionAndRoleValue) {
            if ($permission->id === $permissionAndRoleValue->permission_id) {
                foreach ($sortedRolesWithUsers as $sortedRolesWithUsersItemKey => $sortedRolesWithUsersItemValue) {
                    if ($sortedRolesWithUsersItemValue['role']->id === $permissionAndRoleValue->role_id) {
                        $roles[] = $sortedRolesWithUsersItemValue['role'];
                    }
                }
            }
        }

        return collect($roles);
    }


    /**
     * Retrieves permission users.
     *
     * @param Permission $permission               The permission
     * @param Collection $permissionsAndRolesPivot The permissions and roles pivot
     * @param Collection $sortedRolesWithUsers     The sorted roles with users
     * @param Collection $permissionUsersPivot     The permission users pivot
     * @param Collection $users                    The users
     *
     * @return Collection of Permission Users
     */
    public function retrievePermissionUsers($permission, $permissionsAndRolesPivot, $sortedRolesWithUsers, $permissionUsersPivot, $appUsers)
    {
        $users = [];
        $userIds = [];
        //return dd($permissionsAndRolesPivot);
        // Get Users from permissions associated with roles
        foreach ($permissionsAndRolesPivot as $permissionsAndRolesPivotItemKey => $permissionsAndRolesPivotItemValue) {

            if ($permission->id === $permissionsAndRolesPivotItemValue->permission_id) {
                foreach ($sortedRolesWithUsers as $sortedRolesWithUsersItemKey => $sortedRolesWithUsersItemValue) {
                    if ($permissionsAndRolesPivotItemValue->role_id === $sortedRolesWithUsersItemValue['role']->id) {
                        foreach ($sortedRolesWithUsersItemValue['users'] as $sortedRolesWithUsersItemValueUser) {
                            $users[] = $sortedRolesWithUsersItemValueUser;
                        }
                    }
                }
            }
        }

        // Setup Users IDs from permissions associated with roles
        foreach ($users as $userKey => $userValue) {

            $userIds[] = $userValue->id;
        }
        // return dd($permissionUsersPivot);
        // Get Users from permissions pivot table that are not already in users from permissions associated with roles
        foreach ($permissionUsersPivot as $permissionUsersPivotKey => $permissionUsersPivotItem) {

            if (!in_array($permissionUsersPivotItem->model_id, $userIds) && $permission->id === $permissionUsersPivotItem->permission_id) {
                foreach ($appUsers as $appUser) {
                    if ($appUser->id === $permissionUsersPivotItem->model_id) {
                        $users[] = $appUser;
                    }
                }
            }
        }

        return collect($users);
    }


    /**
     * Restore all the deleted roles.
     *
     * @return array
     */
    public function restoreAllTheDeletedRoles()
    {
        $deletedRoles = $this->getDeletedRoles()->get();
        $deletedRolesCount = $deletedRoles->count();
        $status = 'error';

        if ($deletedRolesCount > 0) {
            foreach ($deletedRoles as $deletedRole) {
                $deletedRole->restore();
            }
            $status = 'success';
        }

        return [
            'status' => $status,
            'count'  => $deletedRolesCount,
        ];
    }
    /**
     * Restore a deleted role.
     *
     * @param int $id The identifier
     *
     * @return collection
     */
    public function restoreDeletedRole($id)
    {
        $role = $this->getDeletedRole($id);
        $role->restore();

        return $role;
    }
}
