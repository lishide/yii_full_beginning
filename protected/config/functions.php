<?php
function App()
{
    return Yii::app();
}

function getRandOnlyId()
{
    $time = explode(' ', microtime());
    $newtime = $time[1] + $time[0] * 10000000;
    $all = sprintf('%04d', mt_rand(0, 9999)) . substr($newtime, 2) . sprintf('%04d', mt_rand(0, 9999));
    $onlyid = base_convert($all, 10, 32);//把10进制转为36进制的唯一ID
    return strtoupper($onlyid);
}

function genOrderSn($prefix = '')
{
    $year = date('y');
    $month = date('n');
    if ($month > 9) {
        switch ($month) {
            case 10:
                $month = 'A';
                break;
            case 11:
                $month = 'B';
                break;
            case 12:
                $month = 'C';
                break;
        }
    }
    $day = date('d');

    return $prefix . $year . $month . $day;
}

function getShortId($id = 0)
{
    if ($id < 10000)
        $shortId = sprintf('%04s', $id);
    else
        $shortId = substr($id, -4);
    return $shortId;
}

/**
 * 快捷生成url
 * @param  [type] $route [description]
 * @return [type]        [description]
 */
function url($route, $params = array())
{
    return Yii::app()->controller->createUrl($route, $params);
}

/**
 * 判断是否当前页面
 * @param  [type] $r [description]
 * @param  [type] $c [description]
 * @param  string $a [description]
 * @param  string $m [description]
 * @return [type]    [description]
 */
function isCur($c, $a = '', $m = 'user', $params = array(), $checkParams = true)
{
    $paramsCheck = true;
    if ($params && $checkParams) {
        $keys = array_keys($params);
        foreach ($keys as $key) {
            $getVal = Yii::app()->request->getParam($key);
            if ($getVal != $params[$key]) {
                $paramsCheck = false;
                break;
            }
        }
    }

    $module = '';
    if (Yii::app()->controller->module)
        $module = Yii::app()->controller->module->id;

    if ($module == $m) {
        $controller = Yii::app()->controller->id;
        if ($controller == $c) {
            if ($a == '') {
                return true && $paramsCheck;
            } else {
                $action = Yii::app()->controller->action->id;
                if ($action == $a)
                    return true && $paramsCheck;
            }
        }
    }
    return false;
}

/**
 * 快速生成会员中心的左侧链接
 * @param  [type] $text [description]
 * @param  [type] $c    [description]
 * @param  string $a [description]
 * @param  string $m [description]
 * @return [type]       [description]
 */
function uLink($text, $c, $a = '', $m = 'user', $params = array())
{
    $options = array();
    if (isCur($c, $a, $m, $params, true)) {
        $options['class'] = 'current';
    }
    $url = url('/' . $m . '/' . $c . '/' . $a, $params);
    return CHtml::link($text, $url, $options);
}

function cutstr($string, $length, $start = 0)
{
    $len = mb_strlen($string, 'utf-8');
    $re = mb_substr($string, $start, $length, 'utf-8');
    if ($len > $length)
        $re .= '...';
    return $re;
}

function colorMoney($val)
{
    $color = 'gray';
    if ($val > 0) {
        $color = 'green';
    } elseif ($val < 0) {
        $color = 'red';
    }
    return CHtml::tag('span', array('style' => 'color:' . $color), number_format($val, 2));
}

function colorExpdate($expdate)
{
    $cmd = Yii::app()->db->createCommand();
    $cmd->text = 'select `value` from configs t where t.key="domain_overday"';
    $overday = $cmd->queryScalar();

    if (!isset($overday)) {
        $overday = 0;
    }

    $today = strtotime(date("Y-m-d"));
    $dotoday = date("Y-m-d", strtotime('+' . $overday . 'day', $today));
    $expdate = date("Y-m-d", strtotime($expdate));
    $color = 'gray';
    if ($dotoday > $expdate) {
        $color = 'red';
    } else {
        $color = 'green';
    }
    return CHtml::tag('span', array('style' => 'color:' . $color), date("Y-m-d", strtotime($expdate)));
}

function getUserRank($uid = 0)
{
    if (!$uid) {
        if (Yii::app()->user->isGuest)
            return 0;
        else
            $uid = Yii::app()->user->getId();
    }

    $cmd = Yii::app()->db->createCommand();
    $cmd->text = 'select rank from user where id=:uid';
    return $cmd->queryScalar(array(':uid' => $uid));
}

/*
 * 获取可选业务员
 */
function getExpert($touid)
{
    $cmd = Yii::app()->db->createCommand();
    $cmd->text = 'select username from user where id=:uid and experted=1 and status=1';
    $username = $cmd->queryScalar(array(':uid' => $touid));
    if ($username)
        return $username;
    else
        return '';
}

/*
 * 获取专家名单
 */
function getExperts($fromuid)
{
    $experts = array(
        0 => '选择专家'
    );
    $list = User::model()->findAll(array(
        'select' => 'id,username',
        'condition' => 'id<>:uid and experted=1 and status=1',
        'params' => array(':uid' => intval($fromuid)),
    ));
    foreach ($list as $item) {
        $experts[$item->id] = $item->username;
    }

    return $experts;
}

/*
 * 多维数组排序
 */
function multi_array_sort($multi_array, $sort_key, $sort = SORT_ASC)
{
    if (is_array($multi_array)) {
        foreach ($multi_array as $row_array) {
            if (is_array($row_array)) {
                $key_array[] = $row_array[$sort_key];
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
    array_multisort($key_array, $sort, $multi_array);
    return $multi_array;
}

function getStringUTF($str, $length)
{
    if (strlen($str) > $length)
        return mb_substr($str, 0, $length, "utf-8") . "...";
    else
        return $str;
}

function colorStatus($val)
{
    $color = 'black';
    switch ($val) {
        case App()->params["task_status"][0]:
            $color = 'black';
            break;
        case App()->params["task_status"][1]:
            $color = 'red';
            break;
        case App()->params["task_status"][2]:
            $color = 'green';
            break;
        case App()->params["task_status"][3]:
            $color = 'black';
            break;
        case App()->params["task_status"][-1]:
            $color = 'gray';
            break;
        case App()->params["task_status"][-2]:
            $color = 'gray';
            break;
    }
    return CHtml::tag('span', array('style' => 'color:' . $color), $val);
}

// 移动端判断
function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

// 创建支付宝PC支付
function createAlipayDirectPay($orderNo, $subjectP, $total, $bodyP = '')
{
    require_once(YII_PATH . "/../protected/extensions/alipayDirectPay/lib/alipay_submit.class.php");

    $alipay_config = App()->params->alipayDirectPay;
    //商户订单号，商户网站订单系统中唯一订单号，必填
    $out_trade_no = $orderNo;
    //订单名称，必填
    $subject = $subjectP;
    //付款金额，必填
    $total_fee = $total;
    //商品描述，可空
    $body = $bodyP;

    //构造要请求的参数数组，无需改动
    $parameter = array(
        "service" => $alipay_config['service'],
        "partner" => $alipay_config['partner'],
        "seller_id" => $alipay_config['seller_id'],
        "payment_type" => $alipay_config['payment_type'],
        "notify_url" => $alipay_config['notify_url'],
        "return_url" => $alipay_config['return_url'],

        "anti_phishing_key" => $alipay_config['anti_phishing_key'],
        "exter_invoke_ip" => $alipay_config['exter_invoke_ip'],
        "out_trade_no" => $out_trade_no,
        "subject" => $subject,
        "total_fee" => $total_fee,
        "body" => $body,
        "_input_charset" => trim(strtolower($alipay_config['input_charset'])),

        // 超时时间
        "it_b_pay" => "5m"
    );

    //建立请求
    $alipaySubmit = new AlipaySubmit($alipay_config);
    $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "正在跳转...");

    echo $html_text;
    return true;
}

// 创建支付宝WAP支付
function createAlipayTradeWapPay($orderNo, $subjectP, $total, $bodyP = '')
{
    $config = App()->params->alipayTradeWapPay;
    include(YII_PATH . "/../protected/extensions/alipayTradeWapPay/wappay/service/AlipayTradeService.php");
    include(YII_PATH . "/../protected/extensions/alipayTradeWapPay/aop/request/AlipayTradeWapPayRequest.php");
    include(YII_PATH . "/../protected/extensions/alipayTradeWapPay/aop/AopClient.php");
    include(YII_PATH . "/../protected/extensions/alipayTradeWapPay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php");

    //商户订单号，商户网站订单系统中唯一订单号，必填
    $out_trade_no = $orderNo;

    //订单名称，必填
    $subject = $subjectP;

    //付款金额，必填
    $total_amount = $total;

    //商品描述，可空
    $body = $bodyP;

    //超时时间
    $timeout_express = "5m";

    $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
    $payRequestBuilder->setBody($body);
    $payRequestBuilder->setSubject($subject);
    $payRequestBuilder->setOutTradeNo($out_trade_no);
    $payRequestBuilder->setTotalAmount($total_amount);
    $payRequestBuilder->setTimeExpress($timeout_express);

    $payResponse = new \AlipayTradeService($config);
    $result = $payResponse->wapPay($payRequestBuilder, $config['return_url'], $config['notify_url']);

    return $result;
}

// 创建支付宝批量转账
/**
 * @param double $batchFee 付款总金额
 * @param int $batchNum 付款总笔数
 * @param array $detailData 付款详细数据
 * @return bool
 */
function createAlipayBatchTrans($batchFee, $batchNum, $detailData)
{
    $alipay_config = App()->params->alipayBatchTrans;
    require_once(YII_PATH . "/../protected/extensions/alipayBatchTrans/lib/alipay_submit.class.php");

    // 付款账号 必填
    $email = $alipay_config['WIDemail'];

    // 付款账户名 必填，个人支付宝账号是真实姓名公司支付宝账号是公司名称
    $account_name = $alipay_config['WIDaccount_name'];

    // 付款当天日期 必填，格式：年[4位]月[2位]日[2位]，如：20100801
    $pay_date = date('Ymd');

    // 批次号 必填，格式：当天日期[8位]+序列号[3至16位]，如：201008010000001
    $batch_no = _genOrderSn('alipay_batch');

    // 付款总金额 必填，即参数detail_data的值中所有金额的总和
    $batch_fee = $batchFee;

    // 付款笔数 必填，即参数detail_data的值中，“|”字符出现的数量加1，最大支持1000笔（即“|”字符出现的数量999个）
    $batch_num = $batchNum;

    //付款详细数据 必填，格式：流水号1^收款方帐号1^真实姓名^付款金额1^备注说明1|流水号2^收款方帐号2^真实姓名^付款金额2^备注说明2....
    $detail_data = '';
    foreach ($detailData as $key => $detail) {
        $detail_data .= $detail['sn'].'^';
        $detail_data .= $detail['alipay'].'^';
        $detail_data .= $detail['realname'].'^';
        $detail_data .= $detail['realamount'].'^';
        $detail_data .= $detail['mark'];
        // 如果不是最后一个 加 |
        if ($key + 1 != count($detailData)) {
            $detail_data .= '|';
        }
    }

    // 构造要请求的参数数组，无需改动
    $parameter = array(
        "service" => "batch_trans_notify",
        "partner" => trim($alipay_config['partner']),
        "notify_url" => $alipay_config['notify_url'],
        "email" => $email,
        "account_name" => $account_name,
        "pay_date" => $pay_date,
        "batch_no" => $batch_no,
        "batch_fee" => $batch_fee,
        "batch_num" => $batch_num,
        "detail_data" => $detail_data,
        "_input_charset" => trim(strtolower($alipay_config['input_charset']))
    );

    // 建立请求
    $alipaySubmit = new AlipaySubmit(App()->params->alipayBatchTrans);
    $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "正在跳转...");
    echo $html_text;

    return true;
}

function getIntroduceChild($father_uid, &$child_array=array())
{
    $childm = User::model()->findAll([
        'select' => ['t.id'],
        'condition' => "t.invite_uid = $father_uid"
    ]);
    $child = _convertModelToArray($childm);
    $child_length = count($child);
    for ($j = 0; $j < $child_length; $j++) {
        $child_id = $child[$j]['id'];
        $child_array[] = $child_id;
        getIntroduceChild($child_id, $child_array);
    }
    return $child_array;
}

function getTreeChild($father_uid, $two, &$child_array=array())
{
    $childm = InviteRls::model()->findAll([
        'select' => ['t.child_uid'],
        'condition' => "t.father_uid = $father_uid"
    ]);
    $child = _convertModelToArray($childm);
    $child_length = count($child);
    for ($j = 0; $j < $child_length; $j++) {
        $child_id = $child[$j]['child_uid'];
        $child_array[] = $child_id;
        if ($two) {
            $um = User::model()->findAll([
                'select' => ['*'],
                'condition' => "t.id = $child_id and role = 2"
            ]);
            if (!$um) {
                getTreeChild($child_id, $two,$child_array);
            }
        } else {
            getTreeChild($child_id, $two,$child_array);
        }
    }
    return $child_array;
}

function updateUserChild()
{
    $users = XUser::model()->findAll([
        'select' => ['*'],
        'condition' => "1"
    ]);
    //更新所有用户的小区人数
    foreach ($users as $user) {
        //计算左中右小区人数
        $left = InviteRls::getLeftChildNumber($user->id);
        $middle = InviteRls::getMiddleChildNumber($user->id);
        $right = InviteRls::getRightChildNumber($user->id);
        $user->leftchild = $left;
        $user->middlechild = $middle;
        $user->rightchild = $right;
        $user->save();
    }
}

