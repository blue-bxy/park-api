@extends('layouts.header')

@section('content')
<div class="content">
    <div class="left">
        <div class="left_price">
            <div class="ty">
                <button type="button" class="btn btn-default" style="border-color:#fff;">
                    <span class="glyphicon glyphicon-usd"></span>
                </button>
                <span>财务分析</span>
            </div>
            <!-- 财务数据统计 -->
            <div class="number_price">
                <div class="number_two">
                    <div class="number_three">
                        <div class="number_text">
                            <p>0</p>
                            <span>预约金额</span>
                        </div>
                        <div class="number_four">
                            <div>0.00%</div>
                            <div>环比</div>
                        </div>
                    </div>
                    <div class="number_three">
                        <div class="number_text">
                            <p>0</p>
                            <span>预约金额</span>
                        </div>
                        <div class="number_four">
                            <div>0.00%</div>
                            <div>环比</div>
                        </div>
                    </div>
                </div>
                <div class="number_two">
                    <div class="number_three">
                        <div class="number_text">
                            <p>0</p>
                            <span>预约金额</span>
                        </div>
                        <div class="number_four">
                            <div>0.00%</div>
                            <div>环比</div>
                        </div>
                    </div>
                    <div class="number_three">
                        <div class="number_text">
                            <p>0</p>
                            <span>预约金额</span>
                        </div>
                        <div class="number_four">
                            <div>0.00%</div>
                            <div>环比</div>
                        </div>
                    </div>
                </div>
                <div class="number_two">
                    <div class="number_three">
                        <div class="number_text">
                            <p>0</p>
                            <span>预约金额</span>
                        </div>
                        <div class="number_four">
                            <div>0.00%</div>
                            <div>环比</div>
                        </div>
                    </div>
                    <div class="number_three">
                        <div class="number_text">
                            <p>0</p>
                            <span>预约金额</span>
                        </div>
                        <div class="number_four">
                            <div>0.00%</div>
                            <div>环比</div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <!-- 圆形统计图 -->
        <div class="box">
            <div class="box_ui">
                <div>支付方式统计</div>
                <div>应收收费类型</div>
            </div>
            <!-- cavans -->
            <ul class="box_ul">
                <li>
                    <div id="container" style="width: 250px; height: 250px;margin-top: -21%;"></div>
                </li>
                <li>
                    <canvas id="canvas_ol" width="140" height="140" ></canvas>
                </li>
            </ul>
            <!-- 分类 -->
            <div class="box_price">
                <div class="box_color">
                    <div class="blue"></div>
                    <div>支付宝</div>
                    <div class="greey"></div>
                    <div>微信</div>
                    <div class="red"></div>
                    <div>其他</div>
                </div>
                <div class="box_color">
                    <div class="blue"></div>
                    <div>支付宝</div>
                    <div class="greey"></div>
                    <div>微信</div>
                    <div class="red"></div>
                    <div>其他</div>
                </div>
            </div>
        </div>
        <!-- 趋势分析 -->
        <div class="trend">
            <div class="trend_text">财务趋势分析</div>
            <div class="bar-chart" style="width:50% !important;height:200px!important;position:relative;">
                <div id="containerr" style="width:450px; height: 200px;"></div>
            </div>
        </div>
        <div class="left_botton" style="margin-top: 8%;">
            <div class="left_price">
                <div class="ty">
                    <button type="button" class="btn btn-default" style="border-color:#fff;">
                        <span class="glyphicon glyphicon-usd"></span>
                    </button>
                    <span>财务分析</span>
                </div>
                <!-- 财务数据统计 -->
                <div class="number_price">
                    <div class="number_two">
                        <div class="number_three">
                            <div class="number_text">
                                <p>0</p>
                                <span>预约金额</span>
                            </div>
                            <div class="number_four">
                                <div>0.00%</div>
                                <div>环比</div>
                            </div>
                        </div>
                        <div class="number_three">
                            <div class="number_text">
                                <p>0</p>
                                <span>预约金额</span>
                            </div>
                            <div class="number_four">
                                <div>0.00%</div>
                                <div>环比</div>
                            </div>
                        </div>
                    </div>
                    <div id="containerrur" style="width: 450px; height: 200px;"></div>
                </div>
                <hr>
            </div>
        </div>
    </div>
    <!-- 地图 -->
    <div class="center">
        <div class="center_op">

            <!-- 区域分布图 -->
            <div id="main">

            </div>

            <div class="center_ul">
                <div class="center_li">
                    <p>302100</p>
                    <span>注册用户</span>
                </div>
                <div class="center_li">
                    <p>30300</p>
                    <span>当前在线人数</span>
                </div>
                <div class="center_li">
                    <p>3000</p>
                    <span>车场数</span>
                </div>
                <div class="center_li">
                    <p>30090</p>
                    <span>车位数</span>
                </div>
                <div class="center_li">
                    <p>3000</p>
                    <span>VIP用户数</span>
                </div>
            </div>
        </div>
        <div class="center_zhe">
            <div class="ty">
                <button type="button" class="btn btn-default" style="border-color:#fff;">
                    <span class="glyphicon glyphicon-usd"></span>
                </button>
                <span>运营分析</span>
            </div>
            <div class="number_price">
                <div class="number_two">
                    <div class="number_threee">
                        <div class="number_text">
                            <p>0</p>
                            <span>总流量(次)</span>
                        </div>
                        <div class="number_four">
                            <div>0.00%</div>
                            <div>环比</div>
                        </div>
                    </div>
                    <div class="number_threee">
                        <div class="number_text">
                            <p>32.10</p>
                            <span>车位占用率</span>
                        </div>
                        <div class="number_four">
                            <div>0.00%</div>
                            <div>环比</div>
                        </div>
                    </div>
                    <div class="number_threee">
                        <div class="number_text">
                            <p>0</p>
                            <span>车位周转率</span>
                        </div>
                        <div class="number_four">
                            <div>0.00%</div>
                            <div>环比</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cloum">
                <div id="containerrurr" style="width: 450px; height: 200px;"></div>
                <div id="containerrurrr" style="width: 450px; height: 200px;"></div>
                <div id="containerrurrrr" style="width: 450px; height: 200px;"></div>
            </div>
        </div>
    </div>

    <div class="right">
        <div class="left_botton">
            <div class="left_price">
                <div class="ty">
                    <button type="button" class="btn btn-default" style="border-color:#fff;">
                        <span class="glyphicon glyphicon-usd"></span>
                    </button>
                    <span>运维分析</span>
                </div>
                <!-- 财务数据统计 -->
                <div class="number_price">
                    <div class="number_two">
                        <div class="number_three">
                            <div class="number_text">
                                <p>1</p>
                                <span>项目总数(个)</span>
                            </div>
                            <div class="number_four">
                                <div>0</div>
                                <div>新增</div>
                            </div>
                        </div>
                        <div class="number_three">
                            <div class="number_text">
                                <p>0</p>
                                <span>离线项目数(个)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="number_price">
                    <div class="number_two">
                        <div class="number_three">
                            <div class="number_text">
                                <p>9</p>
                                <span>设备总数(个)</span>
                            </div>
                            <div class="number_four">
                                <div>0</div>
                                <div>新增</div>
                            </div>
                        </div>
                        <div class="number_three">
                            <div class="number_text">
                                <p>5</p>
                                <span>离线设备数(个)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <!-- 运维报修 -->
        <div class="xiu">
            <div class="ty">
                <button type="button" class="btn btn-default" style="border-color:#fff;">
                    <span class="glyphicon glyphicon-usd"></span>
                </button>
                <span>财务分析</span>
            </div>
            <div class="xiu_flex">
                <div class="xiu_one">
                    <div class="xiu_name">
                        <p>0</p>
                        <span>保修项目</span>
                    </div>
                    <div class="xiu_name">
                        <p>0</p>
                        <span>保修项目</span>
                    </div>
                    <div class="xiu_name">
                        <p>0</p>
                        <span>保修项目</span>
                    </div>
                </div>
                <canvas id="canvas_ole" width="140" height="140" ></canvas>
            </div>
            <!-- 运维报修圆型统计图 -->
            <div>

                </di>
            </div>
            <!-- 运维报修折线统计图 -->
            <div class="">
                <div id="containerru" style="width: 450px; height: 250px;"></div>
                <!-- <div id="containerrur" style="width: 500px; height: 300px;"></div> -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="{{asset('js/index.js')}}"></script>
    <script src="{{asset('js/echarts.min.js')}}"></script>
    <script src="{{asset('js/map/china.js')}}"></script>
    <script>
        //第1个饼图
        function drawCirclei(canvasId, data_arr, color_arr){
            var c = document.getElementById(canvasId);
            var ctx = c.getContext("2d");
            var radius = c.height/2-20;
            var ox = radius + 20, oy = radius + 20;
            var startAngle = 0;
            var endAngle = 0;
            for (var i = 0; i < data_arr.length; i++)  {
                endAngle = endAngle + data_arr[i] * Math.PI * 2;
                ctx.fillStyle = color_arr[i];
                ctx.beginPath();
                ctx.moveTo(ox, oy);
                ctx.arc(ox, oy, radius, startAngle, endAngle, false);
                ctx.closePath();
                ctx.fill();
                startAngle = endAngle;
            }
        }
        function init() {
            var data_arr = [0.05, 0.25, 0.6, 0.1];
            var color_arr = ["#00FF21", "#FFAA00", "#00AABB", "#FF4400"];
            drawCirclei("canvas_ol", data_arr, color_arr);
        }
        window.onload = init;

    </script>

    <script>
        //第2个饼图
        function drawCircleie(canvasId, data_arr, color_arr){
            var c = document.getElementById(canvasId);
            var ctx = c.getContext("2d");
            var radius = c.height/2-20;
            var ox = radius + 20, oy = radius + 20;
            var startAngle = 0;
            var endAngle = 0;
            for (var i = 0; i < data_arr.length; i++)  {
                endAngle = endAngle + data_arr[i] * Math.PI * 2;
                ctx.fillStyle = color_arr[i];
                ctx.beginPath();
                ctx.moveTo(ox, oy);
                ctx.arc(ox, oy, radius, startAngle, endAngle, false);
                ctx.closePath();
                ctx.fill();
                startAngle = endAngle;
            }
        }
        function initq() {
            var data_arr = [0.05, 0.25, 0.6, 0.1];
            var color_arr = ["#00FF21", "#FFAA00", "#00AABB", "#FF4400"];
            drawCirclei("canvas_ole", data_arr, color_arr);
        }
        window.onload = initq;
    </script>




    <script language="JavaScript">
        $(document).ready(function() {
            var chart = {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            };
            var title = {
                text: ''
            };
            var tooltip = {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            };
            var plotOptions = {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}%</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            };
            var series= [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    ['F', 45.0],
                    ['IE', 26.8],
                    {
                        name: 'Chrome',
                        y: 12.8,
                        sliced: true,
                        selected: true
                    },
                    ['Sri',8.5],
                    ['Opa', 6.2],
                    ['Oth', 0.7]
                ]
            }];

            var json = {};
            json.chart = chart;
            json.title = title;
            json.tooltip = tooltip;
            json.series = series;
            json.plotOptions = plotOptions;
            $('#container').highcharts(json);
        });
    </script>


    <!-- 折现图 -->
    <script language="JavaScript">
        $(document).ready(function() {
            var title = {
                text: ''
            };
            var subtitle = {
                text: ''

            };
            var xAxis = {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            };
            var yAxis = {
                title: {
                    text: 'Temperature (\xB0C)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            };
            var tooltip = {
                valueSuffix: '\xB0C'
            }
            var legend = {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            };
            var series =  [
                {
                    name: '',
                    data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2,
                        26.5, 23.3, 18.3, 13.9, 9.6]
                },
                {
                    name: '',
                    data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8,
                        24.1, 20.1, 14.1, 8.6, 2.5]
                },
                {
                    name: '',
                    data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0,
                        16.6, 14.2, 10.3, 6.6, 4.8]
                }
            ];
            var json = {};
            json.title = title;
            json.subtitle = subtitle;
            json.xAxis = xAxis;
            json.yAxis = yAxis;
            json.tooltip = tooltip;
            json.legend = legend;
            json.series = series;

            $('#containerr').highcharts(json);
            $('#containerru').highcharts(json);
            $('#containerrur').highcharts(json);

            $('#containerrurr').highcharts(json);
            $('#containerrurrr').highcharts(json);
            $('#containerrurrrr').highcharts(json);
        });
    </script>
    <!--  -->
    <script type="text/javascript">
        var dataList=[
            {name:"南海诸岛",value:0},
            {name: '北京', value: randomValue()},
            {name: '天津', value: randomValue()},
            {name: '上海', value: randomValue()},
            {name: '重庆', value: randomValue()},
            {name: '河北', value: randomValue()},
            {name: '河南', value: randomValue()},
            {name: '云南', value: randomValue()},
            {name: '辽宁', value: randomValue()},
            {name: '黑龙江', value: randomValue()},
            {name: '湖南', value: randomValue()},
            {name: '安徽', value: randomValue()},
            {name: '山东', value: randomValue()},
            {name: '新疆', value: randomValue()},
            {name: '江苏', value: randomValue()},
            {name: '浙江', value: randomValue()},
            {name: '江西', value: randomValue()},
            {name: '湖北', value: randomValue()},
            {name: '广西', value: randomValue()},
            {name: '甘肃', value: randomValue()},
            {name: '山西', value: randomValue()},
            {name: '内蒙古', value: randomValue()},
            {name: '陕西', value: randomValue()},
            {name: '吉林', value: randomValue()},
            {name: '福建', value: randomValue()},
            {name: '贵州', value: randomValue()},
            {name: '广东', value: randomValue()},
            {name: '青海', value: randomValue()},
            {name: '西藏', value: randomValue()},
            {name: '四川', value: randomValue()},
            {name: '宁夏', value: randomValue()},
            {name: '海南', value: randomValue()},
            {name: '台湾', value: randomValue()},
            {name: '香港', value: randomValue()},
            {name: '澳门', value: randomValue()}
        ]
        var myChart = echarts.init(document.getElementById('main'));
        function randomValue() {
            return Math.round(Math.random()*1000);
        }
        option = {
            tooltip: {
                formatter:function(params,ticket, callback){
                    return params.seriesName+'<br />'+params.name+'：'+params.value
                }//数据格式化
            },
            visualMap: {
                min: 0,
                max: 1500,
                left: 'left',
                top: 'bottom',
                text: ['高','低'],//取值范围的文字
                inRange: {
                    color: ['#e0ffff', '#006edd']//取值范围的颜色
                },
                show:true//图注
            },
            geo: {
                map: 'china',
                roam: false,//不开启缩放和平移
                zoom:1.23,//视角缩放比例
                label: {
                    normal: {
                        show: true,
                        fontSize:'10',
                        color: 'rgba(0,0,0,0.7)'
                    }
                },
                itemStyle: {
                    normal:{
                        borderColor: 'rgba(0, 0, 0, 0.2)'
                    },
                    emphasis:{
                        areaColor: '#F3B329',//鼠标选择区域颜色
                        shadowOffsetX: 0,
                        shadowOffsetY: 0,
                        shadowBlur: 20,
                        borderWidth: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            },
            series : [
                {
                    name: '信息量',
                    type: 'map',
                    geoIndex: 0,
                    data:dataList
                }
            ]
        };
        myChart.setOption(option);
        myChart.on('click', function (params) {
            alert(params.name);
        });

        /*  setTimeout(function () {
              myChart.setOption({
                  series : [
                      {
                          name: '信息量',
                          type: 'map',
                          geoIndex: 0,
                          data:dataList
                      }
                  ]
              });
          },1000)*/
    </script>
@endsection
