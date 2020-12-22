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
                        <li>收款账户</li>
                    </ul>
                </div>
            </div>
            <!-- 日期类型 -->
            <blockquote class="layui-elem-quote">
                <form action="{{url('PaymentAccount')}}" method="get" id="searchFrm" lay-filter="searchFrm" class="layui-form layui-form-pane">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                公司名称:
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="name" placeholder="" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                银行名称:
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="bank_name" placeholder="" autocomplete="off" class="layui-input">
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
                <button type="button" class="btn btn-primary"><a href="{{route('admin.PaymentAccount.create')}}" style="color: #fff;">添加账户</a></button>
            </div>
        </ul>
        <!-- 表格 -->
        <div class="table_name">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>公司名称</th>
                    <th>信用代码</th>
                    <th>银行名称</th>
                    <th>地址</th>
                    <th>银行账户</th>
                    <th>联系电话</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($data))
                    @foreach($data as $item)
                        <tr>
                            <td>{{$item->property->name}}</td>
                            <td>{{$item->credit_code}}</td>
                            <td>{{$item->bank_name}}</td>
                            <td>{{$item->address}}</td>
                            <td>{{$item->bank_account}}</td>
                            <td>{{$item->phone}}</td>
                            <td>{{$item->status}}</td>
                            <td>
                                <button type="button" class="btn btn-primary"><a href="{{route('admin.PaymentAccount.show', ['PaymentAccount' => $item->id])}}" style="color: #fff;">查看详情</a></button>
                                <button type="button" class="btn btn-primary"><a href="{{route('admin.PaymentAccount.edit', ['PaymentAccount' => $item->id])}}" style="color: #fff;">修改</a></button>
                            </td>
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
    </script>
@endsection
