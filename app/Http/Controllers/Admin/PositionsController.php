<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Http\Requests\PositionRequest;
use App\Models\Department;
use App\Models\Position;
use App\Services\DepartmentService;
use App\Services\PositionService;
use Illuminate\Http\Request;

class PositionsController extends BaseController
{
    protected $service;

    public function __construct(PositionService $service)
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
     * @param PositionRequest $request
     * @return \App\Http\Resources\PositionResource
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function store(PositionRequest $request)
    {
        $attributes = $request->all();

        if (!$request->has('guard_name')) {
            $attributes['guard_name'] = $request->input('guard_name', 'admin');
        }

        return $this->service->store($attributes);
    }

    /**
     * update
     *
     * @param PositionRequest $request
     * @param Position $position
     * @return \App\Http\Resources\PositionResource
     */
    public function update(PositionRequest $request, Position $position)
    {
        $attributes = $request->all();

        if (!$request->has('guard_name')) {
            $attributes['guard_name'] = $request->input('guard_name', 'admin');
        }

        return $this->service->update($position, $attributes);
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
     * @param Position $position
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Position $position)
    {
        $this->service->destroy($position);

        return $this->responseSuccess();
    }
}
