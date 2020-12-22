@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{asset('css/sidebar-menu.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/income.css')}}">
    <link rel="stylesheet" href="{{asset('css/instrtion.css')}}">
    <link href="{{asset('layui/layui/css/layui.css')}}" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
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
                        <li>查看详情</li>
                    </ul>
                </div>
            </div>
            <!-- 日期类型 -->
            <div class="Order">
                <p style="font-size: 20px;margin-left: 4%;">详情信息</p></td>
            </div>
            <!-- 表格 -->
            <form  class="layui-form" id="form">
                <div class="instrtion">
                    <div class="instrtion_king">
                        <h5></h5>
                        <div class="instrtion_input">
                            <div class="instrtion_span">
                                <span>公司名称：</span>
                            </div>
                            <input type="text" name="property_name" id="" class="layui-input" value="{{$properties[0]}}" disabled="disabled">
                        </div>
                        <div class="instrtion_input">
                            <div class="instrtion_span">
                                <span>税号：</span>
                            </div>
                            <input type="text" name="ein" id="" class="layui-input" value="{{$data->ein}}" disabled="disabled">
                        </div>
                        <div class="instrtion_input">
                            <div class="instrtion_span">
                                <span>信用代码：</span>
                            </div>
                            <input type="text" name="credit_code" id="" class="layui-input" value="{{$data->credit_code}}" disabled="disabled">
                        </div>
                        <div class="instrtion_input">
                            <div class="instrtion_span">
                                <span>银行账户：</span>
                            </div>
                            <input type="text" name="bank_account" id="" class="layui-input" value="{{$data->bank_account}}" disabled="disabled">
                        </div>
                        <div class="instrtion_input">
                            <div class="instrtion_span">
                                <span>银行名称：</span>
                            </div>
                            <input type="text" name="bank_name" id="" class="layui-input" value="{{$data->bank_name}}" disabled="disabled">
                        </div>
                        <div class="instrtion_input">
                            <div class="instrtion_span">
                                <span>地址：</span>
                            </div>
                            <input type="text" name="address" id="" class="layui-input" value="{{$data->address}}" disabled="disabled">
                        </div>
                        <div class="instrtion_input">
                            <div class="instrtion_span">
                                <span>联系电话：</span>
                            </div>
                            <input type="text" name="phone" id="" class="layui-input" value="{{$data->phone}}" disabled="disabled">
                        </div>
                    </div>
                </div>
            </form>
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
        layui.use(['form','layer'],function () {
            var form=layui.form;
            form.render();
        })
    </script>
@endsection