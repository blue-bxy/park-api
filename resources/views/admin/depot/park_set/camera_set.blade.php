@extends('layouts.header')

@section('css')
    @include('admin.depot.park_set.common_css')
@endsection

@section('content')
    <!-- 侧边栏 -->
    @include('admin.depot.park_sidebar')
    <div class="float">
        <div class="float_flex">
            <!-- 车厂管理车厂信息 -->
            @include('admin.depot.park_set.head_labels')

            <aside class="main-sidebar">
                <section  class="sidebar">
                    <ul class="sidebar-menuli" id="park">
                    </ul>
                </section>
            </aside>

            <!-- 区域设置 -->
            <div class="float_table_qu">
                <div class="float_tables_ul">
                    <div class="float_tables_ul_input">
                        <p>监控名称</p>
                        <input type="text" name="" id="">
                    </div>
                    <div class="float_tables_ul_input">
                        <p>IP地址</p>
                        <input type="text" name="" id="">
                    </div>
                    <div class="float_tables_ul_input">
                        <p>通信协议</p>
                        <input type="text" name="" id="">
                    </div>
                    <div class="float_tables_ul_input">
                        <p>网关</p>
                        <input type="text" name="" id="">
                    </div>
                </div>
                <!-- 提交 -->
                <div class="float_tables_ul_input_bei">
                    <p>备注</p>
                    <input style="width: 80%; height: 115px;" type="text" name="" id="">
                </div>
                <div class="button_type">
                    <button type="button" class="btn btn-default">重置</button>
                    <button type="button" class="btn btn-primary">提交</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('admin.depot.park_set.common_js')
    <script>
        $(document).ready(function () {
            sidebarLoad(JSON.parse(sessionStorage.getItem('parks')));
        });
    </script>
@endsection
