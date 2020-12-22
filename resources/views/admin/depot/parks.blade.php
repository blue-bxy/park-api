@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7//js/bootstrap.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/sidebar-menu.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/income.css">
@endsection

@section('content')


<body style="background-color: #f8f8f8;">

<!-- 侧边栏 -->
<aside class="main-sidebar">
    <section  class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="{{route('admin.parks.index')}}"><i class="fa fa-circle-o text-red"></i> <span>车厂信息</span></a></li>
            <li><a href="{{route('admin.park_area.index')}}"><i class="fa fa-circle-o text-yellow"></i> <span>车厂设置</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Api接口</span></a></li>
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
                    <li><a href="./form.html">车厂管理</a></li>
                    <li><a href="../Yard/Set/form_two.html">车厂信息</a></li>
                </ul>
            </div>
            <!-- 区域选择 -->
            <div class="input">
                <div class="income_row">
                    <!-- <p style="width: 22%;">日</p> -->
                    <div class="input-group">
                        <input class="sex" style=" border-radius: 5px; width: 200px; height: 35px;" type="text" th:field="*{sex}" list="listItem1" placeholder="请选择区域">
                        <datalist id="listItem1">
                                    <option>1</option>
                                    <option>2</option>
                            <option>3</option>
                            <option>4</option>
                        </datalist>
                    </div>
                </div>
            </div>
            <div class="inputu">
                <form class="bs-example bs-example-form"  action="{{route('admin.parks.index')}}" method="get">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group">
                                <input type="text" placeholder="请输入搜索车场" name="project_name" style="width: 150px;" class="form-control">
                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="submit">搜索</button>
                                                </span>
                            </div>
                        </div><!-- /.col-lg-6 -->
                    </div><!-- /.row -->
                </form>
            </div>
            <div class="king" style="margin-top: 1%;margin-left: 40%;">
                <button type="button" class="btn btn-primary" onclick="javascript: location.href='/parks/create'">新建车厂</button>
            </div>
        </div>
    </div>
    <!-- 表格 -->
    <div class="table_name">
        <table class="table table-bordered">

            <thead>
            <tr>
                <th>停车场名称</th>
                <th>停车场编号</th>
                <th>公司名称</th>
                <th>集团名称</th>
                <th>所在城市</th>
                <th>车位数量</th>
                <th>长租车位</th>
                <th>激活码</th>
                <th>运营状态</th>
                <th>属性</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($readparks as $park)
            <tr>
                <td>{{$park['project_name']}}</td>
                <td>{{$park['park_number']}}</td>
                <td>{{$park['property_id']}}</td>
                <td>{{$park['project_group_id']}}</td>
                <td>{{$park['park_province']}}{{$park['park_city']}}{{$park['park_area']}}</td>
                <td>{{$park['park_stall']['carport_count']}}</td>
                <td>{{$park['park_stall']['longtime_carport_count']}}</td>
                <td>560001</td>
                <td>
                    @if($park['park_operation_state']==1)
                        待建
                    @endif
                    @if($park['park_operation_state']==2)
                        运营
                    @endif
                </td>
                <td>{{$park['park_property']}}</td>
                <td>
                    <button type="button" class="btn btn-default" onclick="javascript: location.href='/parks/{{$park['id']}}/edit'">修改</button>
                    <form action="{{route('admin.park_area.index')}}" method="get" style="display: inline-block">
                        <input type="hidden" name="park_id" value="{{$park['id']}}">
                    <button type="submit" class="btn btn-primary" >设置</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- 分页 -->
    <div class="ination ">
{{--        <div class="ination_row">--}}
{{--            <div class="ination_text">共1024条</div>--}}
{{--            <div class="ination_ul">--}}
{{--                <ul class="pagination">--}}
{{--                    <li><a href="#">&laquo;</a></li>--}}
{{--                    <li><a href="#">1</a></li>--}}
{{--                    <li><a href="#">2</a></li>--}}
{{--                    <li><a href="#">3</a></li>--}}
{{--                    <li><a href="#">4</a></li>--}}
{{--                    <li><a href="#">5</a></li>--}}
{{--                    <li><a href="#">&raquo;</a></li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--            <div class="ination_text_next">--}}
{{--                <div class="next">往前</div>--}}
{{--                <div class="input-group" style="margin-top: -4%;">--}}
{{--                    <input type="text" placeholder="请输入页面" style="width: 100px;" class="form-control">--}}
{{--                    <span class="input-group-btn">--}}
{{--                                                <button class="btn btn-default" type="button">前往</button>--}}
{{--                                            </span>--}}
{{--                </div>--}}
{{--                <!-- <div class="next">往前</div> -->--}}
{{--            </div>--}}
{{--        </div>--}}
        {{ $data->links() }}
    </div>
</div>

{{--</div>--}}
{{--</div>--}}


</body>
@endsection

@section('scripts')
<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/sidebar-menu.js"></script>
<script>
    $.sidebarMenu($('.sidebar-menu'))
</script>
@endsection
{{--</html>--}}
