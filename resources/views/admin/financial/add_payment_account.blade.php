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
                        <li>添加账户</li>
                    </ul>
                </div>
            </div>
            <!-- 日期类型 -->
            <div class="Order">
                <p style="font-size: 20px;margin-left: 4%;">新增账户</p></td>
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
                        <div>
                            <select name="property_id" lay-verify="required">
                                @foreach($properties as $name=>$id)
                                    <option value="{{$id}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="instrtion_input">
                        <div class="instrtion_span">
                            <span>税号：</span>
                        </div>
                        <input type="text" name="ein" id="" class="layui-input" lay-verify="required">
                    </div>
                    <div class="instrtion_input">
                        <div class="instrtion_span">
                            <span>银行账户：</span>
                        </div>
                        <input type="text" name="bank_account" id="" class="layui-input" lay-verify="required">
                    </div>
                    <div class="instrtion_input">
                        <div class="instrtion_span">
                            <span>银行名称：</span>
                        </div>
                        <input type="text" name="bank_name" id="" class="layui-input" lay-verify="required">
                    </div>
                    <div class="instrtion_input">
                        <div class="instrtion_span">
                            <span>地址：</span>
                        </div>
                        <input type="text" name="address" id="" class="layui-input" lay-verify="required">
                    </div>
                    <div class="instrtion_input">
                        <div class="instrtion_span">
                            <span>联系电话：</span>
                        </div>
                        <input type="text" name="phone" id="" class="layui-input" lay-verify="phone">
                    </div>
                    <div class="Order_input" style="margin-left:44%;height: 35px;margin-bottom: 15px;">
                        <button type="button" class="layui-btn" lay-submit="" lay-filter="add">确定</button>
                        <button type="button" class="layui-btn layui-btn-primary" style="margin-left: 4%;" onclick="javascript:history.back(-1);">取消</button></td>
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
            //监听提交事件
            form.on('submit(add)',function (data) {
                $('.layui-btn').addClass('layui-disabled').attr('disabled', 'disabled');
                $.ajax({
                    url: "{{route('admin.PaymentAccount.store')}}",
                    type: 'post',
                    dataType: 'json',
                    data: $("#form").serializeArray(),
                    success: function (res) {
                        if (res.code == 1) {
                            $(".layui-btn").removeClass('layui-disabled').removeAttr('disabled');
                            layer.msg(res.msg, {icon: 6, time: 1500, shade: 0.1});
                        } else {
                            $(".layui-btn").removeClass('layui-disabled').removeAttr('disabled');
                            layer.msg('添加失败', {icon: 2, time: 1500, shade: 0.1});
                            return false;
                        }
                    }
                })
            })
        })
    </script>
@endsection