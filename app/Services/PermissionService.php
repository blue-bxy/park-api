<?php


namespace App\Services;


use App\Models\Admin;
use App\Models\Permission;
use App\Models\Property;
use Illuminate\Database\Eloquent\Model;

class PermissionService
{
    protected $guard_name;
    // 平台
    public static $guards = [
        'admin' => '云端',
        'property' => '物业端'
    ];

    // 菜单等级
    public static $menuLevelMaps = [
        0 => '顶级权限',
        1 => '一级菜单',
        2 => '二级菜单',
        3 => '隐藏菜单',
    ];

    public function guards()
    {
        return self::$guards;
    }

    public function menus()
    {
        return self::$menuLevelMaps;
    }

    public function guard($name)
    {
        $this->guard_name = $name;

        return $this;
    }

    public function getTreeByParent($id)
    {
        $query = Permission::query();

        if ($id == 0) {
            $query->where('parent_id', 0);
        } else {
            $query->node($id);
        }

        if ($this->guard_name) {
            $query->where('guard_name', $this->guard_name);
        }

        $query->oldest('sort');

        $query->selectColumns();

        return $query->get();
    }

    public function getTopPermission($parent_id, $level = 0)
    {
        return Permission::query()
            ->where('parent_id', $parent_id)
            // ->where('level', $level)
            ->oldest('sort')
            ->select('id', 'parent_id', 'display_name')
            ->get();
    }

    /**
     * callback
     *
     * @return \Closure
     */
    protected function callback()
    {
        $select = [
            'id',
            'parent_id',
            'sort',
            'display_name',
            'name',
            'is_menu',
            'level',
            'guard_name'
        ];

        $callback = function ($query) use ($select) {
            return $query->select($select)->oldest('sort');
        };

        return $callback;
    }

    public function getTree()
    {
        return Permission::tree(function ($query) {
            if ($this->guard_name) {
                return $query->where('guard_name', $this->guard_name)->select('id', 'parent_id', 'display_name');
            }
            return $query->select('id', 'parent_id', 'display_name');
        });
    }

    public function getGuardPermissions($guard)
    {
        return Permission::getPermissionsByGuard($guard);
    }

    /**
     * getUserPermissions
     *
     * @param Model|Admin $user
     * @return array
     */
    public function getUserPermissions(Model $user)
    {
        $guard = $user->guard_name;

        $permissions = $this->getGuardPermissions($guard);

        $uses_permissions = $user->getAllPermissions();

        $permissions = $permissions->map(function ($permission) use ($uses_permissions) {
            $select = $uses_permissions->filter(function ($user) use ($permission) {
                return $user->id == $permission->id;
            })->first();

            $permission->is_select = (bool) $select;

            return $permission->toArray();
        });

        return list_to_tree($permissions);
    }
}
