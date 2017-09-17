/**
 * 替换所有匹配exp的字符串为指定字符串
 * @param exp 被替换部分的正则
 * @param newStr 替换成的字符串
 */
String.prototype.replaceAll = function (exp, newStr) {
	return this.replace(new RegExp(exp, "gm"), newStr);
};

/**
 * 原型：字符串格式化
 * @param args 格式化参数值
 */
String.prototype.format = function(args) {
	var result = this;
	if (arguments.length < 1) {
		return result;
	}

	var data = arguments; // 如果模板参数是数组
	if (arguments.length == 1 && typeof (args) == "object") {
		// 如果模板参数是对象
		data = args;
	}
	for ( var key in data) {
		var value = data[key];
		if (undefined != value) {
			result = result.replaceAll("\\{" + key + "\\}", value);
		}
	}
	return result;
}

/**
 * 时间对象的格式化;
 */
Date.prototype.format = function(format) {
	/*
	 * eg:format="yyyy-MM-dd hh:mm:ss";
	 */
	var o = {
		"M+" : this.getMonth() + 1, // month
		"d+" : this.getDate(), // day
		"h+" : this.getHours(), // hour
		"m+" : this.getMinutes(), // minute
		"s+" : this.getSeconds(), // second
		"q+" : Math.floor((this.getMonth() + 3) / 3), // quarter
		"S" : this.getMilliseconds()
		// millisecond
	}

	if (/(y+)/.test(format)) {
		format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4
			- RegExp.$1.length));
	}

	for (var k in o) {
		if (new RegExp("(" + k + ")").test(format)) {
			format = format.replace(RegExp.$1, RegExp.$1.length == 1
				? o[k]
				: ("00" + o[k]).substr(("" + o[k]).length));
		}
	}
	return format;
}

/**
 * 数组插入
 * @param index
 * @param item
 */
Array.prototype.insert = function (index, item) {
	this.splice(index, 0, item);
};

jQuery.prototype.serializeObject=function(){
	var a,o,h,i,e;
	a=this.serializeArray();
	o={};
	h=o.hasOwnProperty;
	for(i=0;i<a.length;i++){
		e=a[i];
		if(!h.call(o,e.name)){
			o[e.name]=e.value;
		}
	}
	return o;
};

function _str(fn)
{
	return fn.toString().split('\n').slice(1,-1).join('\n') + '\n';
}

function reloaction(href)
{
	window.location.href = href;
}

function _POST(target, data, onResult)
{
	var xmlhttp;
	if (window.XMLHttpRequest)
	{
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function()
	{
		if(xmlhttp.readyState==4)
		{
			onResult(xmlhttp.status, xmlhttp.responseText);
		}
	}

	var param = "";
	var i = 0;
	for ( var p in data )
	{
		if (typeof (data[p]) !== " function " )
		{
			param +=  "{0}{1}={2}".format(i==0?'':'&', p, _Encode(data[p]+''));
			i++;
		}
	}

	console.log(param);

	xmlhttp.open("POST", target, true);
	xmlhttp.setRequestHeader("cache-control","no-cache");
	xmlhttp.setRequestHeader("contentType","text/html;charset=uft-8");
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	xmlhttp.send(param);

	return false;
}

function _Encode(clearString)
{
	console.log(clearString);
	return clearString.replace(/&/g, "%26");
}