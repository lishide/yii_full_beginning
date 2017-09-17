<?php
/**
 * 生成用户Token
 * @param $username
 * @param $usertype
 * @return string
 */
function _generateToken($uid)
{
    $str = sprintf("%d_%d_%lf", $uid, time(), mt_rand());
    return md5($str);
}

/**
 * 获取指定App()->params的值
 * @param $key
 * @param $index
 * @return mixed
 */
function _getConVal($key, $index = null)
{
    if ($index === null)
        return App()->params[$key];
    return App()->params[$key][$index];
}

function _getBoolText($boolean)
{
    return _getConVal('boolean', $boolean ? 1 : 0);
}

function _getClientIP()
{
    $ip = '';
    if($_SERVER["REMOTE_ADDR"])
        $ip = $_SERVER["REMOTE_ADDR"];
    else if(getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else if(getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])
        $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
    else if($HTTP_SERVER_VARS["REMOTE_ADDR"])
        $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
    else if(getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else
        $ip = false;

    return $ip;
}

/**
 * 获取并检查参数格式
 * @param array $empty_check 每行的元素分别为:字段名/空字段提示/是否自动_safeSQL
 * @param bool|false $allownull
 * @return array|null
 */
function _getParams($empty_check = array())
{
    $params = $_REQUEST;
//    var_dump($params);
    foreach ($empty_check as $p) {
        if (!isset($params[$p[0]]))
        {
            if(isset($_POST[$p[0]]))
                $params[$p[0]] = $_POST[$p[0]];
            else
                _error(100, $p[1]);
        }
        else if (isset($p[2]) && $p[2])
            $params[$p[0]] = _safeSQL($params[$p[0]]);
    }

    return $params;
}
//function _getParams($empty_check = array(), $allownull = false)
//{
//    $params = array();
//    if (isset($_GET['params'])) {
//        $param_arr = CJSON::decode($_GET['params']);
//
//        if (is_array($param_arr))
//            foreach ($param_arr as $key => $value)
//                $params[$key] = htmlspecialchars($value);
//    }
//
//    if (empty($params)) {
//        if ($allownull)
//            return null;
//        else
//            _error(10);
//    } else {
//        foreach ($empty_check as $p) {
//            if (!isset($params[$p[0]]))
//            {
//                if(isset($_POST[$p[0]]))
//                    $params[$p[0]] = $_POST[$p[0]];
//                else
//                    _error(100, $p[1]);
//            }
//            else if (isset($p[2]) && $p[2])
//                $params[$p[0]] = _safeSQL($params[$p[0]]);
//        }
//    }
//    return $params;
//}

/**
 * 获取通用的分页参数:start_pos和list_num
 * @return array
 */
function _getPageParams()
{
    $params = _getParams(array(), true);

    $start_pos = isset($params['start_pos']) ? (int) $params['start_pos'] : 0;
    $list_num = isset($params['list_num']) ? (int) $params['list_num'] : App()->params['pagesize_api'];

    if($start_pos < 0)
        $start_pos = 0;

    $pagesize_max = App() -> params['pagesize_api_max'];
    if($list_num > $pagesize_max)
        $list_num = $pagesize_max;
    else if($list_num < 0)
        $list_num = 0;

    return array('s' => $start_pos, 'l' => $list_num, 'sql' => " limit $start_pos, $list_num ");
}

function _JSON($res)
{
    header('Content-Type: application/json; charset=utf-8');
    exit(CJSON::encode($res));
}

/**
 * 直接运行SQL语句并返回结果数组
 * @param $model
 * @param $sql
 * @return array
 */
function _querySQL($sql, $db = null)
{
    if($db == null)
        $db = Yii::app()->db;

    $res = $db->createCommand($sql)->queryAll();
    return $res;
}

function _executeSQL($sql, $db = null)
{
    if($db == null)
        $db = Yii::app()->db;

    $res = $db->createCommand($sql)->execute();
    return $res;
}

/**
 * 以JSON格式输出正常执行结果并结束PHP脚本
 * @param $data
 */
function _OK($data, $total_num = null)
{
    if(empty($data))
        _error(98);

    if($total_num)
        _JSON(array('state' => 1, 'total_num'=>$total_num, 'data' => $data));
    else
        _JSON(array('state' => 1, 'data' => $data));
}

/**
 * 封装Yii的缓存接口,实现类似ThinkPHP的操作
 * @param $key
 * @param bool|false $value
 * @param int $expire
 * @return mixed
 */
function _S($key, $value = false, $expire = 3600)
{
    if ($value === false)    // 读取缓存
    {
        return Yii::app()->cache->get($key);
    } else if ($value === null)    // 删除缓存
    {
        Yii::app()->cache->delete($key);
    } else    // 新增 更新缓存
    {
        Yii::app()->cache->set($key, $value, $expire);
    }
}

/**
 * 电话号码格式验证
 * @param $phone
 * @param bool|true $onlyMobile
 * @return bool
 */
function _checkPhone($phone, $onlyMobile = true)
{
    return (($onlyMobile && preg_match("/^\d{11}$/u", $phone)) || ($onlyMobile == false && preg_match("/^\d{11}$|^\d{7}$/u", $phone)));
}

/**
 * 密码格式验证(6-20个任意字符)
 * @param $password
 * @return int
 */
function _checkPassword($password)
{
    return (preg_match("/^[\x{0}-\x{ffff}]{6,20}$/u", $password));
}

/**
 * 用户名格式验证（中英文、数字、下划线，长度4-20个字符）
 * @param string $username
 * @return bool
 */
function _checkUsername($username)
{
    //UTF-8汉字字母数字下划线
    return (preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]{4,20}$/u", $username));
}

/**
 * 字符串防SQL注入转换
 * @param string $input
 * @return mixed
 */
function _safeSQL($input = '')
{
    return str_replace('\'', '\'\'', str_replace('\\', '\\\\', $input));
}

function _fixEncodingToUTF8($in_str)
{
    $cur_encoding = mb_detect_encoding($in_str) ;

    if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
        return $in_str;
    return utf8_encode($in_str);
}

/**
 * 检查字符串长度
 * @param $string
 * @param $min
 * @param $max
 * @return int
 */
function _checkStringLength($string, $min, $max)
{
    return preg_match("/^[\x{0}-\x{ffff}]{".$min.",".$max."}$/u",$string);
}

/**
 * 获取订单流水号
 * @return string
 */
function _genOrderSn($type = 'order')
{
    $key = 'order_number';
    $prefix = '';
    if($type == 'pack')
    {
        $key = 'order_number_pack';
        $prefix = 'P';
    } else if ($type == 'withdraw') {
        $key = 'withdraw_sn';
        $prefix = '';
    } else if ($type == 'alipay_batch') {
        $key = 'alipay_batch_sn';
        $prefix = '';
    }

    $db = Yii::app()->db;
    $db->createCommand()->setText("lock tables configs write")->execute();
    $orderSn = $db->createCommand("select `value` from configs where `key` = '$key'")->queryAll();

    $orderSn = (float)$orderSn[0]['value'];
    $timeToday = (float)date("Ymd00000", time());

    if ($timeToday > $orderSn)
        $orderSn = $timeToday + 1;
    else
        $orderSn += 1.0;

    $db->createCommand("update configs set `value` = '" . $orderSn . "' where `key` = '$key'")->execute();
    $db->createCommand()->setText("unlock tables")->execute();
    return sprintf("%s%.0f", $prefix, $orderSn);
}

function _var($var, $stopPHP = false)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    if($stopPHP)
        exit();
}

/**
 * 删除data中的值
 * @param $data array 键值对数组或YiiModel
 * @param $keylist array 要删除的键列表
 * @param bool $multiline data是否为多行
 * @param bool $yiimodel data是否为Yii Model
 * @return array
 */
function _unset($data, $keylist, $multiline = false, $yiimodel = false)
{
    if ($multiline)
        $lines = $data;
    else
        $lines = array($data);

    if ($yiimodel == false)
        $array = $lines;
    else
        foreach ($lines as $row)
            $array[] = $row->attributes;

    $count = count($array);
    for ($i = 0; $i < $count; $i++)
        foreach ($keylist as $key)
            unset($array[$i][$key]);

    if ($multiline)
        return $array;
    return $array[0];
}

/**
 * 通过图片Model获取图片URL
 * @param $picModel 图片Model
 */
function _getPicURLByModel($picModel, $isThumb = true)
{
    return $picModel ? _getPicURL($isThumb ? $picModel -> thumb_path : $picModel -> path) : '';
}

function _getPicURL($path)
{
    if(empty($path))
        return '';
    return App()->params['picdomain'] . $path;
}

function _getPicURLSQL($tablefield, $outputname)
{
    $picdomain = App()->params['picdomain'];
    return " CASE WHEN $tablefield = '' THEN '' WHEN isnull($tablefield) THEN '' ELSE concat('$picdomain', $tablefield) END as $outputname ";
}

/**
 * 从数据库中获取配置值
 * @param $keyStrOrArr
 * @param bool|false $refreshCache
 * @return array|mixed
 */
function _getConfigFromDB($keyStrOrArr, $refreshCache = false)
{
    if(is_array($keyStrOrArr))
    {
        $keys = '(';
        $cacheKey = '_getConfigFromDB_';
        foreach($keyStrOrArr as $key)
        {
            if($keys == '(')
                $keys .= "'$key'";
            else
                $keys .= ",'$key'";
            $cacheKey .= $key;
        }
        $keys .= ')';
    }
    else
    {
        $keys = "('$keyStrOrArr')";
        $cacheKey = $keyStrOrArr;
    }

    $data = _S($cacheKey);
    if($refreshCache || empty($data))
    {
        $sql = "select `key`, `value` from configs where `key` in $keys";
        $res = _querySQL($sql);

        $data = array();
        foreach($res as $row)
            $data[$row['key']] = $row['value'];

        _S($cacheKey, $data);
    }

    return $data;
}

/**
 * @param $model CActiveRecord YiiModel
 */
function _getLastModelError($model)
{
    $errors = $model->getErrors();
    $errors = array_values($errors);
    $errors = $errors[0];
    if($errors)
        return $errors[0];
    return '';
}

/**
 * 以JSON格式输出错误信息并结束PHP脚本
 * @param $error_code
 * @param string $extra_msg
 */
function _error($error_code, $extra_msg = '')
{
    $result['state'] = 0;
    $error = __getErrorMsg($error_code);
    $result = array_merge($result, $error);
    $result['error_msg'] = $extra_msg == '' ? $error['error_text_ch'] : $extra_msg;
    _JSON($result);
}

function _errorWithHTTPStatus($status, $msg){
    header("HTTP/1.1 $status");
    exit($msg);
}

function __getErrorMsg($error_code)
{
    $errors[8] = array("Illegal access", '非法访问');
    $errors[9] = array("Unknown error", '未知错误');
//    $errors[10] = array("Missing 'Params' or can not be converted to JSON", '参数缺失或无法解析为JSON');
    $errors[97] = array('Token invalid', '认证失败, 请重新登录');
    $errors[98] = array('Query is null', '没有记录');
    $errors[99] = array('Paramter value invalid', '参数值不合法');
    $errors[100] = array('More paramters required', '参数不全');
    $errors[101] = array('User does not exist', '用户不存在');
    $errors[102] = array('Phone already existed', '手机号码已被使用');
    $errors[103] = array('Password format incorrect', '密码格式错误');
    $errors[104] = array('File size exceeded the limit', '文件大小超过限制');
    $errors[105] = array('File extension invalid', '文件扩展名不合法');
    $errors[106] = array('Invitecode invalid', '邀请码不可用');
    $errors[107] = array('Shopname already existed', '店铺名已被使用');
    $errors[108] = array('already existed', '已存在');//练习，增加
    $errors[110] = array('Password is not correct', '密码不正确');
    $errors[305] = array('Interval since the last SMS request is less than 60 seconds', '距上次有效短信请求间隔小于60秒');
    $errors[306] = array('Too many SMS request of same phone in one hour', '一小时内同一号码的短信请求数太多');
    $errors[307] = array('Send SMS failed', '发送短信失败');
    $errors[308] = array('Wrong mobile phone number', '无效的手机号码');
    $errors[310] = array('SMS code invalid', '短信验证码无效');
    $errors[311] = array('Too many invaild SMS code, try after one minute', '多次尝试错误验证码，请1分钟后再试');
    $errors[401] = array('Outdate goods version', '商品信息已过期');
    $errors[402] = array('Goods not for sale', '商品暂不售卖');
    $errors[403] = array('Point not enough', '用户积分不够此次交易');

    if ($error_code == null) {
        return $errors;
    }

    $temp = $errors[$error_code];
    $res['code'] = $error_code;
    $res['error_text'] = $temp[0];
    $res['error_text_ch'] = $temp[1];

    return $res;
}

/**
 * 验证token, 成功后返回User对象
 * @return array|mixed|null
 */
function _auth()
{
    $user = null;
    if (!Yii::app()->user->isGuest) {
        $user = User::model()->find('id=:id', array(':id' => Yii::app()->user->Id));
    }else{
        $empty_check[] = array('token', 'token不能为空', true);
        $param = _getParams($empty_check);

        $time = time();
        $sql = "select * from `token` where `token` = '$param[token]' and `time` > $time";
        $token = _querySQL($sql);
        if(!$token)
        {
            _error(97, 'token 不存在或已失效');
        }

        $uid = $token[0]['uid'];
        $user = User::model()->find('id=:id', array(':id' => $uid));
    }

    if(empty($user))
        _error(97);

    return $user;
}

//function _auth($allowEmptyParam = false)
//{
//    // 获取参数
//    $param = _getParams();
//
//    // 判断TOKEN是否为空
//    if (empty($param['token'])) {
//        if($allowEmptyParam) {
//            $user = new User();
//            $user -> id = 0;
//            return $user;
//        }
//        _error(100, 'TOKEN不存在');
//    }
//
//    // 按照TOKEN获取用户信息
//    $user = User::model()->find('token=:token', array(':token' => $param['token']));
//
//    // 判断用户是否存在
//    if (empty($user)) {
//        _error(97); // TOKEN 认证失败
//    }
//
//    return $user;
//}

/**
 * 将CActiveRecord模型的属性输出为数组
 * @param $models
 * @param $includeRelations bool 包含关系中的属性
 * @return array
 */
function _convertModelToArray($models, $includeRelations = false) {
    if (is_array($models))
        $arrayMode = TRUE;
    else {
        $models = array($models);
        $arrayMode = FALSE;
    }

    $result = array();
    foreach ($models as $model) {
        if($model == null){
            $all = [];
        }
        else{
            $attributes = _getAttributes($model);
            $relations = array();
            if($includeRelations){
                foreach ($model->relations() as $key => $related) {
                    if ($model->hasRelated($key)) {
                        $relations[$key] = _convertModelToArray($model->$key);
                    }
                }
            }
            $all = array_merge($attributes, $relations);
        }

        if ($arrayMode)
            array_push($result, $all);
        else
            $result = $all;
    }
    return $result;
}

function _getAttributes($model)
{
    $allAttrKeys = $model -> getSafeAttributeNames();
    $dbAttrs = array_keys($model -> getAttributes());

    $memberAttrKeys = array_diff($allAttrKeys, $dbAttrs);
    $selDbAttrs = $model -> getAttributes(false);

    return array_merge($selDbAttrs, $model -> getAttributes($memberAttrKeys));
}

function _getTimeString($time = 0)
{
    if($time == 0){
        $time = time();
    }

    return date("Y-m-d H:i:s",$time);
}

function _genCGridViewPicColumn($width = 40, $height = 40)
{
    return [
        'name' => 'pic_id',
        'header' => '图片',
        'type' => 'html',
        'value' => '__genCGridViewPicValue($data'.", $width, $height)",
        'htmlOptions' => array(
            'style' => 'width:50pt',
        ),
    ];
}

function __genCGridViewPicValue($data, $width, $height)
{
    $width = "width:$width"."pt";

    if($height == 0){
        $height = "";
    }else{
        $height = "height:$height"."pt";
    }

    echo sprintf('<img src = "%s" style="%s; %s" />', _getPicURLByModel($data->pic), $width, $height);
}

function _getCurrentUser()
{
    return User::model()->find('LOWER(username)=?', array(strtolower(App()->user->name)));
}

function _subStr($str, $start, $length)
{
    $sub = mb_substr($str,$start,$length,'utf-8');
    return $sub == $str ? $str : $sub . '...';
}

function _getAssignedRoles()
{
    $allRoles['Admin'] = false;
    $allRoles['Shop'] = false;

    if (!Yii::app()->user->isGuest) {
        $allRoles['Guest'] = false;
        $roles = Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
        foreach($roles as $role)
        {
            $allRoles[$role->name] = true;
        }
    }

    return $allRoles;
}

function _resourceVer()
{
    return '?v=' . App()->params->web_resource_version;
}