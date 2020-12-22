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
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>字段一</th>
                        <th>字段二</th>
                        <th>字段三</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Tanmay</td>
                        <td>Bangalore</td>
                        <td>560001</td>
                        <td><a href="">修改</a></td>


                    </tr>
                    <tr>
                        <td>Tanmay</td>
                        <td>Bangalore</td>
                        <td>560001</td>
                        <td><a href="">修改</a></td>

                    </tr>
                    <tr>
                        <td>Tanmay</td>
                        <td>Bangalore</td>
                        <td>560001</td>
                        <td><a href="">修改</a></td>

                    </tr>
                    <tr>
                        <td>Tanmay</td>
                        <td>Bangalore</td>
                        <td>560001</td>
                        <td><a href="">修改</a></td>

                    </tr>
                    <tr>
                        <td>Tanmay</td>
                        <td>Bangalore</td>
                        <td>560001</td>
                        <td><a href="">修改</a></td>
                    </tr>
                    <tr>
                        <td>Tanmay</td>
                        <td>Bangalore</td>
                        <td>560001</td>
                        <td><a href="">修改</a></td>
                    </tr>
                    <tr>
                        <td>Tanmay</td>
                        <td>Bangalore</td>
                        <td>560001</td>
                        <td><a href="">修改</a></td>
                    </tr>
                    <tr>
                        <td>Tanmay</td>
                        <td>Bangalore</td>
                        <td>560001</td>
                        <td><a href="">修改</a></td>
                    </tr>
                    </tbody>
                </table>
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
