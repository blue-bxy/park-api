@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">、
    <link rel="stylesheet" href="{{ asset('layui/layui/css/layui.css') }}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/income.css')  }}">
    <style>
        .layui-form-select dl dd.layui-this{
            background-color:#337AB7 !important;
        }
        .searchBtn{
            margin-left: 20px;
        }
        .long-size{
            width: 86px;
        }
        .search-btn{
            width: 100px;
            height: 40px;
            color: #fff;
            line-height: 40px;
            text-align: center;
            background: #337AB7;
            outline: none;
            border: none;
            margin-right: 20px;
        }
        .search-btn:hover{
            background: #296298;
        }
        .parkincome-table{
            /*margin-left: 50px;*/
        }
        .paignate{
            margin: 20px 20px;
        }
    </style>
@endsection

@section('content')
<!-- 侧边栏 -->
<aside class="main-sidebar">
    <section  class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="{{ route('admin.parkincome.index') }}"><i class="fa fa-circle-o text-red"></i> <span>车厂收入总览</span></a></li>
            <li><a href="{{ route('admin.carapt.index') }}"><i class="fa fa-circle-o text-yellow"></i> <span>预约数据查询</span></a></li>
            <li><a href="{{ route('admin.carstop.index') }}"><i class="fa fa-circle-o text-aqua"></i> <span>停车记录</span></a></li>
            <li><a href="{{ route('admin.excel.index') }}"><i class="fa fa-circle-o text-aqua"></i> <span>报表导出</span></a></li>
            <li><a href="{{ route('admin.actionrecord.index') }}"><i class="fa fa-circle-o text-aqua"></i> <span>操作记录</span></a></li>
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
                    <li>数据管理</li>
                    <li>车厂收入总览</li>
                </ul>
            </div>
        </div>
        <form class="layui-form" action="{{ route('admin.carstop.index') }}"  method="GET" id="searchForm">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">项目</label>
                    <div class="layui-input-inline">
                        <input value="" name="park_id" class="layui-input" id="" type="text" placeholder="车牌号">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">时间段</label>
                    <div class="layui-input-inline">
                        <input name="car_start_time" class="layui-input" id="startTime" type="text" placeholder="请选择时间">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">至</label>
                    <div class="layui-input-inline">
                        <input value="" name="car_end_time" class="layui-input" id="stopTime" type="text" placeholder="请选择时间">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label long-size">停车类型</label>
                    <div class="layui-input-inline">
                        <select name="car_stop_type" id="stop_type" lay-verify="required"  lay-filter="province">
                            <option value="">请选择</option>
                            <option value="1">暂停</option>
                            <option value="2">长租</option>
                            <option value="3">出租暂停</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">车牌号</label>
                    <div class="layui-input-inline">
                        <input value="" name="car_num" class="layui-input" id="" type="text" placeholder="车牌号">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label stop-type long-size">优免类型</label>
                    <div class="layui-input-inline">
                        <select name="free_type_id" id="stop_type" lay-verify="required"  lay-filter="province">
                            <option value="">请选择</option>
                            <option value="1">APP优免</option>
                            <option value="2">车场优免</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        <div class="">
            <div class="searchBtn">
                <button class="search-btn" type="button" id="search">查询</button>
                <button class="search-btn" type="button" id="export">导出报表</button>
            </div>
        </div>
        <!--表格  -->
        <table class="parkincome-table layui-table" style="margin-top: 5%;">
            <thead>
            <tr>
                <th>出入场图片</th>
                <th>停车场名称</th>
                <th>车牌号</th>
                <th>入场时间</th>
                <th>出场时间</th>
                <th>应收金额</th>
                <th>优免金额</th>
                <th>特殊处理损失</th>
                <th>被冲车辆损失</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $v)
            <tr class="info">
                <td>Tanmay</td>
                <td>{{ $v['park']['project_name'] }}</td>
                <td>{{ $v['car_num'] }}</td>
                <td>{{ $v['car_in_time'] }}</td>
                <td>{{ $v['car_out_time'] }}</td>
                <td>{{ $v['stop_price'] }}</td>
                <td>{{ $v['free_price'] }}</td>
                <td>{{ $v['special_price'] }}</td>
                <td>{{ $v['washed_price'] }}</td>
                <td>
                    <a href="">修改</a>
                    <a href="">删除</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <!-- 分页 -->
    <div class="paignate">
        {{ $data->appends(request()->except('page'))->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7//js/bootstrap.js"></script>
<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
<script src="{{ asset('layui/layui/layui.js') }}"></script>
<script src="{{ asset('js/select.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/sidebar-menu.js') }}"></script>
<script>
    $.sidebarMenu($('.sidebar-menu'));
    // layui
    layui.use('form', function(){
        var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
    });
    layui.use(['laydate','layer'], function() {
        var laydate = layui.laydate;
        laydate.render({
            elem: '#startTime'
        });
        laydate.render({
            elem: '#stopTime'
        });
        // 导出表格
        $('#export').on('click',function() {
            var province = $('#province').val();
            var city = $('#city').val();
            var district = $('#district').val();
            var year = $('#year').val();
            var month = $('#month').val();
            var day = $('#day').val();
            var loading = layer.msg('正在生成', {icon: 16, shade: 0.3, time:0});
            $.ajax({
                url: "{{ route('admin.carstop.create') }}",
                type: 'GET',
                dataType: 'json',
                data: {province,city,district,year,month,day},
                success: function (data) {
                    var data = typeof data == 'string' ? jQuery.parseJSON(data) : data;
                    if (data['code'] == 1) {
                        layer.msg(data.msg, {icon: 6, time: 1500, shade: 0.1});
                        layer.close(loading);
                    }else{
                        layer.msg(res.msg, {icon: 2, time: 1500, shade: 0.1});
                    }
                }
            })
        })
    })
    // 封装一个函数，将省市区的value换成字符
    function inputValue(){
        var province = $('#province option:selected');
        var city = $('#city option:selected');
        var district = $('#district option:selected');
        province.attr("value",province.text());
        city.attr("value",city.text());
        district.attr("value",district.text());
    }
    // 提交表单查询
    $('#search').on('click',function(){
        inputValue();
        $('#searchForm').submit();
    });


</script>
@endsection
