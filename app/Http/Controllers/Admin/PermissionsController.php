<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PermissionsRequest;
use App\Models\Admin;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionsController extends BaseController
{
    /**
     * index
     *
     * @param Request $request
     * @param PermissionService $service
     * @return \Illuminate\Support\Collection
     */
    public function index(Request $request, PermissionService $service)
    {
        return $service->guard($request->input('guard_name'))->getTreeByParent(0);
    }

    /**
     * 添加权限
     *
     * @param PermissionsRequest $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse
     */
    public function store(PermissionsRequest $request)
    {
        $attributes = $request->all();

        $name = $request->input('name', '#');

        $attributes['name'] = $name == '#' ? '#-'.time() : $name;

        $result = (new Permission)->create($attributes);

        if (!$result) {
            return $this->responseFailed('权限添加失败', 40022);
        }

        return $result;
    }

    /**
     * show
     *
     * @param Request $request
     * @param $id
     * @param PermissionService $service
     * @return array
     */
    public function show(Request $request, $id, PermissionService $service)
    {
        $permissions = $service->guard($request->input('guard_name'))->getTreeByParent($id);

        return list_to_tree($permissions->toArray(), 'id', 'parent_id', 'children', $id);
    }

    /**
     * update
     *
     * @param PermissionsRequest $request
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PermissionsRequest $request, Permission $permission)
    {
        $result = $permission->fill($request->all())->save();

        if (!$result) {
            return $this->responseFailed('操作失败');
        }

        return $this->responseSuccess('操作成功');
    }

    /**
     * destroy
     *
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Permission $permission)
    {
        \DB::beginTransaction();
        try {
            $permission->load('children');

            if ($permission->children->isNotEmpty()) {
                return $this->responseFailed('不能删除主权限', 40022);
            }

            //如果该用户拥有权限
            //$permission->users()->detach();
            $permission->delete();
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            return $this->responseFailed('权限删除出错了', 40022);
        }

        return $this->responseSuccess('删除成功');
    }

    /**
     * 获取节点数据
     *
     * @param Request $request
     * @param PermissionService $service
     * @return array
     */
    public function top(Request $request, PermissionService $service)
    {
        return [
            'tree' => $service->guard($request->input('guard_name'))->getTree(),
            'guards' => $service->guards(),
            'menu' => $service->menus()
        ];
    }

    public function getRolePermissions(Request $request)
    {
        /** @var Admin $admin */
        $admin = $request->user();

        $rolePermission = $admin->getAllPermissions()->filter(function ($permission) {
            return $permission->level < 4;
        })->map(function ($permission) {
            return $permission->only(['id', 'parent_id', 'name', 'display_name', 'level', 'sort']);
        });

        return $rolePermission;
    }
}
