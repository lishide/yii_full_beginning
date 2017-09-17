<?php

/**
 * 操作并获取用户的账户信息
 * @author eclanp <eclanp@qq.vip.com>
 */
class Account extends CApplicationComponent
{
    protected $cmd = null;

    public function __construct()
    {
        $this->cmd = Yii::app()->db->createCommand();
    }

    /**
     * 获取用户账户信息
     * @param  integer $uid
     * @return float
     */
    public function get($key = 'balance', $uid = 0)
    {
        $uid = $this->parseUid($uid);
        if (!$uid)
            return 0.00;
        if (!in_array($key, array('balance', 'forzen', 'benefit')))
            return 0.00;
        $this->cmd->setText('SELECT `' . $key . '` FROM `user` WHERE id=:uid');
        $this->cmd->params = array(':uid' => $uid);
        $val = $this->cmd->queryScalar();
        return $val;
    }

    /**
     * 更新用户账户
     *
     * <pre>
     * try {
     *     Yii::app()->account->update(-15.00, 15.00, 0.00,'测试', 2, 'order', '201302042123');
     * } catch (AccountException $e) {
     *     echo $e->getMessage();
     * }
     * </pre>
     *
     * @param  float $balance
     * @param  float $forzen
     * @param  string $intro
     * @param  integer $uid
     * @param  string $tbl
     * @param  string $sn
     * @throws AccountException
     */
    public function update($balance, $forzen = 0.00, $benefit = 0.00, $intro = '', $uid = 0, $tbl = '', $sn = '')
    {
        $uid = $this->parseUid($uid);
        if (!$uid)
            return false;

        $balance = floatval($balance);
        $forzen = floatval($forzen);
        $benefit = floatval($benefit);

        $usemoney = $this->get('balance', $uid);
        $bret = $usemoney + $balance;

        $fozmoney = $this->get('forzen', $uid);
        $fret = $fozmoney + $forzen;

        $benemoney = $this->get('benefit', $uid);
        $zret = $benemoney + $benefit;

        if ($bret < 0) { //当可用积分少于要减去的积分时，抛出异常
            throw new AccountException('账户可用余额不足，本次操作未完成！');
        }

        if ($fret < 0) {
            throw new AccountException('账户冻结金额不足，本次操作未完成！');
        }

        if ($zret < 0) {
            throw new AccountException('账户赠送金额不足，本次操作未完成！');
        }

        if (!Yii::app()->user->isGuest)
            $oper_uid = Yii::app()->user->getId();
        else
            $oper_uid = $uid;

        $this->cmd->update('user', array('balance' => $bret, 'forzen' => $fret, 'benefit' => $zret), 'id=:uid', array(':uid' => $uid));

        $log = array(
            'uid' => $uid,
            'oper_uid' => $oper_uid,
            'tbl' => $tbl,
            'sn' => $sn,
            'bval' => $balance,
            'bret' => $bret,
            'fval' => $forzen,
            'fret' => $fret,
            'zval' => $benefit,
            'zret' => $zret,
            'intro' => $intro,
            'createddate' => date('Y-m-d H:i:s'),
        );
        $this->cmd->insert('accountlog', $log);

        return Yii::app()->db->getLastInsertID();
    }

    /**
     * 更新用户账户2，从赠送账户扣取
     *
     * <pre>
     * try {
     *     Yii::app()->account->update(-15.00, 15.00, 0.00,'测试', 2, 'order', '201302042123');
     * } catch (AccountException $e) {
     *     echo $e->getMessage();
     * }
     * </pre>
     *
     * @param  float $balance
     * @param  float $forzen
     * @param  string $intro
     * @param  integer $uid
     * @param  string $tbl
     * @param  string $sn
     * @throws AccountException
     */
    public function update2($balance, $forzen = 0.00, $benefit = 0.00, $intro = '', $uid = 0, $tbl = '', $sn = '', $type = 0, $feeType = '')
    {
        $uid = $this->parseUid($uid);
        if (!$uid)
            return false;

        $balance = floatval($balance);
        $forzen = floatval($forzen);
        $benefit = floatval($benefit);

        $accountfrom2 = $balance . '|' . $forzen . '|' . $benefit;

        $feeRate = intval(Configs::model()->getByKey('promo' . $feeType . '_fee')) / 100;

        #region 判断活动是否正在进行
//        if (Configs::model()->getByKey('promo'.$feeType.'_started') != "1" ||
//            date('Y-m-d h:i:s') < Configs::model()->getByKey('promo'.$feeType.'_startdate') ||
//            date('Y-m-d h:i:s') > Configs::model()->getByKey('promo'.$feeType.'_enddate') || $feeRate == 0)
//        {
//            #region 普通出入账
//            App()->account->update($balance,$forzen,$benefit,$intro,$uid,$tbl,$sn,$accountfrom2);
//            #endregion
//        }
        #endregion

        if (intval($type) == 0) {
            #region 普通出入账
            App()->account->update3($balance, $forzen, $benefit, $intro, $uid, $tbl, $sn, $accountfrom2);
            #endregion
        } else if (intval($type) == 1) {
            if ($balance > 0.00) {
                #region 进账到赠送账户和可用账户($balance>0表示进账金额)
                $this->cmd->setText('SELECT `accountfrom` FROM `AccountLog` WHERE tbl=:tbl and sn=:sn and uid=:uid and accountfrom<>"0|0|0" order by createddate desc');
                $this->cmd->params = array(':tbl' => $tbl, ':sn' => $sn, ':uid' => $uid);
                $accountfrom = $this->cmd->queryScalar();

                if (isset($accountfrom) && !empty($accountfrom)) {
                    $arrAccountFrom = explode('|', $accountfrom);
                    if (floatval($arrAccountFrom[0]) + floatval($arrAccountFrom[2]) == -$balance) {
                        $accountfrom2 = -floatval($arrAccountFrom[0]) . '|' . $forzen . '|' . -floatval($arrAccountFrom[2]);
                        App()->account->update3(-floatval($arrAccountFrom[0]), $forzen, -floatval($arrAccountFrom[2]), $intro, $uid, $tbl, $sn, $accountfrom2);
                    } else {
                        App()->account->update3($balance, $forzen, $benefit, $intro, $uid, $tbl, $sn, $accountfrom2);
                    }
                } else {
                    App()->account->update3($balance, $forzen, $benefit, $intro, $uid, $tbl, $sn, $accountfrom2);
                }
                #endregion
            } else {
                App()->account->update3($balance, $forzen, $benefit, $intro, $uid, $tbl, $sn, $accountfrom2);
            }
        } else if (intval($type) == -1) {
            if (($balance < 0.00 && $forzen > 0.00) || ($balance < 0.00 and $forzen == 0.00)) {
                #region 从赠送账户和可用账户出账($balance<0 and $forzen>0 表示冻结,$balance<0 and $forzen=0 表示直接付钱)
                $remainBenefit = floatval(App()->account->get('benefit', $uid));
                $prepayBenefit = $balance * $feeRate;
                if ($feeRate != 0 && $remainBenefit == 0) {
                    //赠送账户没钱
                    App()->account->update3($balance, $forzen, $benefit, $intro, $uid, $tbl, $sn, $accountfrom2);
                } else if ($feeRate != 0 && $remainBenefit > -$prepayBenefit) {
                    //赠送账户按照比例够钱
                    $prepayBalance = $balance * (1 - $feeRate);
                    $accountfrom = $prepayBalance . '|' . $forzen . '|' . $prepayBenefit;

                    App()->account->update3($prepayBalance, $forzen, $prepayBenefit, $intro, $uid, $tbl, $sn, $accountfrom);
                } else if ($feeRate != 0) {
                    //赠送账户剩下余额全部付完，其他从可用账户扣除
                    $prepayBalance = $balance + $remainBenefit;
                    $accountfrom = $prepayBalance . '|' . $forzen . '|' . -$remainBenefit;

                    App()->account->update3($prepayBalance, $forzen, -$remainBenefit, $intro, $uid, $tbl, $sn, $accountfrom);
                } else {
                    App()->account->update3($balance, $forzen, $benefit, $intro, $uid, $tbl, $sn, $accountfrom2);
                }
                #endregion
            } else {
                App()->account->update3($balance, $forzen, $benefit, $intro, $uid, $tbl, $sn, $accountfrom2);
            }
        }

        return Yii::app()->db->getLastInsertID();
    }

    /**
     * 更新用户账户
     *
     * <pre>
     * try {
     *     Yii::app()->account->update(-15.00, 15.00, 0.00,'测试', 2, 'order', '201302042123');
     * } catch (AccountException $e) {
     *     echo $e->getMessage();
     * }
     * </pre>
     *
     * @param  float $balance
     * @param  float $forzen
     * @param  string $intro
     * @param  integer $uid
     * @param  string $tbl
     * @param  string $sn
     * @throws AccountException
     */
    public function update3($balance, $forzen = 0.00, $benefit = 0.00, $intro = '', $uid = 0, $tbl = '', $sn = '', $accountfrom = '0|0|0')
    {
        $uid = $this->parseUid($uid);
        if (!$uid)
            return false;

        $balance = floatval($balance);
        $forzen = floatval($forzen);
        $benefit = floatval($benefit);

        $usemoney = $this->get('balance', $uid);
        $bret = $usemoney + $balance;

        $fozmoney = $this->get('forzen', $uid);
        $fret = $fozmoney + $forzen;

        $benemoney = $this->get('benefit', $uid);
        $zret = $benemoney + $benefit;

        if ($bret < 0) { //当可用积分少于要减去的积分时，抛出异常
            throw new AccountException('账户可用余额不足，本次操作未完成！');
        }

        if ($fret < 0) {
            throw new AccountException('账户冻结金额不足，本次操作未完成！');
        }

        if ($zret < 0) {
            throw new AccountException('账户赠送金额不足，本次操作未完成！');
        }

        if (!Yii::app()->user->isGuest)
            $oper_uid = Yii::app()->user->getId();
        else
            $oper_uid = $uid;

        $this->cmd->update('user', array('balance' => $bret, 'forzen' => $fret, 'benefit' => $zret), 'id=:uid', array(':uid' => $uid));

        $log = array(
            'uid' => $uid,
            'oper_uid' => $oper_uid,
            'tbl' => $tbl,
            'sn' => $sn,
            'bval' => $balance,
            'bret' => $bret,
            'fval' => $forzen,
            'fret' => $fret,
            'zval' => $benefit,
            'zret' => $zret,
            'accountfrom' => $accountfrom,
            'intro' => $intro,
            'createddate' => date('Y-m-d H:i:s'),
        );
        $this->cmd->insert('accountlog', $log);

        return Yii::app()->db->getLastInsertID();
    }

    /**
     * 处理UID
     * @param  integer $uid
     * @return integer
     */
    private function parseUid($uid)
    {
        $uid = intval($uid);
        if (!$uid) {
            $uid = Yii::app()->user->getId();
        }
        return $uid;
    }
}


class AccountException extends Exception
{
}