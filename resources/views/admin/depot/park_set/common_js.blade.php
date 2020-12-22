<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7//js/bootstrap.js"></script>
<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/sidebar-menu.js') }}"></script>
<script>
    $.sidebarMenu($('.sidebar-menuli'))
    $.sidebarMenu($('.sidebar-menu'))

    function getUrlParam(key){
//构造一个含有目标参数的正则表达式对象
        let reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
//匹配目标参数
        let value = window.location.search.substr(1).match(reg);
//返回参数值
        if (value!=null) return unescape(value[2]);
        return null;
    }

    // 停车场设置侧边栏菜单
    function sidebarLoad(parks) {
        let table = '';
        parks.forEach(park => {
            table += '<li class="treeview">\n' +
                '                                <a href="#">\n' +
                '                                    <i class="fa fa-dashboard"></i> <span>'+park.park_name+'</span> <i class="fa fa-angle-left pull-right"></i>\n' +
                '                                </a>\n' +
                '\n' +
                '                                <ul class="treeview-menu">\n' +
                '                                    <li class="treeview">\n' +
                '                                        <a href="'+"{{ route('admin.park_area.create') }}?park_id="+park.id+'">\n' +
                '                                            <i class="fa fa-dashboard"></i> <span>新增区域</span> <i class="fa fa-angle-left pull-right"></i>\n' +
                '                                        </a>\n' +
                '                                    </li>'
            park.areas.forEach(area => {
                table += '<li class="treeview">\n' +
                    '                                            <a href="#">\n' +
                    '                                                <i class="fa fa-dashboard"></i> <span>'+area.name+'</span> <i class="fa fa-angle-left pull-right"></i>\n' +
                    '                                            </a>\n' +
                    '                                            <ul class="treeview-menu">\n' +
                    '                                                <li><a href="'+"{{ route('admin.park_spaces.create') }}"+'"><i class="fa fa-circle-o"></i>车位设置</a></li>\n' +
                    '                                                <li><a href="'+"{{ route('admin.park_bluetooth.create') }}?park_id="+park.id+"&park_area_id="+area.id+'"><i class="fa fa-circle-o"></i>蓝牙设置</a></li>\n' +
                    '                                                <li><a href="'+"{{ route('admin.park_cameras.create') }}?park_id="+park.id+"&park_area_id="+area.id+'"><i class="fa fa-circle-o"></i>摄像头设置</a></li>\n' +
                    '                                                <li><a href="'+"{{ route('admin.park_space_locks.create') }}?park_id="+park.id+"&park_area_id="+area.id+'"><i class="fa fa-circle-o"></i>地锁设置</a></li>\n' +
                    '                                            </ul>\n' +
                    '                                        </li>'
            })
            table += '<li class="treeview">\n' +
                '                                        <a href="#">\n' +
                '                                            <i class="fa fa-files-o"></i>\n' +
                '                                            <span>车位费率管理</span>\n' +
                '                                            <span class="label label-primary pull-right">4</span>\n' +
                '                                        </a>\n' +
                '                                        <ul class="treeview-menu" style="display: none;">\n' +
                '                                            <li><a href="#"><i class="fa fa-circle-o"></i>新建费率</a></li>\n' +
                '                                            <li><a href="#"><i class="fa fa-circle-o"></i>修改费率</a></li>\n' +
                '                                        </ul>\n' +
                '                                    </li>\n' +
                '                                    <li class="treeview">\n' +
                '                                        <a href="#">\n' +
                '                                            <i class="fa fa-dashboard"></i> <span>设备管理状态</span> <i class="fa fa-angle-left pull-right"></i>\n' +
                '                                        </a>\n' +
                '                                        <ul class="treeview-menu">\n' +
                '                                            <li><a href="'+"{{ route('admin.park_cameras.index') }}?park_id="+park.id+'"><i class="fa fa-circle-o"></i>摄像头</a></li>\n' +
                '                                            <li><a href="'+"{{ route('admin.park_bluetooth.index') }}?park_id="+park.id+'"><i class="fa fa-circle-o"></i>蓝牙</a></li>\n' +
                '                                            <li><a href="'+"{{ route('admin.park_cameras.index') }}?park_id="+park.id+'"><i class="fa fa-circle-o"></i>车位监控</a></li>\n' +
                '                                            <li><a href="'+"{{ route('admin.park_space_locks.index') }}?park_id="+park.id+'"><i class="fa fa-circle-o"></i>地锁</a></li>\n' +
                '                                        </ul>\n' +
                '                                    </li>\n' +
                '                                </ul>\n' +
                '                            </li>'
        })
        $("#park").html(table);
    }
</script>
