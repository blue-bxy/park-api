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

            <form id="form_bluetooth">
                @csrf
                <!-- 区域设置 -->
                <div class="float_table_qu">
                    <div class="float_tables_ul">
                        <div class="float_tables_ul_input">
                            <p>蓝牙名称</p>
                            <input type="hidden" name="park_id">
                            <input type="hidden" name="park_area_id">

                            <input type="text" name="name" id="name">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>IP地址</p>
                            <input type="text" name="ip" id="ip">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>通信协议</p>
                            <input type="text" name="protocol" id="protocol">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>网关</p>
                            <input type="text" name="gateway" id="gateway">
                        </div>
                    </div>
                    <!-- 提交 -->
                    <div class="float_tables_ul_input_bei">
                        <p>备注</p>
                        <input style="width: 80%; height: 115px;" type="text" name="remark" id="remark">
                    </div>
                    <div class="button_type">
                        <button type="button" onclick="reset()" class="btn btn-default">重置</button>
                        <button type="button" onclick="store()" class="btn btn-primary">提交</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.depot.park_set.common_js')
    <script>
        $(document).ready(function () {
            sidebarLoad(JSON.parse(sessionStorage.getItem('parks')));
            $("input[name='park_id']").attr('value', getUrlParam('park_id'))
            $("input[name='park_area_id']").attr('value', getUrlParam('park_area_id'))
        });
        function reset() {
            $("#form_bluetooth").reset();
        }
        function store() {
            $.ajax({
                url: "{{ route('admin.park_bluetooth.store') }}",
                data: $("#form_bluetooth").serialize(),
                type: "POST",
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    alert(res.message);
                }
            })
        }
    </script>
@endsection
