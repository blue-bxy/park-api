<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DepartmentController extends BaseController
{
    protected $service;

    public function __construct(DepartmentService $service)
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
     * @param DepartmentRequest $request
     * @return \App\Http\Resources\DepartmentResource
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function store(DepartmentRequest $request)
    {
        return $this->service->store($request);
    }

    /**
     * update
     *
     * @param DepartmentRequest $request
     * @param Department $department
     * @return \App\Http\Resources\DepartmentResource
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        return $this->service->update($department, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * destroy
     *
     * @param Department $department
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Department $department)
    {
        $this->service->destroy($department);

        return $this->responseSuccess();
    }

    /**
     * roles
     *
     * @param Request $request
     * @param Department|null $department
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Collection
     */
    public function roles(Request $request, Department $department = null)
    {
        return $this->service->roles('admin', $department);
    }

    /**
     * syncRoles
     *
     * @param Request $request
     * @param Department $department
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function syncRoles(Request $request, Department $department)
    {
        $request->validate(['roles' => 'required']);

        $this->service->syncRoles($department, $request->input('roles'));

        $this->responseSuccess();
    }
}
