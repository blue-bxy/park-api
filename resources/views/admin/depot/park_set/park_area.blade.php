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
        <!-- 停车场管理侧边栏 -->

            <aside class="main-sidebar">
                <section  class="sidebar">
                    <ul class="sidebar-menuli" id="park">
                    </ul>
                </section>
            </aside>

            <!-- 区域设置 -->
            <div class="float_table">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>区域名称</th>
                        <th>区域属性</th>
                        <th>总车位数量</th>
                        <th>临时停车位数量</th>
                        <th>长租停车位数量</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($areas as $area)
                            <tr>
                                <td>{{ $area->name }}</td>
                                <td>{{ $area->attribute }}</td>
                                <td>{{ $area->parking_places_count }}</td>
                                <td>{{ $area->temp_parking_places_count }}</td>
                                <td>{{ $area->long_term_parking_places_count }}</td>
                                <td>
                                    <div><a href="#">详情</a></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="ination ">
                    {{ $areas->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.depot.park_set.common_js')
    <script>
        $(document).ready(function () {
            //请求侧边栏菜单数据
            $.ajax({
                url: "{{ config('app.admin_domain') }}/park_area/sidebar",
                type: "GET",
                dataType: "json",
                success: function (res) {
                    sessionStorage.setItem('parks', JSON.stringify(res));
                    sidebarLoad(res);
                    searchbarLoad();
                }
            });
        })
        //根据停车场搜索区域
        function searchbarLoad() {
            $("#head_labels").append(
                '<div class="inputu">\n' +
                '    <form class="bs-example bs-example-form" action="'+"{{ route('admin.park_area.index') }}"+'" method="get">\n' +
                '        <div class="row">\n' +
                '            <div class="col-lg-6">\n' +
                '                <div class="input-group">\n' +
                '                    <input type="text" placeholder="请输入搜索车场" name="park_name" style="width: 150px;" class="form-control">\n' +
                '                    <span class="input-group-btn">\n' +
                '                        <button class="btn btn-default" type="submit"">搜索</button>\n' +
                '                    </span>\n' +
                '                </div>\n' +
                '            </div><!-- /.col-lg-6 -->\n' +
                '        </div><!-- /.row -->\n' +
                '    </form>\n' +
                '</div>'
            );
        }
    </script>
@endsection

