<?php

/**
 * 请注意 此代码未经调试, 无法正常使用
 */

class SMSAPI extends CComponent
{
    /**
     * 发送短信验证码
     * @param $phone
     * @param $type
     * @return int
     */
    public function send($phone, $type)
    {
        $status_code = $this->checkFrequency($phone, $type);

        if($status_code === 0)
        {
            $status_code = $this->sendToAPI($phone, $type);
            $this->afterSend($phone, $type, $status_code);
        }

        $errors[0] = '发送成功';
        $errors[301] = '发送失败';
        $errors[305] = '距上次有效短信请求间隔小于60秒';
        $errors[306] = '一小时内同一号码的短信请求数太多';
        $errors[310] = '短信验证码无效';
        $errors[311] = '多次尝试错误验证码，请1分钟后再试';

        return array('status' => $status_code, 'msg' => $errors[$status_code]);
    }

    /**
     * 检查短信验证码是否正确
     * @param $smsCode
     * @param $phone
     * @param $type
     * @return int
     */
    public function checkSMSCode($smsCode, $phone, $type)
    {
        $time = time() - App()->params['smscode_expire'];
        $smsStartTime = _S('sms_retrieve_starttime_'.$type.$phone);

        if(empty($smsStartTime) == false)
        {
            return 311;
        }

        $smsCode = (int)$smsCode;
        $sql = "select * from sms where `type` = '$type' and `server_code` = 'success' and `sms` = $smsCode and `phone` = '$phone' and `time` >= $time";
        if(count(_querySQL($sql)) == 0)
        {
            $smsCount = _S('sms_retrieve_count_'.$type.$phone) + 1;
            _S('sms_retrieve_count_'.$type.$phone, $smsCount, 60);
            if($smsCount > 2)
            {
                _S('sms_retrieve_starttime_'.$type.$phone, time(), 60);
            }

            return 310;
        }
        else
        {
            _S('sms_retrieve_count_'.$type.$phone, 0, 60);
        }
        return 0;
    }

    private function sendToAPI($phone, $type)
    {
        $number = '123456';

        $data_db = new SMS;
        $data_db -> type = $type;
        $data_db -> server_code = 'success';
        $data_db -> sms = $number;
        $data_db -> phone = $phone;
        $data_db -> time = time();
        $data_db -> client_ip = Yii::app()->request->userHostAddress;
        $data_db -> extra = '';

        $data_db -> save();

        return 0;
//        $SMS = Yii::createComponent('application.extensions.HuaxinSMS.HuaxinSMS');
//        $number = rand(1000,9999);
//        $data = $SMS -> sendToAPI($phone, $number, $type);
//        $dataArr = json_decode($data, true);
//
//        $data_db = new SMS;
//        $data_db -> type = $type;
//        $data_db -> server_code = $dataArr['returnstatus'];
//        $data_db -> sms = $number;
//        $data_db -> phone = $phone;
//        $data_db -> time = time();
//        $data_db -> client_ip = Yii::app()->request->userHostAddress;
//        $data_db -> extra = $data;
//
//        $data_db -> save();
//
//        switch(strtolower($dataArr['returnstatus']))
//        {
//            case 'success': $code = 0;break;
//            default: $code = 301;     //失败
//        }

//        return $code;
    }

    /**
    检查发送频率是否符合限制：
    号码和类型相同的短信，一小时内最多发送10条
    而且两次发送间隔必须大于30秒
     */
    private function checkFrequency($phone, $type)
    {
        $smsCount = _S('sms_auth_count_'.$type.$phone);
        $smsStartTime = _S('sms_auth_starttime_'.$type.$phone);
        $smsLastTime = _S('sms_auth_lasttime_'.$type.$phone);

        if($smsCount > 9 && (time() - $smsStartTime) < 3600)
        {
            return 306;
        }
        else if(empty($smsLastTime) == false)
        {
            return 305;
        }
        return 0;
    }

    /**
    发送后更新验证码频率限制统计信息
     */
    private function afterSend($phone, $type, $errorCode)
    {
        if($errorCode == 0)
        {
            $smsStartTime = _S('sms_auth_starttime_'.$type.$phone);
            $smsCount = _S('sms_auth_count_'.$type.$phone);

            // $smsStartTime为0，代表上次一发送本短信时间在一小时之前或从未发送过
            if($smsStartTime==0)
            {
                // 保存本条短信第一次发送的时间，有效期为1小时
                _S('sms_auth_starttime_'.$type.$phone, time(), 3600);
                // 将本短信发送次数初始化为0
                _S('sms_auth_count_'.$type.$phone, 0, 3600);
            }
            else
            {
                // 本短信发送次数加1
                _S('sms_auth_count_'.$type.$phone, $smsCount + 1, 3600);
            }
            // 更新本短信最后一次发送的时间，用于限制30秒内只能发送一次
            _S('sms_auth_lasttime_'.$type.$phone, time(), 30);
        }
    }
}