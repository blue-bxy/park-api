@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{asset('css/sidebar-menu.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/income.css')}}">
    <link href="{{asset('layui/layui/css/layui.css')}}" rel="stylesheet" />
    <link href="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection

@section('content')
    <!-- 侧边栏 -->
    <aside class="main-sidebar">
        <section  class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{route('admin.customers.index')}}"><i class="fa fa-circle-o text-red"></i> <span>客户管理</span></a></li>
                <li><a href="{{route('admin.signing.index')}}"><i class="fa fa-circle-o text-yellow"></i> <span>签约管理</span></a></li>
                <li><a href="payment.html"><i class="fa fa-circle-o text-aqua"></i> <span>合同管理</span></a></li>
                <li><a href="Payment_order.html"><i class="fa fa-circle-o text-aqua"></i> <span>审批管理</span></a></li>
            </ul>
        </section>
    </aside>
    <div class="float">
        <div class="float_flex">
            <div class="button">
                <button type="button" class="btn btn-default" style=" background-color:#f8f8f8;border-color: #f8f8f8;">
                    <span class="glyphicon glyphicon-th-large"></span>
                </button>
                <div class="breadcrumb_but">
                    <ul class="breadcrumb">
                        <li>销售系统</li>
                        <li>签约管理</li>
                    </ul>
                </div>
            </div>
            <!-- 日期类型 -->
            <blockquote class="layui-elem-quote">
                <form action="{{url('signing')}}" method="get" id="searchFrm"  class="layui-form layui-form-pane">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                车场名称:
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="park_name" placeholder="" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                签约时间:
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="starttime"  placeholder="" onfocus="lay_date(this)" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid">
                                -
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="endtime" placeholder="" onfocus="lay_date(this)" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                停车场类型:
                            </div>
                            <div class="layui-input-inline">
                                <select name="park_type" lay-verify="required">
                                    <option value="">请选择</option>
                                    <option value="1">室内</option>
                                    <option value="2">室外</option>
                                    <option value="3">室内+室外</option>
                                    <option value="4">其他</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                所属集团:
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="group_name" placeholder="" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                签约状态:
                            </div>
                            <div class="layui-input-inline">
                                <select name="status" lay-verify="required">
                                    <option value="">请选择</option>
                                    <option value="0">未签约</option>
                                    <option value="1">已签约</option>
                                    <option value="2">已解约</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <input type="submit" class="layui-btn" value="查询">
                            </div>
                        </div>
                    </div>
                </form>
            </blockquote>
        </div>
        <ul class="nav nav-tabs">
            <li class="active"><a href="Money.html">签约项目{{$signings->count()}}</a></li>
            <div class="Order_input" style="margin-left:92%;">
                <button type="button" class="btn btn-primary">生成报表</button></td>
            </div>
        </ul>
        <!-- 表格 -->
        <div class="table_name">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>停车场名称</th>
                    <th>停车场类型</th>
                    <th>所属集团</th>
                    <th>停车场地址</th>
                    <th>签约人</th>
                    <th>签约时间</th>
                    <th>签约状态</th>
                    <th>合同编号</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($data))
                    @foreach($data as $item)
                        <tr>
                            <td>{{$item['park_name']}}</td>
                            <td>
                                @if($item['park_type']==1)
                                    室内
                                @elseif($item['park_type']==2)
                                    室外
                                @elseif($item['park_type']==3)
                                    室内+室外
                                @else
                                    其他
                                @endif
                            </td>
                            <td>{{$item['group_name']}}</td>
                            <td>{{$item['park_address']}}</td>
                            <td>{{$item['signatory']}}</td>
                            <td>{{$item['contract_time']}}</td>
                            <td>
                                @if($item['contract_status']==0)
                                    未签约
                                @elseif($item['contract_status']==1)
                                    已签约
                                @elseif($item['contract_status']==2)
                                    已解约
                                @endif
                            </td>
                            <td>{{$item['contract_number']}}</td>
                            <td>
                                <a href="{{route('admin.signing.show', ['signing' => $item['id']])}}">查看详情</a>
                                <a href="{{route('admin.signing.edit', ['signing' => $item['id']])}}">修改</a>
                                <a href="{{route('admin.signing.create', ['signing' => $item['id']])}}">新建拜访记录</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" style="text-align: center">暂无数据</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        <!-- 分页 -->
        <div class="ination ">
            <div class="ination_row">
                <div class="ination_ul">
                    <ul class="pagination">
                        {{$signings->links()}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/sidebar-menu.js')}}"></script>
    <script src="{{asset('layui/layui/layui.js')}}" type="text/javascript"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script>
        layui.use('form',function () {
            var form=layui.form;
            form.render();
        });
        $.sidebarMenu($('.sidebar-menu'))
        function lay_date(obj){
            layui.use('laydate', function(){
                var laydate = layui.laydate;
                //执行一个laydate实例
                laydate.render({
                    elem: obj
                    ,type: 'datetime'
                    ,show: true
                    ,format:'yyyy-MM-dd HH:mm:ss'
                });
            });
        }
    </script>
@endsection