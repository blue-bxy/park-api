@extends('admin.head')

@section('content')
<!-- 侧边栏 -->
<aside class="main-sidebar">
    <section  class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="../form.html"><i class="fa fa-circle-o text-red"></i> <span>车厂信息</span></a></li>
            <li><a href="../Set/form_three.html"><i class="fa fa-circle-o text-yellow"></i> <span>车厂设置</span></a></li>
            <li><a href="../API/api.html"><i class="fa fa-circle-o text-aqua"></i> <span>Api接口</span></a></li>
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
                    <li>API管理</li>
                </ul>
            </div>
            <!-- 区域选择 -->
            <div class="inputu">
                <form class="bs-example bs-example-form" role="form">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group">
                                <input class="sex" style=" border-radius: 5px; width: 280px; height: 50px;" type="text" th:field="*{sex}" list="listItem1" placeholder="停车场选择">
                                <datalist id="listItem1">
                                            <option>圣立创业园</option>
                                            <option>乐坊陆恒店</option>
                                    <option>杨浦中心医院</option>
                                    <option>亭风商务酒店</option>
                                    <option>交汇2号停车场</option>
                                </datalist>
                            </div>
                        </div>
                    </div>
                </form>
                <form class="bs-example bs-example-form" role="form">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group">
                                <input class="sex" style=" margin-left: 5%;border-radius: 5px; width: 280px; height: 50px;" type="text" th:field="*{sex}" list="listItem1" placeholder="对接管理软件">
                                <datalist id="listItem1">
                                            <option>捷顺</option>
                                            <option>ETCP</option>
                                    <option>软杰</option>
                                    <option>领通自营</option>
                                    <option>科拓</option>
                                </datalist>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('my-js')
<script>
    $.sidebarMenu($('.sidebar-menu'))
</script>
@endsection