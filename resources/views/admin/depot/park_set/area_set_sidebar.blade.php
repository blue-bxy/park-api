<aside class="main-sidebar">
    <section  class="sidebar">
        <ul class="sidebar-menuli">
            @foreach($parks as $park)
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>{{ $park['park_name'] }}</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    @foreach($park['area'] as $area)
                        <ul class="treeview-menu">
                            <li class="treeview">
                                <a href="{{ route('admin.park_area.create')}}">
                                    <i class="fa fa-dashboard"></i> <span>新增区域</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                            </li>
                            <li class="treeview" id=233>
                                <a href="#">
                                    <i class="fa fa-dashboard"></i> <span>E停车地面区域</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i>车位设置</a></li>
                                    <li><a href="{{ route('admin.park_bluetooth.index') }}"><i class="fa fa-circle-o"></i>蓝牙设置</a></li>
                                    <li><a href="{{ route('admin.park_cameras.index') }}"><i class="fa fa-circle-o"></i>摄像头设置</a></li>
                                    <li><a href="{{ route('admin.park_space_locks.index') }}"><i class="fa fa-circle-o"></i>地锁设置</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-files-o"></i>
                                    <span>车位费率管理</span>
                                    <span class="label label-primary pull-right">4</span>
                                </a>
                                <ul class="treeview-menu" style="display: none;">
                                    <li><a href="#"><i class="fa fa-circle-o"></i>新建费率</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i>修改费率</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-dashboard"></i> <span>设备管理状态</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{ route('admin.park_cameras.index') }}"><i class="fa fa-circle-o"></i>摄像头</a></li>
                                    <li><a href="{{ route('admin.park_bluetooth.index') }}"><i class="fa fa-circle-o"></i>蓝牙</a></li>
                                    <li><a href="{{ route('admin.park_cameras.index') }}"><i class="fa fa-circle-o"></i>车位监控</a></li>
                                    <li><a href="{{ route('admin.park_space_locks.index') }}"><i class="fa fa-circle-o"></i>地锁</a></li>
                                </ul>
                            </li>
                        </ul>
                    @endforeach
                </li>
            @endforeach
        </ul>
    </section>
</aside>
