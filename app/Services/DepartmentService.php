<?php


namespace App\Services;


use App\Exceptions\InvalidArgumentException;
use App\Http\Resources\DepartmentNodeResource;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\Property;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DepartmentService
{
    /**
     * 列表页
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function paginate(Request $request)
    {
        // $page = $request->input('page');
        // 分页数量
        $perPage = $request->input('per_page');

        $query = Department::query();

        // 限制物业端部门范围
        if ($request->user() instanceof Property) {
            $query->where('guard_name', 'property');
        } else {
            if ($guard_name = $request->input('guard_name')) {
                $query->where('guard_name', $guard_name);
            }
        }

        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%{$name}%");
        }

        $departments = $query->paginate($perPage);

        return DepartmentResource::collection($departments)->additional([
            'guards' => PermissionService::$guards
        ]);
    }

    /**
     * store
     *
     * @param Request $request
     * @return DepartmentResource
     * @throws InvalidArgumentException
     */
    public function store(Request $request)
    {
        $attributes = $request->all();

        $attributes['guard_name'] = $attributes['guard_name'] ?? self::getDefaultName($request);

        $query = Department::query();

        $exists = $query->where('name', $attributes['name'])
            ->where('guard_name', $attributes['guard_name'])
            ->exists();

        if ($exists) {
            throw new InvalidArgumentException('部门已存在，不能重复添加');
        }

        /** @var Department $department */
        $department = $query->create($attributes);

        if ($roles = $request->input('roles')) {
            $this->syncRoles($department, $roles);
        }

        return new DepartmentResource($department);
    }

    /**
     * update
     *
     * @param Department $department
     * @param Request $request
     * @return DepartmentResource
     * @throws InvalidArgumentException
     */
    public function update(Department $department, Request $request)
    {
        $attributes = $request->all();

        $attributes['guard_name'] = $attributes['guard_name'] ?? self::getDefaultName($request);

        $department->fill($attributes)->save();

        if ($roles = $request->input('roles')) {
            $this->syncRoles($department, $roles);
        }

        return new DepartmentResource($department);
    }

    /**
     * syncRoles
     *
     * @param Department $department
     * @param array|string $roles
     * @throws InvalidArgumentException
     */
    public function syncRoles(Department $department, $roles)
    {
        if (is_string($roles)) {
            $roles = explode(',', $roles);
        }

        if (!is_array($roles)) {
            throw new InvalidArgumentException('角色数据格式有误');
        }

        $department->syncRoles($roles);
    }

    /**
     * destroy
     *
     * @param Department $department
     * @return bool
     * @throws \Exception
     */
    public function destroy(Department $department)
    {
        \DB::transaction(function () use ($department) {
            $department->admin()->detach();

            $department->properties()->detach();

            $department->delete();
        });

        return true;
    }

    /**
     * nodes
     *
     * @param $guard_name
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function nodes($guard_name)
    {
        $query = Department::query();

        $query->where('guard_name', $guard_name);

        $query->with('roles');

        $items = $query->get();

        return DepartmentNodeResource::collection($items);
    }

    public function roles($guard_name, Department $department = null)
    {
        $query = Role::query();

        $query->select('id', 'name', 'guard_name');

        $query->where('guard_name', $guard_name);

        $roles = $query->get();

        $department_roles = new Collection();

        if ($department) {
            $department_roles = $department->roles;
        }

        return $roles->map(function ($role) use ($department_roles) {
            $result = $department_roles->filter(function ($filter) use ($role) {
                return $role->id == $filter->id;
            })->first();

            $role->is_select = (bool) $result;

            return $role;
        });
    }

    public static function getDefaultName(Request $request)
    {
        $user = $request->user();

        $default = config('auth.defaults.guard');

        if (!$user) {
            return $default;
        }

        $guards = collect(config('auth.guards'))
            ->map(function ($guard) {
                if (! isset($guard['provider'])) {
                    return;
                }

                return config("auth.providers.{$guard['provider']}.model");
            })
            ->filter(function ($model) use ($user) {
                return get_class($user) === $model;
            })
            ->keys();

        return $guards->first() ?? $default;
    }
}
