<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Department;
use App\Models\Role;
use App\Services\PermissionService;
use App\Services\RolesService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RolesController extends BaseController
{
    protected $service;

    public function __construct(RolesService $service)
    {
        $this->service = $service;
    }

    /**
     * index
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return $this->service->paginate($request);
    }

    /**
     * store
     *
     * @param Request $request
     * @return RoleResource
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function store(Request $request)
    {
        return $this->service->store($request);
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
        return $this->service->update($request, $role);
    }

    /**
     * destroy
     *
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Role $role)
    {
        $this->service->destroy($request, $role);

        return $this->responseSuccess();
    }

    /**
     * 角色权限
     *
     * @param Request $request
     * @param Role $role
     */
    public function permissions(Request $request, Role $role)
    {
        return $this->service->permissions($role);
    }

    /**
     * 同步角色权限
     *
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncPermissions(Request $request, Role $role)
    {
        $request->validate(['permissions' => 'required']);

        $permissions = $request->input('permissions');

        if (is_string($permissions)) {
            $permissions = explode(',', $permissions);
        }

        if (!is_array($permissions)) {
            return $this->responseFailed('权限id 类型错误', 40022);
        }

        $this->service->sync($role, $permissions);

        return $this->responseSuccess();
    }
}
