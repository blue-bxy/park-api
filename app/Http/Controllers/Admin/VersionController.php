<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\VersionResource;
use App\Models\Admin;
use App\Models\Users\Version;
use Illuminate\Http\Request;

class VersionController extends BaseController
{
    /**
     *配置信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $per_page = $request->input('per_page');

        $query = Version::query();

        $query->with('user');

        $data = $query->search($request)->latest()->paginate($per_page);

        return VersionResource::collection($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     *新增内部版本和适用版本
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['user_id'] = ($request->user())->id;

        $res = Version::create($data);

        if(!$res){
            return $this->responseFailed();
        }

        return $this->responseSuccess('发布成功！');
    }

    /**
     *明细
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Version::find($id);

        $data['name'] = (Admin::find($data['user_id']))->name;

        $data['date'] = $data['created_at']->format('Y-m-d');

        return $this->responseData($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 修改
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // 版本
        $data = array();

        $data['version_no'] = $request->input('version_no');

        $data['platform'] = $request->input('platform');

        $data['update_description'] = $request->input('update_description');

        $data['resource_url'] = $request->input('resource_url');

        $data['is_force'] = $request->input('is_force');

        $res = Version::where('id',$id)->update($data);

        if(!$res){
            return $this->responseFailed();
        }

        return $this->responseSuccess('修改成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = Version::where('id',$id)->delete();

        if(!$res){
           return $this->responseFailed();
        }

        return $this->responseSuccess('删除成功！');
    }
}
