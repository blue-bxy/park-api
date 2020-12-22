<?php


namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AdminUserRequest;
use App\Http\Resources\Admin\PropertiesResource;
use App\Models\Property;
use App\Services\DepartmentService;
use App\Services\PermissionService;
use Illuminate\Http\Request;

/**
 * 物业用户控制器
 * Class PropertiesController
 * @package App\Http\Controllers\Admin
 */
class PropertiesController extends BaseController
{
    /**
     * index
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // 分页数量
        $perPage = $request->input('per_page');

        $query = Property::query();

        $query->search($request);

        $query->with('departments', 'roles','park');

        $admins = $query->latest()->paginate($perPage);

        return PropertiesResource::collection($admins);
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
        (new Property)->addUser($request);

        return $this->responseSuccess();
    }

    /**
     * update
     *
     * @param AdminUserRequest $request
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiResponseException
     */
    public function update(AdminUserRequest $request, Property $property)
    {
        $property->updateUser($request);

        return $this->responseSuccess();
    }

    /**
     * destroy
     *
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Property $property)
    {
        $property->delete();

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
        return $service->nodes('property');
    }

    /**
     * 用户权限
     *
     * @param Request $request
     * @param Property $property
     * @param PermissionService $service
     * @return array
     */
    public function permissions(Request $request, Property $property, PermissionService $service)
    {
        return $service->getUserPermissions($property);
    }

    /**
     * 同步用户权限
     *
     * 一般来讲我们通常是使用角色权限
     *
     * @param Request $request
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncPermissions(Request $request, Property $property)
    {
        $request->validate(['permissions' => 'required']);

        $permissions = $request->input('permissions');

        if (is_string($permissions)) {
            $permissions = explode(',', $permissions);
        }

        if (!is_array($permissions)) {
            return $this->responseFailed('权限id 类型错误', 40022);
        }

        $property->syncPermissions($permissions);

        return $this->responseSuccess();
    }

    public function getPark($id) {
        $property = Property::query()
            ->select('id')
            ->with('park:id,project_name,property_id')
            ->find($id);
        if (empty($property)) {
            return $this->responseNotFound();
        }
        return $this->responseData($property);
    }
}
