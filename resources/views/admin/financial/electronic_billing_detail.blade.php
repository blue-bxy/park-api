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
                        <li>电子支付对账</li>
                        <li>对账明细</li>
                    </ul>
                </div>
            </div>
            <!-- 日期类型 -->
            <blockquote class="layui-elem-quote">
                <form action="{{url('ElectronicBilling')}}" method="get" id="searchFrm" lay-filter="searchFrm" class="layui-form layui-form-pane">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                结算日期:
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
            <div class="Order_input" style="margin-left:92%;">
                <button type="button" class="btn btn-primary">导出</button></td>
            </div>
        </ul>
        <!-- 表格 -->
        <div class="table_name">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>车牌号</th>
                    <th>停车场名称</th>
                    <th>预约时间</th>
                    <th>预约时长</th>
                    <th>预约费用</th>
                    <th>电子支付（元）</th>
                    <th>结算日期</th>
                    <th>结算金额（元）</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($data))
                    @foreach($data as $item)
                        <tr>
                            <td>{{$item->license_plate_number}}</td>
                            <td>{{$item->park->project_name}}</td>
                            <td>{{$item->appointment_time}}</td>
                            <td>{{$item->appointment_length}}</td>
                            <td>{{$item->booking_fee}}</td>
                            <td>{{$item->billing->electronic_payment}}</td>
                            <td>{{$item->settlement_date}}</td>
                            <td>{{$item->settlement_amount}}</td>
                            <td><button type="button" class="btn btn-primary">查看明细</button></td>
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
