<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Http\Resources\Admin\AdminUserResource;
use App\Http\Resources\DepartmentResource;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Permission;
use App\Services\DepartmentService;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class AdminController extends BaseController
{

    public function index(Request $request)
    {
        // 分页数量
        $perPage = $request->input('per_page');

        $query = Admin::query();

        $query->search($request);

        $query->with('departments', 'roles');

        $admins = $query->latest()->paginate($perPage);

        return AdminUserResource::collection($admins);
    }

    /**
     * store
     *
     * @param AdminUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiResponseException
     */
    public function store(AdminUserRequest $request)
    {
        (new Admin)->addUser($request);

        return $this->responseSuccess();
    }

    /**
     * update
     *
     * @param AdminUserRequest $request
     * @param Admin $admin
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiResponseException
     */
    public function update(AdminUserRequest $request, Admin $admin)
    {
        $admin->updateUser($request);

        return $this->responseSuccess();
    }

    /**
     * destroy
     *
     * @param Admin $admin
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return $this->responseSuccess();
    }

    /**
     * nodes
     *
     * @param Request $request
     * @param DepartmentService $service
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function nodes(Request $request, DepartmentService $service)
    {
        $request->validate(['guard_name' => 'sometimes|required|string']);

        return $service->nodes($request->input('guard_name', 'admin'));
    }

    /**
     * 用户权限
     *
     * @param Request $request
     * @param Admin $admin
     * @param PermissionService $service
     * @return array
     */
    public function permissions(Request $request, Admin $admin, PermissionService $service)
    {
        return $service->getUserPermissions($admin);
    }

    /**
     * 同步用户权限
     *
     * 一般来讲我们通常是使用角色权限
     *
     * @param Request $request
     * @param Admin $admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncPermissions(Request $request, Admin $admin)
    {
        $request->validate(['permissions' => 'required']);

        $permissions = $request->input('permissions');

        if (is_string($permissions)) {
            $permissions = explode(',', $permissions);
        }

        if (!is_array($permissions)) {
            return $this->responseFailed('权限id 类型错误', 40022);
        }

        $admin->syncPermissions($permissions);

        return $this->responseSuccess();
    }
}
