@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7//js/bootstrap.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/sidebar-menu.css">
    <link rel="stylesheet" href="/css/instrtion.css">
    <link rel="stylesheet" href="/css/style.css">
    <link href="/layui/layui/css/layui.css" rel="stylesheet" />
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
            <!-- 面包屑导航 -->
            <div class="breadcrumb_but">
                <ul class="breadcrumb">
                    <li><a href="../form.html">车厂管理</a></li>
                    <li><a href="../Set/form_two.html">车厂信息</a></li>
                </ul>
            </div>
        </div>
        <form class="layui-form">
        <!-- 停车场基本信息表格填写 -->
        <div class="instrtion">
            <div class="instrtion_king">
                <h5>停车场基本信息</h5>
                <div>
                    <input type="hidden" value="{{$park['id']}}" id="parkid">
                </div>
                <!--项目名称：  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>项目名称：</span>
                    </div>
                    <div>
                        <input type="text" name="project_name" id="" value="{{$park['project_name']}}">
                    </div>
                </div>
                <!--所属公司：  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>所属公司：</span>
                    </div>
                    <select name="property_id"  lay-verify="required">
                        <option value="1" selected>待建</option>
                        <option value="2">运营</option>
                    </select>
                </div>
                <!--所属集团：  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>所属集团：</span>
                    </div>
                    <select name="project_group_id"  lay-verify="required">
{{--                        @foreach($park->projectGroup as $group)--}}
                        <option value="{{$park->projectGroup['id']}}" @if($park['project_group_id'] == $park->projectGroup['id']) selected @endif >{{$park->projectGroup['name']}}</option>
{{--                        @endforeach--}}
                    </select>
                </div>
                <!--停车场简称：  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>停车场简称：</span>
                    </div>

                    <input type="text" name="park_name" id="park_name" value="{{$park['park_name']}}">
                </div>
                <!--停车场编号：  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>停车场编号：</span>
                    </div>
                    <input type="text" name="park_number" id="" value="{{$park['park_number']}}">
                </div>
                <!--停车场所在城市：  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>停车场所在城市：</span>
                    </div>
                    <div class="instrtion_input_link">
                        <select name="park_province" id="province" lay-verify="required"  lay-filter="province" >
                            <option value=""  >{{$park['park_province']}}</option>
                        </select>
                        <select name="park_city" id="city" lay-verify="required"  lay-filter="city">
                            <option value="">{{$park['park_city']}}</option>
                        </select>
                        <select name="park_area" id="district" lay-verify="required">
                            <option value="">{{$park['park_area']}}</option>
                        </select>
                    </div>
                </div>
                <!--项目地址  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>项目地址：</span>

                    </div>
                    <div class="instrtion_input_link">
                        <input id="address" type="text" value="{{$park['project_address']}}" name="project_address" style="margin-right:50px;" />
                        <div id="container" style="position:absolute; margin-top:-4px;margin-left: 8%; width:40px; height:40px; top:50; border:1px solid gray; overflow:hidden;"></div>
                        <input id="coordinate" type="text" value="{{$park['longitude_latitude']}}" name="longitude_latitude"/>
                        <input type="button" value="查询" onclick="searchByStationName();"/>

                    </div>
                </div>
                <!-- 出入口坐标 -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>出入口坐标：</span>
                    </div>
                    <div class="instrtion_input_colum">
                        <div class="instrtion_input_go">
                            <span>止园路出口：</span>
                            <input type="text" name="exit_coordinate" id="" value="{{$park['exit_coordinate']}}">
                        </div>
                        <div class="instrtion_input_gots">
                            <span>止园路入口：</span>
                            <input type="text" name="entrance_coordinate" id="" value="{{$park['entrance_coordinate']}}">
                        </div>
                    </div>
                </div>
                <!-- 停车场类型 -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>停车场类型：</span>
                    </div>
                    <select name="park_type"  lay-verify="required" >
                        <option value="1" @if($park['park_type'] == '1')selected @endif>室内</option>
                        <option value="2" @if($park['park_type'] == '2')selected @endif>室外</option>
                        <option value="3" @if($park['park_type'] == '3')selected @endif>室内+室外</option>
                        <option value="4" @if($park['park_type'] == '4')selected @endif>其他</option>
                    </select>
                </div>
                <!-- 停车场合作类型 -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>停车场合作类型</span>
                    </div>
                    <select name="park_cooperation_type"  lay-verify="required">
                        <option value="1" @if($park['park_cooperation_type'] == '1')selected @endif>销售</option>
                    </select>
                </div>
                <!-- 停车场客户端类型 -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>停车场客户端类型</span>
                    </div>
                    <select name="park_client_type"  lay-verify="required">
                        <option value="1" @if($park['park_client_type'] == '1')selected @endif>车牌识别</option>
                    </select>
                </div>
                <!--停车场属性 -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>停车场属性</span>
                    </div>
                    <select name="park_property"  lay-verify="required">
                        <option value="1" @if($park['park_property'] == '1')selected @endif>产业园</option>
                    </select>
                </div>
                <!-- 停车场运营状态 -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>停车场运营状态</span>
                    </div>
                    <select name="park_operation_state"  lay-verify="required">
                        <option value="1" @if($park['park_operation_state'] == '1')selected @endif>待建</option>
                        <option value="2" @if($park['park_operation_state'] == '2')selected @endif>运营</option>
                    </select>
                </div>

                <!-- 停车场数据及位置 -->
                <h5>停车场数据及位置</h5>
                <!--总车位数  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>总车位数</span>
                    </div>
                    <input type="text" name="carport_count" id="" value="{{$park->parkStall['carport_count']}}">
                </div>
                <!--长租车位数  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>长租车位数</span>
                    </div>
                    <input type="text" name="longtime_carport_count" id="" value="{{$park->parkStall['longtime_carport_count']}}">
                </div>
                <!-- 临时停车位数 -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>临时停车位数</span>
                    </div>
                    <input type="text" name="temporary_carport_count" id="" value="{{$park->parkStall['temporary_carport_count']}}">
                </div>
                <!-- 总车道数  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>总车道数</span>
                    </div>
                    <input type="text" name="lanes_count" id="" value="{{$park->parkStall['lanes_count']}}">
                </div>
                <!--  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>预计临日停车量</span>
                    </div>
                    <input type="text" name="expect_temporary_parking_count" id="" value="{{$park->parkStall['expect_temporary_parking_count']}}">
                </div>
                <!-- 营业时间 -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>停车场运营时间</span>
                    </div>
                    <input type="text" name="park_operation_time" id="park_operation_time" value="{{$park->parkStall['park_operation_time']}}">
                </div>
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>营业时间</span>
                    </div>
                    <input type="text" name="do_business_time" id="" value="{{$park->parkStall['do_business_time']}}">
                </div>
                <!--文字版费率  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>文字版费率</span>
                    </div>
                    <input type="text" name="fee_string" id="" value="{{$park->parkStall['fee_string']}}">
                </div>
                <!--地图费率  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>地图费率 ：</span>
                    </div>
                    <input type="text" name="map_fee" id="" value="{{$park->parkStall['map_fee']}}">
                </div>
                <!--  -->

                <!--  -->
            </div>
            <div class="instrtion_king">
                <h5>业务信息</h5>
                <!--项目名称：  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>业务员工号：</span>
                    </div>
                    <div>
                        <input type="text" name="salesman_number" id="" value="{{$park->parkService['salesman_number']}}">
                    </div>
                </div>
                <!--所属公司：  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>业务员姓名：</span>
                    </div>
                    <input type="text" name="sales_name" id="" value="{{$park->parkService['sales_name']}}">
                </div>
                <!--所属集团：  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>联系电话：</span>
                    </div>
                    <input type="text" name="sales_phone" id="" value="{{$park->parkService['sales_phone']}}">
                </div>
                <!--停车场简称：  -->
                <div class="instrtion_input">
                    <div class="instrtion_span">
                        <span>合同编号：</span>
                    </div>

                    <input type="text" name="contract_no" id="" value="{{$park->parkService['contract_no']}}">
                </div>

                <div class="instrtion_input">
                    <button type="button" id='EditPark' class="btn btn-primary" style="width: 120px;">提交</button></td>
                </div>
                <!--停车场编号：  -->
            </div>
        </div>
        </form>
    </div>
</div>



</body>
@endsection

@section('scripts')
<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/sidebar-menu.js"></script>
<script src="/layui/layui/layui.js" type="text/javascript"></script>
<script src="/js/select.js" type="text/javascript"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script>
<script type="text/javascript" src="/js/depot.js"></script>
<script>
    $.sidebarMenu($('.sidebar-menu'))
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //执行一个laydate实例
        laydate.render({
            elem: '#park_operation_time'
            ,type: 'datetime'
            ,show: false
            ,format:'yyyy-MM-dd'
        });
    });
</script>
<script type="text/javascript">
    var map = new BMap.Map("container");
    map.centerAndZoom("上海市", 12);
    map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用

    map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
    map.addControl(new BMap.OverviewMapControl()); //添加默认缩略地图控件
    map.addControl(new BMap.OverviewMapControl({ isOpen: true, anchor: BMAP_ANCHOR_BOTTOM_RIGHT }));   //右下角，打开

    var localSearch = new BMap.LocalSearch(map);
    localSearch.enableAutoViewport(); //允许自动调节窗体大小
    function searchByStationName() {
        map.clearOverlays();//清空原来的标注
        var keyword = document.getElementById("address").value;
        localSearch.setSearchCompleteCallback(function (searchResult) {
            var poi = searchResult.getPoi(0);
            document.getElementById("coordinate").value = poi.point.lng + "," + poi.point.lat;
            map.centerAndZoom(poi.point, 13);
            var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地方对应的经纬度
            map.addOverlay(marker);
            var content = document.getElementById("text_").value + "<br/><br/>经度：" + poi.point.lng + "<br/>纬度：" + poi.point.lat;
            var infoWindow = new BMap.InfoWindow("<p style='font-size:14px;'>" + content + "</p>");
            marker.addEventListener("click", function () { this.openInfoWindow(infoWindow); });
            // marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
        });
        localSearch.search(keyword);
    }
</script>
@endsection
