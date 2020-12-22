$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
layui.use('form', function() {
	var form = layui.form;
	var province = $("#province"),
		city = $("#city"),
		district = $("#district"),
		parkName = $('#parkName');
	$.ajax({
		url:'/province',
		type:'post',
		dataType:'json',
		success:function (data) {
			//初始将省份数据赋予
			var provinceList=data;
			for (var i = 0; i < provinceList.length; i++) {
				var optionStr = "";
				optionStr = "<option value=" + provinceList[i].province_id + " >" + provinceList[i].name + "</option>";
				province.append(optionStr);
				form.render('select');
			}
		}
	});

	//移除select中所有项 赋予初始值
	function removeEle(ele) {
		ele.find("option").remove();
		var optionStar = "<option value=" + "0" + ">" + "请选择" + "</option>";
		ele.append(optionStar);
	}

	var provinceText, cityText;

	//选定省份后 将该省份的数据读取追加上
	form.on('select(province)', function(data) {
		provinceText = data.value;
		$.ajax({
			url:'/cities',
			type:'post',
			dataType:'json',
			data:{"province_id":provinceText},
			success:function (data) {
				var cityList=data;
				for (var i = 0; i < cityList.length; i++) {
					var optionStr = "";
					optionStr = "<option value=" + cityList[i].city_id + " >" + cityList[i].name + "</option>";
					city.append(optionStr);
					form.render('select');
				}
			}
		});
		removeEle(city);
		removeEle(district);
	});

	//选定市或直辖县后 将对应的数据读取追加上
	form.on('select(city)', function(data) {
		cityText = data.value;
		removeEle(district);
		$.ajax({
			url:'/countries',
			type:'post',
			dataType:'json',
			data:{"city_id":cityText},
			success:function (data) {
				var countryList=data;
				for (var i = 0; i < countryList.length; i++) {
					var optionStr = "";
					optionStr = "<option value=" + countryList[i].country_id + " >" + countryList[i].name + "</option>";
					district.append(optionStr);
					form.render('select');
				}
			}
		});
	});
	// 选定区/县后，将对应的停车场加上
	form.on('select(district)', function(data) {
		removeEle(parkName);
		var park_province = $('#province option:selected').text();
		var park_city = $('#city option:selected').text();
		var park_area = $('#district option:selected').text();
		$.ajax({
			url:'/ajaxParkInfo',
			type:'POST',
			dataType:'json',
			data:{park_province,park_city,park_area},
			success:function (data) {
				var parkNameList=data.data;
				for (var i = 0; i < parkNameList.length; i++) {
					var optionStr = "";
					optionStr = "<option value=" + parkNameList[i].id + " >" + parkNameList[i].project_name + "</option>";
					parkName.append(optionStr);
					form.render('select');
				}
			}
		});
	});

});
