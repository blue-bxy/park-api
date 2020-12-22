@extends('layouts.header')
@section('css')
    <style type="text/css">
        html,body{height:auto;}
        #province select{margin-left:10px; width:100px}
        .Sale_Numb{
            width: 85%;
            display: flex;
            flex-wrap: wrap;
            margin-left: 15%;
        }

        .sale_input {
            width: 50%;
            display: flex;
            flex-direction: row;
        }

        .sale_accon span {
            display: block;
            float: right;
            color: #000 !important;
        }

        .Sale_Number {
            width: 100%;
            display: flex;
            flex-direction: row;
            margin-top: 5%;
        }

        .Sale_Numbe {
            width: 100%;
            display: flex;
            flex-direction: row;
            margin-top: 1%;
        }

        .sale_accon {
            width: 25%;
            text-align: center;
        }

        .sale_input input {
            width: 10%;
        }

        .sale_input span {
            color: #f00;
            font-size: 15px;
            margin-left: 5%;
        }

        .sale_put {
            width: 35%;
        }

        .sale_put input {
            border: 1px solid rgb(182, 182, 182);
            width: 100%;
        }


        fieldset {
            width: 500px;
            padding: 20px;
            margin: 30px;
            border: 1px solid #ccc;
        }

        legend{
            font-size: 18px;
            font-weight: bold;
        }

        #addr-show, #addr-show02,#addr-show03{
            width: 225px;
            height: 25px;
            margin-bottom: 10px;
        }

        .btn {
            width: 80px;
            height: 30px;
            border-radius: 4px;
            border: 1px solid #ccc;
            outline: none;
            background-color: #aaa;
            margin: 0 20px;
        }

        .btn:disabled{
            background-color:#ccc;
        }
        /*方法一样式部分*/
        select {
            width: 120px;
            height: 30px;
        }

    </style>
    <link rel="stylesheet" href="{{asset('css/sidebar-menu.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/income.css')}}">
    <link href="{{asset('layui/layui/css/layui.css')}}" rel="stylesheet" />
    <link href="http://static.h-ui.net/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
@endsection
@section('content')
<!-- 侧边栏 -->
<aside class="main-sidebar">
    <section  class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="{{route('admin.customers.index')}}"><i class="fa fa-circle-o text-red"></i> <span>客户管理</span></a></li>
            <li><a href="{{route('admin.signing.index')}}"><i class="fa fa-circle-o text-yellow"></i> <span>签约管理</span></a></li>
            <li><a href="payment.html"><i class="fa fa-circle-o text-aqua"></i> <span>合同管理</span></a></li>
            <li><a href="Payment_order.html"><i class="fa fa-circle-o text-aqua"></i> <span>审批管理</span></a></li>
        </ul>
    </section>
</aside>
<form class="layui-input" method="post" action="{{route('admin.customers.sotre')}}">
<div class="Sale_Numb">
    <div class="Sale_Number">
        <div class="sale_input">
            <div class="sale_accon">
                <span>客户类型：</span>
            </div>
            <input type="radio" name="customer_type" value="0">自拓客户
            <input type="radio" name="customer_type" value="1">渠道
            <span>*必填项</span>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>停车场名称：</span>
            </div>
            <div class="sale_put"><input type="text" name="park_name"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>跟进人：</span>
            </div>
            <div class="sale_put"><input type="text" name="follow_up_person"></div>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>城市：</span>
            </div>
            <div class="sale_put"><input type="text" name="city"></div>

            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>前期厂商满意度：</span>
            </div>
            <input type="radio" name="manufacturer_satisfaction" value="1">中
            <input type="radio" name="manufacturer_satisfaction" value="0">高
            <input type="radio" name="manufacturer_satisfaction" value="2">低

        </div>
        <div class="sale_input">
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>停车场类型选择：</span>
            </div>
            <div class="sale_put">
                <select name="">
                    <option value="1">室内</option>
                    <option value="2">室外</option>
                    <option value="3">室内+室外</option>
                    <option value="4">其他</option>
                </select>
            </div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">

        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>物业公司级别：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>运营管理模式：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">

        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>竞争对手干扰:</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>物业公司级别：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>合作方式:</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>车厂管理方名称：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>车厂所有方名称:</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input" style="width: 100%;margin-left: 5.8%;">
                <label for="addr-show">您选择的是：
                    <input type="text" value="" id="addr-show">
                </label>
                <br/>
                <!--省份选择-->
                <select id="prov" onchange="showCity(this)">
                    <option>=请选择省份=</option>
                </select>
                <!--城市选择-->
                <select id="city" onchange="showCountry(this)">
                    <option>=请选择城市=</option>
                </select>
                <!--县区选择-->
                <select id="country"  onchange="selecCountry(this)">
                    <option>=请选择县区=</option>
                </select>
                <button type="button" class="btn met1" onClick="showAddr()">确定</button>
                <input type="text" style="width: 225px; width: 25%;" >
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>客户等级：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>地铁站周边：</span>
            </div>
            <input type="radio">是
            <input type="radio">否
            <span>*必填项</span>
        </div>
        <div class="sale_input">
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input" style="width: 80%;">
            <div class="sale_accon">
                <span>费率：</span>
            </div>
            <input type="text" style="width: 800px; height: 100px;">
            <span>*必填项</span>
        </div>
        <div class="sale_input">
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input" style="width: 80%;">
            <div class="sale_accon">
                <span>停车场状况概述：</span>
            </div>
            <input type="text" style="width: 800px; height: 100px;">
            <span>*必填项</span>
        </div>
        <div class="sale_input">
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>总车位数：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>临停量:</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>可预约车位：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>周(六/日)日均临停量：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>周(一~五)日均临停量:</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>周(六/日)日均收费临停量：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>周(一~五)日均收费临停量:</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>车厂管理方名称：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>车厂所有方名称:</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>姓名：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>电话:</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>职业：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>地下停车场是否有引导系统：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>地下停车场是否有动线指引:</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>停车发票是否主动提供：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
        <div class="sale_input">
            <div class="sale_accon">
                <span>地标线是否清晰:</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span>*必填项</span>
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="sale_accon">
                <span>关联集团：</span>
            </div>
            <div class="sale_put"><input type="text"></div>
            <span><a href="">清空</a></span>
        </div>
        <div class="sale_input">
        </div>
    </div>
    <div class="Sale_Numbe">
        <div class="sale_input">
            <div class="button_type">
                <button type="button" class="btn btn-default">查询</button>
                <button type="button" class="btn btn-primary">导处</button>
            </div>
        </div>
        <div class="sale_input">
        </div>
    </div>
    <!-- 提交 -->
    <div class="button_report">
        <div class="button_type">
            <button type="button" class="btn btn-default">查询</button>
            <button type="button" class="btn btn-primary">导处</button>
        </div>
    </div>
</div>
</form>
@endsection
@section('scripts')
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/sidebar-menu.js')}}"></script>
    <script src="{{asset('js/citys.js')}}"></script>
    <script src="{{asset('js/method01.js')}}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7//js/bootstrap.js"></script>
    <script type="text/javascript">
        layui.use('form',function () {
            var form=layui.form;
            form.render();
        });
        $(function(){
            $("#city").id="citySelect"({
                nodata:"none",
                required:false
            });
        });
    </script>
@endsection