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
                        <li>订单管理</li>
                    </ul>
                </div>
            </div>
            <!-- 日期类型 -->
            <blockquote class="layui-elem-quote">
                <form action="{{url('orders')}}" method="get" id="searchFrm" lay-filter="searchFrm" class="layui-form layui-form-pane">
                    <div class="layui-form-item">
                        <div class="layui-input-inline">
                            <input type="hidden" name="status"  placeholder="" value="{{$status}}" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                结算时间:
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
                                结算订单号:
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="order_no" placeholder="" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-form-mid">
                                订单类型:
                            </div>
                            <div class="layui-input-inline">
                                <select name="type">
                                    <option value="">请选择</option>
                                    <option value="1">预约订单</option>
                                    <option value="2">停车费用订单</option>
                                    <option value="3">出租车位订单</option>
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
            <li class="active"><a href="{{route('admin.orders.index')}}?status=2">正常结算{{$orders->count()}}</a></li>
            <li><a href="{{route('admin.orders.index')}}?status=1">延时结算</a></li>
            <li><a href="{{route('admin.orders.index')}}?status=3">退款</a></li>
            <div class="Order_input" style="margin-left:92%;">
                <button type="button" class="btn btn-primary" id="excelbutton">生成报表</button>
            </div>
        </ul>
        <!-- 表格 -->
        <div class="table_name">
            <table class="table table-bordered">
                <thead>
                <tr>
                    @if($status==3)
                        <th>订单号</th>
                        <th>车牌号</th>
                        <th>预约时间</th>
                        <th>预约金额</th>
                        <th>项目名称</th>
                        <th>退款金额</th>
                        <th>退款状态</th>
                        <th>退款时间</th>
                        <th>退款原因</th>
                        <th>备注</th>
                        <th>操作</th>
                    @else
                        <th>结算订单号</th>
                        <th>订单类型</th>
                        <th>结算类型</th>
                        <th>金额</th>
                        <th>结算时间</th>
                        <th>支付方式</th>
                        <th>电子支付第三方（元）</th>
                        <th>操作</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @if($status==3)
                    @if(!empty($orders))
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->order->order_no}}</td>
                                <td>{{$order->order->car_num}}</td>
                                <td>{{$order->order->appointment_start_time}}</td>
                                <td>{{$order->order->actual_price}}</td>
                                <td>{{$order->order->parks->project_name}}</td>
                                <td>{{$order->refunded_amount}}</td>
                                <td>
                                    @if($order->refunded_at!='')
                                        成功
                                    @elseif($order->failed_at!='')
                                        失败
                                    @endif
                                </td>
                                <td>{{$order->refunded_at?$order->refunded_at:$order->failed_at}}</td>
                                <td>{{$order->reason}}</td>
                                <td>{{$order->remarks}}</td>
                                <td><button type="button" class="btn btn-primary">查看详情</button></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" style="text-align: center">暂无数据</td>
                        </tr>
                    @endif
                @elseif($status==1 || $status==2)
                    @if(!empty($orders))
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->order_no}}</td>
                                <td>
                                    {{$order->type}}
                                </td>
                                <td>
                                    @if($order->status==1)
                                        延时结算
                                    @elseif($order->status==2)
                                        正常结算
                                    @endif
                                </td>
                                <td>{{$order->actual_price}}</td>
                                <td>{{$order->payed_at}}</td>
                                <td>
                                    @if($order->payment_method==0)
                                        未支付
                                    @elseif($order->payment_method==1)
                                        余额支付
                                    @elseif($order->payment_method==2)
                                        微信支付
                                    @elseif($order->payment_method==3)
                                        支付宝支付
                                    @endif
                                </td>
                                <td>
                                    @if($order->payment_method==2 || $order->payment_method==3)
                                        {{$order->actual_price}}
                                    @else
                                        0
                                    @endif
                                </td>
                                <td>
                                    @if($order->status==1)
                                        <button type="button" class="btn btn-primary">查看详情</button>
                                    @elseif($order->status==2)
                                        <button type="button" class="btn btn-primary">查看详情</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" style="text-align: center">暂无数据</td>
                        </tr>
                    @endif
                @endif
                </tbody>
            </table>
        </div>
        <!-- 分页 -->
        <div class="ination ">
            <div class="ination_row">
                <div class="ination_ul">
                    <ul class="pagination">
                        {{$orders->links()}}
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
        function lay_date(obj){
            layui.use(['laydate','layer'], function(){
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
                    url:"{{route('admin.orders.create')}}",
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