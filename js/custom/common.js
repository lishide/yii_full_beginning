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
String.prototype.format = function (args) {
    var result = this;
    if (arguments.length < 1) {
        return result;
    }

    var data = arguments; // 如果模板参数是数组
    if (arguments.length == 1 && typeof (args) == "object") {
        // 如果模板参数是对象
        data = args;
    }
    for (var key in data) {
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
Date.prototype.format = function (format) {
    /*
     * eg:format="yyyy-MM-dd hh:mm:ss";
     */
    var o = {
        "M+": this.getMonth() + 1, // month
        "d+": this.getDate(), // day
        "h+": this.getHours(), // hour
        "m+": this.getMinutes(), // minute
        "s+": this.getSeconds(), // second
        "q+": Math.floor((this.getMonth() + 3) / 3), // quarter
        "S": this.getMilliseconds()
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
 * 将form转换为Object(包括disable的控件)
 * @returns {{}}
 */
jQuery.prototype.serializeAllObject = function () {
    var obj = {};

    $('input', this).each(function () {
        if (this.type == 'checkbox') {
            if (typeof(obj[this.name]) == 'undefined') {
                obj[this.name] = [];
            }
            if (this.checked) {
                obj[this.name].push($(this).val());
            }
        } else if (this.type == 'radio') {
            if (typeof(obj[this.name]) == 'undefined') {
                obj[this.name] = '';
            }
            if (this.checked) {
                obj[this.name] = $(this).val();
            }
        } else {
            if (typeof(obj[this.name]) == 'undefined') {
                obj[this.name] = $(this).val();
            } else if (typeof(obj[this.name]) == 'string') {
                var t = obj[this.name];
                obj[this.name] = [];
                obj[this.name].push(t);
                obj[this.name].push($(this).val());
            } else {
                obj[this.name].push($(this).val());
            }
        }
    });

    $('select', this).each(function () {
        obj[this.name] = $(this).val();
    });

    $('textarea', this).each(function () {
        obj[this.name] = $(this).val();
    });

    return obj;
};

/**
 * 将form转换为Object
 * @returns {{}|*}
 */
jQuery.prototype.serializeObject = function () {
    var a, o, h, i, e;
    a = this.serializeArray();
    o = {};
    h = o.hasOwnProperty;
    for (i = 0; i < a.length; i++) {
        e = a[i];
        if (!h.call(o, e.name)) {
            o[e.name] = e.value;
        } else {
            if (typeof(o[e.name]) == 'string') {
                var tmp = o[e.name];
                o[e.name] = [];
                o[e.name].push(tmp);
            }
            o[e.name].push(e.value);
        }
    }
    return o;
};

/**
 * 将多行注释转换为多行文本
 * @param fn
 * @returns {string}
 * @private
 */
function _str(fn) {
    return fn.toString().split('\n').slice(1, -1).join('\n') + '\n';
}

/**
 * 页面跳转
 * @param href
 * @private
 */
function _reLoaction(href) {
    window.location.href = href;
}

/**
 * 使用原生JS以POST方式发送数据
 * @param target
 * @param data
 * @param onResult
 * @returns {boolean}
 * @private
 */
function _POST(target, data, onResult) {
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();//创建 XMLHttpRequest 对象的语法
    }
    else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");//老版本的 Internet Explorer （IE5 和 IE6）使用 ActiveX 对象：
    }

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4) {
            onResult(xmlhttp.status, xmlhttp.responseText);
        }
    }

    var param = "";
    var i = 0;
    for (var p in data) {
        if (typeof (data[p]) !== " function ") {
            param += "{0}{1}={2}".format(i == 0 ? '' : '&', p, _Encode(data[p] + ''));
            i++;
        }
    }

    console.log(param);
    xmlhttp.open("POST", target, true);
    xmlhttp.setRequestHeader("cache-control", "no-cache");
    xmlhttp.setRequestHeader("contentType", "text/html;charset=uft-8");
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
    xmlhttp.send(param);

    return false;
}

/**
 * 请求API(依赖vue-resource)
 * @param controller
 * @param action
 * @param data
 * @param onResult
 * @param delay int
 * @param onProgress function
 * @private
 */
function _postAPI(controller, action, data, onResult) {
    Vue.http.options.emulateJSON = true;
    Vue.http.options.emulateHTTP = true;
    var onProgress = null;

    if(arguments.length >= 6){
        onProgress = arguments[5];
    }

    var post = function () {
        var url = "{0}{1}/{2}".format(_CONFIG.api, controller, action);
        console.log(url);
        console.log(data);

        var res = {
            state: 0,
            code: -1,
            error_text: 'Cannot connect server',
            error_text_ch: '无法连接服务器',
            error_msg: '无法连接服务器'
        };

        Vue.http.post(url, data, {
            progress:onProgress
        }).then(function (response) {
            console.log(response.body);
            console.log(response.text());
            res = response.body;
            onResult(res);
        }, function (response) {
            console.log(response.body);
            onResult(res);
        });
    };

    if (arguments.length >= 5) {
        setTimeout(post, arguments[4]);
    } else {
        post();
    }
}

function _dg(id) {
    return document.getElementById(id);
}

function _saveSession(key, content) {
    if (typeof (content) == "object") {
        localStorage.setItem(key, JSON.stringify(content));
    }
    else {
        localStorage.setItem(key, content);
    }
}

/**
 * 获取保存的字符串
 * @param {String} key 键名
 * @param {String} default 默认值
 */
function _getSessionStr(key) {
    var defaultValue = null;
    if (arguments[1]) {
        defaultValue = arguments[1];
    }

    var res = localStorage.getItem(key);
    return res === null ? defaultValue : res;
}

/**
 * 获取保存的对象
 * @param {String} key 键名
 */
function _getSessionObj(key) {
    return JSON.parse(localStorage.getItem(key));
}

/**
 * 移除本地保存的键值对
 * @param {String} key 键名
 */
function _removeSession(key) {
    localStorage.removeItem(key);
}

/**
 * 合并Object
 * @param {Object} destination
 * @param {Object} source
 */
function _mergeObject (destination, source) {
    for (var i in source) {
        if (typeof(source[i]) != 'undefined') {
            destination[i] = source[i];
        }
    }
    return destination;
}