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
            <div class="float_table_es">
                <table class="table table-striped">
                    <caption>蓝牙</caption>
                    <thead>
                    <tr>
                        <th>相机ID</th>
                        <th>车位号</th>
                        <th>停车场名称</th>
                        <th>
                            <input type="text" style="width: 150px;" placeholder="模糊查询">
                        </th>
                        <th>
                            区域
                            <input class="sex" style="width: 115px;" type="text" th:field="*{sex}" list="listItem1" placeholder="请选择类型">
                            <datalist id="listItem1">
                                        <option>室内</option>
                                        <option>室外</option>
                                <option>室内+室外</option>
                            </datalist>
                        </th>
                        <th>
                            相机状态
                            <input class="sex" style="width: 115px;" type="text" th:field="*{sex}" list="listItem2" placeholder="请选择类型">
                            <datalist id="listItem2">
                                        <option>模糊</option>
                                        <option>一般</option>
                                <option>清晰</option>
                            </datalist>
                        </th>
                        <th>
                            网络状态
                            <input class="sex" style="width: 115px;" type="text" th:field="*{sex}" list="listItem3" placeholder="请选择类型">
                            <datalist id="listItem3">
                                        <option>差</option>
                                        <option>良好</option>
                                <option>好</option>
                            </datalist>
                        </th>
                        <th>
                            <div class="button_type">
                                <button type="button" style="margin-left: -15%;" class="btn btn-primary">提交</button>
                            </div>
                        </th>
                    </tr>
                    </thead>
                </table>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>停车场名称</th>
                        <th>区域</th>
                        <th>蓝牙ID</th>
                        <th>蓝牙位置</th>
                        <th>品牌</th>
                        <th>型号</th>
                        <th>网络状态</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($parkBluetooth as $bluetooth)
                        <tr>
                            <td>{{ $bluetooth->park->park_name }}</td>
                            <td>{{ $bluetooth->area->name }}</td>
                            <td>{{ $bluetooth->id }}</td>
                            <td>{{ $bluetooth->location }}</td>
                            <td>{{ $bluetooth->brand }}</td>
                            <td>{{ $bluetooth->model }}</td>
                            <td>{{ $bluetooth->network_status }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="ination ">
                    {{ $parkBluetooth->withQueryString()->links() }}
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
