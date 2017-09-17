<?php

/**
 * Created by PhpStorm.
 * User: HaPBoy
 * Date: 2017/1/27
 * Time: 上午00:03
 */
class PayController extends Controller
{
    // 支付宝PC异步回调
    public function actionPCNotify()
    {
        $alipay_config = App()->params->alipayDirectPay;
        require_once(YII_PATH . "/../protected/extensions/alipayDirectPay/lib/alipay_notify.class.php");

        // 计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        $log = new Log();
        $log->content = json_encode($_POST);
        $log->save();

        $log = new Log();
        $log->content = sprintf('$verify_result = %s', $verify_result ? 'true' : 'false');
        $log->save();

        //验证成功
        if ($verify_result) {
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            // 订单支付金额
            $total_amount = $_POST['total_fee'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            // 订单
            $order = Orders::model()->find('sn=:sn and status=1', array(':sn' => $out_trade_no));

            if ($trade_status == 'TRADE_FINISHED') {
                // 退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

            } else if ($trade_status == 'TRADE_SUCCESS') {
                // 付款完成后，支付宝系统发送该交易状态通知

                // 订单未处理 状态为1:待付款
                if ($order) {
                    // 验证订单金额
                    if ($order->price == $total_amount) {
                        // 更新订单状态
                        $order->status = 2; // 2: 待发货

                        // 支付宝交易号
                        $order->alipay_sn = $trade_no;

                        $order->save();

                        // TODO 其他业务逻辑
                        $order->orderPayed();

                    } else {
                        // 订单金额不一致
                        // 不处理
                    }
                } else {
                    // 订单不存在或状态不是1:待付款
                    // 不处理
                }
            }
            echo "success";         // 请不要修改或删除
        } else {
            echo "fail";            // 请不要修改或删除
        }
    }

    // 支付宝PC同步回调
    public function actionPCReturn()
    {
        $alipay_config = App()->params->alipayDirectPay;
        require_once(YII_PATH . "/../protected/extensions/alipayDirectPay/lib/alipay_notify.class.php");

        // 计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();

        // 验证成功
        if ($verify_result) {
            // 商户订单号
            $out_trade_no = $_GET['out_trade_no'];
            // 支付宝交易号
            $trade_no = $_GET['trade_no'];
            // 交易状态
            $trade_status = $_GET['trade_status'];

            // 订单
            $order = Orders::model()->find('sn=:sn', array(':sn' => $out_trade_no));

            // 验证订单是否存在，存在则跳转
            if ($order) {
                $this->redirect('/web/orders/detail/sn/' . $order->sn);
            }
        }

        // 否则跳回首页
        $this->redirect('/');
    }

    // 支付宝WAP异步回调
    public function actionWapNotify()
    {
        $config = App()->params->alipayTradeWapPay;
        include(YII_PATH . "/../protected/extensions/alipayTradeWapPay/wappay/service/AlipayTradeService.php");
        include(YII_PATH . "/../protected/extensions/alipayTradeWapPay/aop/request/AlipayTradeWapPayRequest.php");
        include(YII_PATH . "/../protected/extensions/alipayTradeWapPay/aop/AopClient.php");

        // 计算得出通知验证结果
        $arr = $_POST;
        $alipaySevice = new AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST, true));
        $result = $alipaySevice->check($arr);

        //验证成功
        if ($result) {
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            // 订单支付金额
            $total_amount = $_POST['total_amount'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            // 订单
            /**
             * @var $order Orders
             */
            $order = Orders::model()->find('sn=:sn and status=1', array(':sn' => $out_trade_no));

            if ($trade_status == 'TRADE_FINISHED') {
                // 退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

            } else if ($trade_status == 'TRADE_SUCCESS') {
                // 付款完成后，支付宝系统发送该交易状态通知

                // 订单未处理 状态为1:待付款
                if ($order) {
                    // 验证订单金额
                    if ($order->price == $total_amount) {
                        // 更新订单状态
                        $order->status = 2; // 2: 待发货

                        // 支付宝交易号
                        $order->alipay_sn = $trade_no;

                        $order->save();

                        // TODO 其他业务逻辑
                        $order->orderPayed();
                    } else {
                        // 订单金额不一致
                        // 不处理
                    }
                } else {
                    // 订单不存在或状态不是1:待付款
                    // 不处理
                }
            }
            echo "success";         // 请不要修改或删除
        } else {
            echo "fail";            // 请不要修改或删除
        }
    }

    // 支付宝WAP同步回调
    public function actionWapReturn()
    {
        $config = App()->params->alipayTradeWapPay;
        include(YII_PATH . "/../protected/extensions/alipayTradeWapPay/wappay/service/AlipayTradeService.php");
        include(YII_PATH . "/../protected/extensions/alipayTradeWapPay/aop/request/AlipayTradeWapPayRequest.php");
        include(YII_PATH . "/../protected/extensions/alipayTradeWapPay/aop/AopClient.php");

        // 计算得出通知验证结果
        $arr = $_GET;
        $alipaySevice = new AlipayTradeService($config);
        $result = $alipaySevice->check($arr);

        // 验证成功
        if ($result) {
            // 商户订单号
            $out_trade_no = $_GET['out_trade_no'];
            // 支付宝交易号
            $trade_no = $_GET['trade_no'];
            // 订单
            $order = Orders::model()->find('sn=:sn', array(':sn' => $out_trade_no));

            // 验证订单是否存在，存在则跳转
            if ($order) {
                $this->redirect('/web/orders/detail/sn/' . $order->sn);
            }
        }

        // 否则跳回首页
        $this->redirect('/');
    }

    // 支付宝批量转账异步回调
    public function actionTransNotify()
    {
        $alipay_config = App()->params->alipayBatchTrans;
        require_once(YII_PATH . "/../protected/extensions/alipayBatchTrans/lib/alipay_notify.class.php");

        // 计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        // 验证成功
        if ($verify_result) {
            // 批量付款数据中转账成功的详细信息
            $success_details = $_POST['success_details'];
            $successArray = $this->transResultToArray($success_details);

            // 处理成功订单
            foreach ($successArray as $item) {
                $order = WithdrawOrder::model()->find('sn=:sn', array(':sn' => $item['sn']));
                // 订单存在
                if ($order) {
                    // 如果不是待处理，跳过
                    if ($order->status != 1) {
                        continue;
                    }
                    $order->status = 2; // 2 成功
                    $order->save();
                }
            }

            // 批量付款数据中转账失败的详细信息
            $fail_details = $_POST['fail_details'];
            $failArray = $this->transResultToArray($fail_details);

            // 处理失败订单
            foreach ($failArray as $item) {
                $order = WithdrawOrder::model()->find('sn=:sn', array(':sn' => $item['sn']));
                // 订单存在
                if ($order) {
                    // 如果不是待处理，跳过
                    if ($order->status != 1) {
                        continue;
                    }
                    $order->status = 3; // 3 失败
                    $order->reason = $item['reason'];
                    $order->save();
                }
            }

            // 请不要修改或删除
            echo "success";
        } else {
            // 验证失败
            echo "fail";
        }
    }

    // 将支付宝的批量转账回调结果转换成数组
    function transResultToArray($str)
    {
        $result = array();
        $orderStrListArray = explode('|', $str);
        foreach ($orderStrListArray as $orderStr) {
            $orderStrArray = explode('^', $orderStr);
            if (count($orderStrArray) != 8) {
                continue;
            }
            $result[] = array(
                'sn' => $orderStrArray[0], // 流水号
                'alipay' => $orderStrArray[1], // 收款方账号
                'realname' => $orderStrArray[2], // 收款账号姓名
                'amount' => $orderStrArray[3], // 付款金额
                'flag' => $orderStrArray[4], // 成功标识(S)/失败标识(F)
                'reason' => $orderStrArray[5], // 成功原因(null)/失败原因
                'alipay_sn' => $orderStrArray[6], // 支付宝内部流水号
                'finish_time' => $orderStrArray[7], // 完成时间
            );
        }
        var_dump($result);
        return $result;
    }
}