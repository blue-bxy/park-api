<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{config('app.name', 'E停车云平台')}}</title>
{{--    <script src="{{asset('js/bootstrap.min.js')}}"></script>--}}
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    @yield('css')
</head>

<body style="background-color: #f8f8f8;">
<!-- 头部 -->
@section('topnavbar')
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="position: fixed;width: 100%;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{route('admin.home')}}">
                    {{config('app.name', 'E停车云平台')}}
                </a>
            </div>
            <div>
                <ul class="nav navbar-nav" style="font-size:16px;margin-left: 8%;">
                    <li class="active"><a href="{{route('admin.home')}}">首页</a></li>
                    <li><a href="{{route('admin.parks.index')}}">车厂管理</a></li>
                    <li><a href="{{ route('admin.parkincome.index') }}">数据管理</a></li>
                    <li><a href="{{route('admin.orders.index')}}">财务管理</a></li>
                    <li><a href="#">用户管理</a></li>
                    <li><a href="#">大数据中心</a></li>
                    <li><a href="{{route('admin.customers.index')}}">客服中心</a></li>
                    <li><a href="{{route('admin.customers.index')}}">销售系统</a></li>
                    <li><a href="#">关于我们</a></li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('admin.logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                退出
                            </a>

                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">

                </ul>
            </div>
        </div>
    </nav>
@show
<!-- 主体卡片 -->
@yield('content')

</body>
@yield('scripts')
</html>
