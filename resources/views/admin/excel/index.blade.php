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
            margin-left: 50px;
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
            margin-left: 50px;
        }
        .paignate{
            margin: 20px 50px;
        }
        /*去掉连接默认样式*/
        /*包含以下四种的链接*/
        a {
            text-decoration: none;
        }
        /*正常的未被访问过的链接*/
        a:link {
            text-decoration: none;
        }
        /*已经访问过的链接*/
        a:visited {
            text-decoration: none;
        }
        /*鼠标划过(停留)的链接*/
        a:hover {
            text-decoration: none;
        }
        /* 正在点击的链接*/
        a:active {
            text-decoration: none;
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
            <form class="layui-form" action="{{ route('admin.excel.index') }}" method="GET" id="searchForm">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">名称</label>
                        <div class="layui-input-inline">
                            <input name="excel_name" class="layui-input" type="text" placeholder="请输入" autocomplete="off" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">类型</label>
                        <div class="layui-input-inline">
                            <input name="excel_type" class="layui-input" type="text" placeholder="请输入" autocomplete="off" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">始</label>
                        <div class="layui-input-inline">
                            <input id="start_create_time" name="create_excel_time" class="layui-input" type="text" placeholder="请输入" autocomplete="off" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">至</label>
                        <div class="layui-input-inline">
                            <input id="stop_create_time" name="stop_create_time" class="layui-input" type="text" placeholder="请输入" autocomplete="off" lay-verify="required">
                        </div>
                    </div>
                    <div>
                        <label class="layui-form-label">状态</label>
                        <div class="layui-input-inline">
                            <select name="load_type">
                                <option value="">选择</option>
                                <option value="否">未下载</option>
                                <option value="是">已下载</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <!-- 查询 -->
            <div class="">
                <div class="searchBtn">
                    <button class="search-btn" type="button" id="search">查询</button>
                </div>
            </div>
            <!--表格  -->
            <table class="parkincome-table layui-table">
                <thead>
                <tr>
                    <th>序号</th>
                    <th>报表名称</th>
                    <th>报表类型</th>
                    <th>文件大小</th>
                    <th>创建时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $v)
                <tr>
                    <td class="id">{{ $v['id'] }}</td>
                    <td>{{ $v['excel_name'] }}</td>
                    <td>
                        @if($v['excel_type'] == 'xls')
                        excel
                        @endif
                    </td>
                    <td>{{ $v['excel_size'] }}kb</td>
                    <td>{{ $v['create_excel_time'] }}</td>
                    <td>
                        @if($v['load_type'] == '否')
                            未下载
                        @else
                            已下载
                        @endif
                        </td>
                    <td>
                        <a href="{{ url('/excel/'.$v['id']) }}" class="download layui-btn layui-btn-sm" type="button">下载</a>
                        <a href="javascript:void(0);" class="delete layui-btn layui-btn-danger layui-btn-sm" type="button">删除</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="paignate">
            {{ $data->appends(request()->except('page'))->links() }}
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7//js/bootstrap.js"></script>
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="{{ asset('layui/layui/layui.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/sidebar-menu.js') }}"></script>
    <script>
        $.sidebarMenu($('.sidebar-menu'))
        // 查询
        $('#search').on('click',function(){
            $('#searchForm').submit();
        });
        // layui
        layui.use('form', function(){
            var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
        });
        layui.use('laydate', function() {
            var laydate = layui.laydate;
            laydate.render({
                elem: '#start_create_time'
                , type: 'datetime'
            });
            laydate.render({
                elem: '#stop_create_time'
                , type: 'datetime'
            });

            laydate.render({
                elem: '#month'
                , type: 'month'
                ,format: 'MM'
            });
            laydate.render({
                elem: '#day'
                ,format: 'dd'
            });
            laydate.render({
                elem: '#hour'
                ,type: 'time'
                ,format: 'HH'
            });
        })
        // 下载
        $('.download').click(function(){
            var tr = $(this).parent().parent();
            var id= tr.find("td").first().text();
            $.ajax({
                url:"/excel/"+id,
                type:'GET',
                data:{'_token':'{{csrf_token()}}'},
                dataType:'json',
                success:function(data){
                }
            })
        });

        // 删除
        $('.delete').click(function(e){
            e.preventDefault();
            var tr = $(this).parent().parent();
            var id= tr.find("td").first().text();
            $.ajax({
                url:"/excel/"+id,
                type:'DELETE',
                data:{'_token':'{{csrf_token()}}'},
                dataType:'json',
                success:function(data){
                    var ret = typeof data == 'string' ? jQuery.parseJSON(data) : data;
                    if(ret.code == 1){
                        // 移除对应的元素
                        tr.remove();
                        alert(ret.msg);
                    }
                }
            })
        });

    </script>
@endsection
