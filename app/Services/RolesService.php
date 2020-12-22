<?php

namespace App\Services;

use App\Exceptions\InvalidArgumentException;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesService
{
    /**
     * index
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function paginate(Request $request)
    {
        // 分页数量
        $perPage = $request->input('per_page');

        $query = Role::query();

        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%{$name}%");
        }

        if ($guard = $request->input('guard_name')) {
            $query->where('guard_name', $guard);
        }

        $query->with('departments');

        $roles = $query->paginate($perPage);

        return RoleResource::collection($roles)->additional([
            'guards' => PermissionService::$guards
        ]);
    }

    /**
     * store
     *
     * @param Request $request
     * @return RoleResource
     * @throws InvalidArgumentException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'guard_name' => 'required',
            'display_name' => 'required'
        ]);

        $exists = Role::query()
            ->where('name', $request->input('name'))
            ->where('guard_name', $request->input('guard_name'))
            ->exists();

        if ($exists) {
            throw new InvalidArgumentException('数据已存在，无法重复添加');
        }

        $role = \DB::transaction(function () use ($request) {
            /** @var Role $role */
            $role = Role::create([
                'name' => $request->input('name'),
                'guard_name' => $request->input('guard_name'),
                'display_name' => $request->input('display_name')
            ]);

            if ($department_id = $request->input('department_id')) {
                /** @var Department $department */
                $department = Department::find($department_id);

                $department->assignRole($role);
            }

            if ($permissions = $request->input('permissions')) {
                $role->permissions()->sync(explode(',', $permissions));
            }

            return $role;
        });

        return new RoleResource($role);
    }

    public function show(Request $request, Role $role)
    {
        $role->load('permissions');

        return new RoleResource($role);
    }

    /**
     * update
     *
     * @param Request $request
     * @param Role $role
     * @return RoleResource
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required',
            'guard_name' => 'required',
            'display_name' => 'required'
        ]);

        $role = \DB::transaction(function () use ($role, $request) {
            $role->fill($request->all())->save();

            if ($permissions = $request->input('permissions')) {
                $role->permissions()->sync(explode(',', $permissions));
            }

            return $role;
        });

        return new RoleResource($role);
    }

    /**
     * destroy
     *
     * @param Request $request
     * @param Role $role
     * @return bool|null
     * @throws \Exception
     */
    public function destroy(Request $request, Role $role)
    {
        return $role->delete();
    }

    public function permissions(Role $role)
    {
        $guard = $role->guard_name;

        $permissions = Permission::getPermissionsByGuard($guard);

        $uses_permissions = $role->getAllPermissions();

        $permissions = $permissions->map(function ($permission) use ($uses_permissions) {
            $select = $uses_permissions->filter(function ($user) use ($permission) {
                return $user->id == $permission->id;
            })->first();

            $permission->is_select = (bool) $select;

            return $permission->toArray();
        });

        return list_to_tree($permissions);
    }

    public function sync(Role $role, $permissions)
    {
        $role->syncPermissions($permissions);
    }
}
