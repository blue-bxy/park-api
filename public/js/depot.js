/*解决laravel ajax token传递问题*/
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$("#EditPark").click(function () {
    var province=$("#province").find("option:selected").text()
    var city=$("#city").find("option:selected").text()
    var country=$('#district').find("option:selected").text()
    // alert(province);
    var id = $('#parkid').val();
    var params = $('form').serialize();
    // params=decodeURIComponent(params,true);

    // alert(params);
    // var pars = $("#province option:selected");
    $.ajax({
        url:'/parks/'+id,
        type:"put",
        data: params + '&province=' + province +'&city=' + city +'&country=' + country,
        dataType:'json',
        // headers:{"Content-Type":"text/plain;charset=UTF-8"},
        success:function (res) {
            if(res.msg=='success'){

                alert('提交成功！');
                window.location.href=document.referrer;
            }else{
                alert('提交失败！');
                return false;
            }
        },

    });
})

//添加
$("#AddPark").click(function () {

    var province=$("#province").find("option:selected").text()
    var city=$("#city").find("option:selected").text()
    var country=$('#district').find("option:selected").text()
    // alert(province);
    var id = $('#parkid').val();
    var params = $('form').serialize();
    // params=decodeURIComponent(params,true);

    // var pars = $("#province option:selected");

    $.ajax({
        url:'/parks',
        type:"post",
        data: params + '&province=' + province +'&city=' + city +'&country=' + country,
        dataType:'json',
        // headers:{"Content-Type":"text/plain;charset=UTF-8"},
        success:function (res) {
            if(res.msg=='success'){

                alert('新增成功！');
                window.location.href=document.referrer;
            }else{
                alert('新增失败！');
                return false;
            }
        },

    });
})
