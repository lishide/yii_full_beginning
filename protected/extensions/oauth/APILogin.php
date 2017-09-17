<?php

session_start();
define('API_DIR', dirname (__FILE__));

abstract class APILogin
{
	protected $apiName = '';
	protected $apiConfig = array('appId' => '', 'appKey' => '', 'appCallbackUrl' => '');

	static $createdObjs = array();

	static function create ($apiName)
	{
		if (! isset(self::$createdObjs[$apiName]))
		{
			$apiClassName = ucfirst($apiName)."LoginApi";
			$apiPath      = API_DIR . "/$apiName/$apiClassName.class.php";

			if (file_exists ($apiPath)) {

				require_once $apiPath;
				
				self::$createdObjs[$apiName] = new $apiClassName();
				self::$createdObjs[$apiName]->apiName = $apiName;
				self::$createdObjs[$apiName]->configure();
				self::$createdObjs[$apiName]->init();
				
				return self::$createdObjs[$apiName];
			} 
		}
		
		return self::$createdObjs[$apiName];
	}
	
	protected function configure()
	{
		$apiName = strtolower($this->apiName);
		$this->apiConfig = array(
			'appId' => Configs::getByKey('oauth_'.$apiName.'_key'),
			'appKey' => Configs::getByKey('oauth_'.$apiName.'_secret'),
			'appCallbackUrl' => Configs::getByKey('oauth_'.$apiName.'_callback')
		);
	}
	
	public function doLogout()
	{
		if ( isset( $_SESSION[$this->apiName . '_data'] ) ){
			$_SESSION[$this->apiName . '_data'] = array();
		}
	}
	
	abstract protected function init();

	abstract function getLoginUrl ();

	abstract function doCallback ();

	abstract function getUserInfo ();

	abstract function sendMsg ($message, $url);
}
