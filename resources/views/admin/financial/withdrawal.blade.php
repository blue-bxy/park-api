@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{asset('css/sidebar-menu.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/income.css')}}">
    <link href="{{asset('layui/layui/css/layui.css')}}" rel="stylesheet" />
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection

@section('content')
<!-- 侧边栏 -->
<aside class="main-sidebar">
    <section  class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="{{route('admin.orders.index')}}"><i class="fa fa-circle-o text-red"></i> <span>订单管理</span></a></li>
            <li><a href="{{route('admin.Withdrawal.index')}}"><i class="fa fa-circle-o text-yellow"></i> <span>提现管理</span></a></li>
            <li><a href="{{route('admin.ElectronicBilling.index')}}"><i class="fa fa-circle-o text-aqua"></i> <span>电子支付对账</span></a></li>
            <li><a href="{{route('admin.SettleOrder.index')}}"><i class="fa fa-circle-o text-aqua"></i> <span>电子支付结算订单</span></a></li>
            <li><a href="{{route('admin.ReviewWithdrawal.index')}}"><i class="fa fa-circle-o text-aqua"></i> <span>审核提现</span></a></li>
            <li><a href="{{route('admin.PaymentAccount.index')}}"><i class="fa fa-circle-o text-aqua"></i> <span>收款账户</span></a></li>
        </ul>
    </section>
</aside>
<div class="float">
    <div class="float_flex">
        <!-- 车厂管理车厂信息 -->
        <div class="button">
            <button type="button" class="btn btn-default" style=" background-color:#f8f8f8;border-color: #f8f8f8;">
                <span class="glyphicon glyphicon-th-large"></span>
            </button>
            <div class="breadcrumb_but">
                <ul class="breadcrumb">
                    <li>财务管理</li>
                    <li>预约提现</li>
                </ul>
            </div>
        </div>
        <!-- 日期类型 -->
        <blockquote class="layui-elem-quote">
            <form action="{{url('AppointmentWithdrawal')}}" method="get" id="searchFrm" lay-filter="searchFrm" class="layui-form layui-form-pane">
                <div class="layui-form-item">
                    <div class="layui-input-inline">
                        <input type="hidden" name="status" value="{{$status}}" placeholder="" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-inline">
                        <div class="layui-form-mid">
                            项目名称:
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" name="project_name" placeholder="" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-form-mid">
                            申请人:
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" name="applicant" placeholder="" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-form-mid">
                            提现人员类型:
                        </div>
                        <div class="layui-input-inline">
                            <select name="withdrawal_person_type">
                                <option value="">请选择</option>
                                <option value="1">车场提现</option>
                                <option value="2">车主提现</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-form-mid">
                            进度状态:
                        </div>
                        <div class="layui-input-inline">
                            <select name="progress_status">
                                <option value="">请选择</option>
                                <option value="1">已完成</option>
                                <option value="2">审核中</option>
                                <option value="3">已驳回</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-form-mid">
                            申请时间:
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
                        <div class="layui-input-inline">
                            <input type="submit" class="layui-btn" value="查询">
                        </div>
                    </div>
                </div>
            </form>
        </blockquote>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{route('admin.Withdrawal.index')}}">全部{{count($data)}}</a></li>
        <li><a href="{{route('admin.Withdrawal.index')}}?status=1">待处理</a></li>
        <li><a href="{{route('admin.Withdrawal.index')}}?status=2">汇款中</a></li>
        <li><a href="{{route('admin.Withdrawal.index')}}?status=3">已完成</a></li>
        <div class="Order_input" style="margin-left:92%;">
            <button type="button" class="btn btn-primary" id="excelbutton">生成报表</button></td>
        </div>
    </ul>
    <!-- 表格 -->
    <div class="table_name">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>提现单编号</th>
                <th>提现人员类型</th>
                <th>申请时间</th>
                <th>申请金额（元）</th>
                <th>申请人</th>
                <th>项目名称</th>
                <th>物业公司</th>
                @if($status==0)
                    <th>状态</th>
                @endif
                @if($status==0 || $status==3)
                    <th>完成时间</th>
                @endif
                @if($status==2 || $status==3)
                    <th>审核人</th>
                    <th>审核时间</th>
                @endif
                @if($status==3)
                    <th>备注</th>
                @endif
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($data) && $status!=0)
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->withdrawal_no}}</td>
                        <td>{{}}</td>
                        <td>{{$item->apply_time}}</td>
                        <td>{{$item->apply_money}}</td>
                        <td>{{$item->applicant}}</td>
                        <td>{{$item->park->project_name}}</td>
                        @if($item->status!=3)
                            <td>{{$item->property->name}}</td>
                        @endif
                        @if($item->status==3)
                            <td>{{$item->completion_time}}</td>
                        @endif
                        @if($item->status==2 || $item->status==3)
                            <td>{{$item->reviewer}}</td>
                            <td>{{$item->audit_time}}</td>
                        @endif
                        @if($item->status==3)
                            <td>{{$item->remark}}</td>
                        @endif
                        <td>
                            @if($item->status==1)
                                <button type="button" class="btn btn-primary">查看详情</button>
                            @endif
                            @if($item->status==2 || $item->status==3)
                                <button type="button" class="btn btn-primary">查看明细</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @elseif(!empty($data) && $status==0)
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->withdrawal_no}}</td>
                        <td>
                            @if($item->withdrawal_person_type==1)
                                车场提现
                            @elseif($item->withdrawal_person_type==2)
                                车主提现
                            @endif
                        </td>
                        <td>{{$item->apply_time}}</td>
                        <td>{{$item->apply_money}}</td>
                        <td>{{$item->applicant}}</td>
                        <td>{{$item->park->project_name}}</td>
                        <td>{{$item->property->name}}</td>
                        <td>
                            @if($item->progress_status==1)
                                已完成
                            @endif
                            @if($item->progress_status==2)
                                审核中
                            @endif
                            @if($item->progress_status==3)
                                已驳回
                            @endif
                        </td>
                            <td>{{$item->completion_time}}</td>
                        <td>
                            <button type="button" class="btn btn-primary">查看详情</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <!-- 分页 -->
    <div class="ination ">
        <div class="ination_row">
            <div class="ination_ul">
                <ul class="pagination">
                    {{$data->links()}}
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
    <script>
        layui.use('form',function () {
            var form=layui.form;
            form.render();
        });

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

        $("#excelbutton").click(function () {
            layui.use('layer',function () {
                var loading = layer.msg('正在生成', {icon: 16, shade: 0.3, time:0});
                var status={{ isset($_GET['status'])?$_GET['status']:0 }};
                $.ajax({
                    url:"{{route('admin.Withdrawal.create')}}",
                    data:{'status':status},
                    type:'get',
                    dataType:'json',
                    success:function (res) {
                        if(res.code==1){
                            layer.msg(res.msg, {icon: 6, time: 1500, shade: 0.1});
                            layer.close(loading);
                        }else{
                            layer.msg(res.msg, {icon: 2, time: 1500, shade: 0.1});
                        }
                    }
                })
            })
        })
    </script>
@endsection
