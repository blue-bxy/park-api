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
                <li><a href="{{route('admin.AppointmentWithdrawal.index')}}"><i class="fa fa-circle-o text-yellow"></i> <span>预约提现</span></a></li>
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
                        <li>审核提现</li>
                    </ul>
                </div>
            </div>
            <!-- 日期类型 -->
            <blockquote class="layui-elem-quote">
                <form action="{{url('ReviewWithdrawal')}}" method="get" id="searchFrm" lay-filter="searchFrm" class="layui-form layui-form-pane">
                    <div class="layui-form-item">
                        <div class="layui-input-inline">
                            <input type="hidden" name="status"  placeholder="" value="{{$status}}" autocomplete="off" class="layui-input">
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
                            <div class="layui-form-mid">
                                申请人:
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="applicant" placeholder="" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                停车场名称:
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="park_name" placeholder="" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                审核人:
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="reviewer" placeholder="" autocomplete="off" class="layui-input">
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
        <ul class="nav nav-pills">
            <li role="presentation"><a href="{{route('admin.ReviewWithdrawal.index')}}?status=0">待审核</a></li>
            <li><a href="{{route('admin.ReviewWithdrawal.index')}}?status=1">已通过</a></li>
            <li><a href="{{route('admin.ReviewWithdrawal.index')}}?status=2">已驳回</a></li>
        </ul>
        <!-- 表格 -->
        <div class="table_name">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>停车场名称</th>
                    <th>提现金额</th>
                    <th>申请人</th>
                    <th>申请时间</th>
                    @if($status!=0)
                        <th>审核人</th>
                        <th>审核时间</th>
                    @endif
                    @if($status==2)
                        <th>驳回理由</th>
                    @endif
                    <th>备注</th>
                    @if($status==0)
                        <th>操作</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @if(!empty($data))
                    @foreach($data as $item)
                        <tr>
                            <td>{{$item->park->project_name}}</td>
                            <td>{{$item->withdrawal_amount}}</td>
                            <td>{{$item->applicant}}</td>
                            <td>{{$item->apply_time}}</td>
                            @if($item->status!=0)
                                <td>{{$item->reviewer}}</td>
                                <th>{{$item->audit_time}}</th>
                            @endif
                            @if($item->status==2)
                                <td>{{$item->rejection_reason}}</td>
                            @endif
                            <td>{{$item->remark}}</td>
                            @if($item->status==0)
                                <td><button type="button" class="btn btn-primary">审核</button></td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" style="text-align: center">暂无数据</td>
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
