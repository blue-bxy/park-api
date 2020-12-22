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
            <form enctype="multipart/form-data">
                @csrf
                <div class="float_table_qu">
                    <div class="float_tables_ul">
                        <div class="float_tables_ul_input">
                            <p>区域</p>
                            <input type="text" name="" id="">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>区域名称</p>
                            <input type="text" name="" id="">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>车位总数</p>
                            <input type="text" name="" id="">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>车位编号</p>
                            <input type="text" name="" id="">
                        </div>
                        <div class="float_tables_ul_input">
                            <p>导入文件</p>
                            <input type="file" name="upload_file" id="upload_file">
                        </div>
                    </div>
                    <!-- 提交 -->
                    <div class="button_type">
                        <button type="button" class="btn btn-default">重置</button>
                        <button type="button" class="btn btn-primary" onclick="store()">提交</button>
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
        });
        function store() {
            let files = $('#upload_file').prop('files');
            let data = new FormData();
            data.append('upload_file', files[0]);
            data.append('_token', "{{ csrf_token() }}");

            $.ajax({
                type: 'POST',
                url: "{{ config('app.admin_domain') }}/park_spaces/import",
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log(res);
                }
            });
        }
    </script>
@endsection
