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
            <form id="form_area">
                @csrf
                <div class="float_table_qu">
                    <div class="float_tables_ul">
                        <div class="float_tables_ul_input">
                            <p>区域名称</p>
                            <input type="hidden" name="park_id">

                            <input type="text" name="name">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>车位总数</p>
                            <input type="text" name="parking_places_count">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>长租车位</p>
                            <input type="text" name="long_term_parking_places_count">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>预约车位</p>
                            <input type="text" name="reserved_parking_places_count">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>区域属性</p>
                            <input type="text" name="attribute">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>临时长租</p>
                            <input type="text" name="temp_parking_places_count">
                        </div>
                    </div>
                    <!-- 提交 -->
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
        });
        function reset() {
            $("#form_area").reset();
        }
        function store() {
            $.ajax({
                url: "{{ route('admin.park_area.store') }}",
                data: $("#form_area").serialize(),
                type: "POST",
                dataType: "json",
                success: function (res) {
                    alert(res.message);
                }
            })
        }
    </script>
@endsection


