<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CommentResource;
use App\Models\Parks\Park;
use App\Models\Users\UserComment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserCommentController extends BaseController
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
        $query = UserComment::query();

        $query->search($request);

        $query->with('order','user', 'park');

        if($status = $request->input('status')){
            $query->where('audit_status',$status);
        }

        $comment = $query->latest()->paginate($perPage);

        return CommentResource::collection($comment);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $comment = UserComment::query()->with('order','user')->where('id',$id)->get();

        return CommentResource::collection($comment);
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
        $audit_status=$request->input('audit_status', 1);
        $comment = UserComment::find($id);
        $comment->audit_status = $audit_status;
        //如果未通过  驳回理由
        if ($audit_status == '3'){
            $comment->refuse_reason = $request->input('refuse_reason');
        }
        $comment->auditor = $request->user()->name;
        $comment->audit_time = now();
        $res = $comment->save();
        if ($res){
            // 成功
            return $this->responseSuccess();

        } else{
            //失败
            return $this->responseFailed();
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
