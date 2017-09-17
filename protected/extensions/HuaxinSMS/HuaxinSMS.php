<?php


class HuaxinSMS extends CComponent
{
    public function sendToAPI($phone, $number, $type)
    {
        $postContent = 'action=send&userid=conferplat&account=jksc227&password=jksc22766&sendTime=&extno=';
        $postContent .= '&mobile='.$phone;
        $postContent .= '&content='.$this->getMsgByType($type, $number);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);				// 超时时间（秒）
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		// 是否返回结果
        curl_setopt($ch, CURLOPT_URL, 'http://sh2.ipyy.com/smsJson.aspx?'.$postContent);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$postContent);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    private function getMsgByType($type, $code)
    {
        switch(strtolower($type))
        {
            case 'reg':
                $msg = "您的验证码是$code,请不要把验证码泄露给其他人。如非本人操作，可不用理会！【超级论文】";
                break;
            default:
                $msg = "您的验证码是$code,请不要把验证码泄露给其他人。如非本人操作，可不用理会！【超级论文】";
                break;
        }
        return urlencode(mb_convert_encoding($msg, "UTF-8", "auto"));
    }
}