<?php

/**
 * Created by PhpStorm.
 * User: Hapon
 * Date: 2017/1/26
 * Time: 下午8:00
 */
class OrdersController extends Controller
{
    public function actionList()
    {
        $this->render('list');
    }

    public function actionDetail()
    {
        $p = _getParams([
            ['sn']
        ], true);

        $this->render('detail', ['sn' => $p['sn']]);
    }

    public function actionPay()
    {
        $p = _getParams([
            ['token'], ['sn'], ['balance']
        ], true);

        $user = _auth(true, false, true);
        $uid = $user->id;
        $sn = $p['sn'];
        $balance = sprintf("%.2f", $p['balance']);

        /**
         * @var $order Orders
         */
        $order = Orders::model()->find('uid=:uid and sn=:sn and status=1', array(':uid' => $uid, ':sn' => $sn));

        // 订单存在
        if ($order) {
            $price = (float)($order->price);

            // 如果已经使用过余额或创建过支付宝订单, 则不处理余额
            if ($balance != 0 && $order->use_balance == 0 && empty($order->alipay_sn)) {
                $maxBalance = $user->balance;
                if ($price < $maxBalance) {
                    $maxBalance = $price;
                }

                if ($balance < 0) {
                    exit('非法操作');
                } else if ($balance > $maxBalance) {
                    exit('余额不足');
                }

                $order->use_balance = $balance;
                $order->save();

                $user->balance = $user->balance - $balance;
                $user->save();

                $moneylog = new Moneylog();
                $moneylog->setAttributes([
                    'uid' => $user->id,
                    'ordersn' => $sn,
                    'moneychange' => -1 * $balance,
                    'moneyleft' => $user->balance,
                    'tradetype' => 4
                ]);
                $moneylog->save();
            }

            $price = (intval($price * 100) - intval($order->use_balance * 100)) / 100.0;

            if ($price <= 0) {
                $order->status = 2;
                $order->save();
                $order->orderPayed();

                $this->redirect(App()->baseUrl . '/web/orders/detail/sn/' . $sn);
                return;
            }

            // 判断是PC还是WAP
            if (isMobile()) {
                // 创建手机支付宝订单
                return createAlipayTradeWapPay($order->sn, '订单支付 #' . $order->sn, $price);
            } else {
                // 创建PC支付宝订单
                return createAlipayDirectPay($order->sn, '订单支付 #' . $order->sn, $price);
            }
        }

        return '非法操作';
    }

}