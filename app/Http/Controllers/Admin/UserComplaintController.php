<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\ComplaintResource;
use App\Models\Users\UserComplaint;
use Illuminate\Http\Request;

class UserComplaintController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 分页数量
        $perPage = $request->input('per_page');

        //查询
        $query = UserComplaint::query();

        $query->search($request);

        $query->has('user')->with('user','order');

        $complaint = $query->latest()->paginate($perPage);

        return ComplaintResource::collection($complaint);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $complaint=UserComplaint::query()->with('user','order')->where('id',$id)->get();
        return ComplaintResource::collection($complaint);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $handling_state=$request->input('handling_state');
        $complaint = UserComplaint::find($id);
        $complaint->handling_state=$handling_state;
        $complaint->handling_person=$request->user()->name;
        $complaint->handling_time=now()->toDateTimeString();
        $res=$complaint->save();
        if ($res){
            // 成功
            return $this->responseSuccess();
        } else{
            //失败
            return $this->responseFailed('处理失败！');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
