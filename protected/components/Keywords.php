<?php

class Keywords extends CApplicationComponent
{
    public $apitype = 0;
    private $http = null;
    private $apiurl = array(
        'http://tool.phpcms.cn/api/get_keywords.php',
        'http://www.xunsearch.com/scws/api.php',
    );

    public function __construct()
    {
        Yii::import('application.vendor.httpclient.http');
        $this->http = new http();
    }

    public function getKws($data, $number = 3)
    {
        $data = trim(strip_tags($data));
        $keys = '';

        if ($this->apitype == 0) {
            $data = iconv('utf-8', 'gbk', $data);
            $this->http->post($this->apiurl[$this->apitype], array(
                'data' => $data,
                'number' => $number,
            ));
            if ($this->http->is_ok()) {
                $keys = iconv('gbk', 'utf-8', $this->http->get_data());
                $keys = str_replace('，', '', $keys);
                $keys = str_replace(',', '', $keys);
                $keys = str_replace('、', '', $keys);
            }
        } else {
            $this->http->post($this->apiurl[$this->apitype], array(
                'data' => $data,
                'respond' => 'json',
                'charset' => 'utf8',
                'ignore' => 'yes',
                'duality' => 'no',
                'multi' => 4,
            ));
            if ($this->http->is_ok()) {
                $ret = $this->http->get_data();
                $ret = json_decode($ret);

                $keys = $ret;
                $w = array();
                foreach ($ret->words as $r) {
                    if (count($w) == $number)
                        break;
                    if (in_array($r->word, array('，', ',', '、')))
                        continue;
                    $w[] = $r->word;
                }
                $keys = implode(',', $w);
            }
        }
        return $keys;
    }
}