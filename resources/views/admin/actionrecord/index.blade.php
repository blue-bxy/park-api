@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/income.css')  }}">
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
        <div class="Report_form">
            <div class="Report_form_row">
                <p style="width: 35%;line-height: 30px;">操作人员</p>
                <div class="input-group">
                    <input class="sex" style=" border-radius: 5px; width: 150px; height: 30px;" type="text" th:field="*{sex}" list="listItem5" placeholder="选择">
                    <datalist id="listItem4">
                                <option>北京</option>
                                <option>上海</option>
                        <option>安徽</option>
                        <option>湖南</option>
                    </datalist>
                </div>
            </div>
            <div class="Report_form_row">
                <p style="width: 35%;line-height: 30px;">操作时间</p>
                <div class="input-group">
                    <input class="sex" style=" border-radius: 5px; width: 150px; height: 30px;" type="text" th:field="*{sex}" list="listItem3" placeholder="选择">
                    <datalist id="listItem3">
                                <option>北京</option>
                                <option>上海</option>
                        <option>安徽</option>
                        <option>湖南</option>
                    </datalist>
                </div>
            </div>
            <div class="Report_form_row">
                <p style="width: 35%;line-height: 30px;">项目</p>
                <div class="input-group">
                    <input class="sex" style=" border-radius: 5px; width: 150px; height: 30px;" type="text" th:field="*{sex}" list="listItem2" placeholder="选择">
                    <datalist id="listItem2">
                                <option>北京</option>
                                <option>上海</option>
                        <option>安徽</option>
                        <option>湖南</option>
                    </datalist>
                </div>
            </div>

        </div>
        <!-- 查询 -->
        <div class="report">
            <button type="button" class="btn btn-primary">查询</button>
        </div>
        <!--表格  -->
        <div class="Report_form_row" style="margin-top: 2%;">
            <p style="width: 35%;line-height: 30px;">显示</p>
            <div class="input-group">
                <input class="sex" style=" border-radius: 5px; width: 150px; height: 30px;" type="text" th:field="*{sex}" list="listItem0" placeholder="选择">
                <datalist id="listItem0">
                            <option>10</option>
                            <option>20</option>

                </datalist>
            </div>
            <p style="width: 35%;line-height: 30px;">记录</p>
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>序号</th>
                <th>报表名称</th>
                <th>报表类型</th>
                <th>文件大小</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Tanmay</td>
                <td>Bangalore</td>
                <td>560001</td>
                <td>560001</td>
            </tr>
            <tr>
                <td>Tanmay</td>
                <td>Bangalore</td>
                <td>560001</td>
                <td>560001</td>
            </tr>
            <tr>
                <td>Tanmay</td>
                <td>Bangalore</td>
                <td>560001</td>
                <td>560001</td>
            </tr>
            <tr>
                <td>Tanmay</td>
                <td>Bangalore</td>
                <td>560001</td>
                <td>560001</td>
            </tr>
            <tr>
                <td>Tanmay</td>
                <td>Bangalore</td>
                <td>560001</td>
                <td>560001</td>
            </tr>
            </tbody>
        </table>

    </div>

    <!-- 分页 -->
    <div class="ination ">
        <div class="ination_row">
            <div class="ination_text">共1024条</div>
            <div class="ination_ul">
                <ul class="pagination">
                    <li><a href="#">&laquo;</a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">&raquo;</a></li>
                </ul>
            </div>
            <div class="ination_text_next">
                <div class="next">往前</div>
                <div class="input-group" style="margin-top: -4%;">
                    <input type="text" placeholder="请输入页面" style="width: 100px;" class="form-control">
                    <span class="input-group-btn">
                                                <button class="btn btn-default" type="button">前往</button>
                                            </span>
                </div>
                <!-- <div class="next">往前</div> -->
            </div>
        </div>
    </div>
    <!-- 提交 -->
    <div style="float: right;margin-top: -27.5%;">
        <button type="button" class="btn btn-primary">复制</button>
        <button type="button" class="btn btn-primary">导处</button>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7//js/bootstrap.js"></script>
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/sidebar-menu.js') }}"></script>
    <script>
        $.sidebarMenu($('.sidebar-menu'))
    </script>
@endsection
