<?php
/* PHP SDK
 * @version 2.0.0
 * @author connect@qq.com
 * @copyright © 2013, Tencent Corporation. All rights reserved.
 */

require_once(QQ_API_ROOT."ErrorCase.class.php");

class Recorder {
    private static $data;
    private $inc;
    private $error;

    public function __construct(){
        $this->error = new ErrorCase();

        //-------读取配置文件
		//$this->inc = require (QQ_API_ROOT."../config.php");
		//var_dump($this->inc);
        $this->inc = array(
            'appid' => Configs::getByKey('oauth_qq_key'),
            'appkey' => Configs::getByKey('oauth_qq_secret'),
            'callback' => Configs::getByKey('oauth_qq_callback'),
        );

        if(empty($this->inc)){
            $this->error->showError("20001");
        }

        if(empty($_SESSION['qq_data'])){
            self::$data = array();
        }
        else{
            self::$data = & $_SESSION['qq_data'];
        }
    }

    public function write($name,$value){
        self::$data[$name] = $value;
    }

    public function read($name){
        if(empty(self::$data[$name])){
            return null;
        }else{
            return self::$data[$name];
        }
    }

    public function readInc($name){
        if(empty($this->inc[$name])){
            return null;
        }else{
            return $this->inc[$name];
        }
    }

    public function delete($name){
        unset(self::$data[$name]);
    }

    function __destruct(){
        $_SESSION['qq_data'] = self::$data;
    }
}
