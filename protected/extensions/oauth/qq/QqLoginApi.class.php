<?php

require_once "class/qqConnectAPI.php";

class QqLoginApi extends APILogin
{
	protected $qc;

	protected function init()
	{
		if (! empty($_SESSION['qq_data']['openid'])){
			$this->qc = new QC($_SESSION['qq_data']['access_token'], $_SESSION['qq_data']['openid']);
		}
		else {
			$this->qc = new QC();
		}
	}
	
	function getLoginUrl ()
	{
		return $this->qc->qq_login ();
	}

	function doCallback ()
	{
		// 存储 token 和 openid
		if (! isset($_SESSION['qq_data']['access_token'] )){
			$this->qc->qq_callback ();
			$this->qc->get_openid ();
		}
	}

	function getUserInfo ()
	{
		self::init();
		
		$userInfo = $this->qc->get_user_info ();
		if ($userInfo['ret'] == 0){
			return array(
				'open_id' => $_SESSION['qq_data']['openid'],
				'access_token' => $_SESSION['qq_data']['access_token'],

				'nickname' => $userInfo['nickname'],
				'gender' => $userInfo['gender'] == '男',
				'head_icon' => $userInfo['figureurl_qq_2'],

				'extra' => $userInfo,
			);
		}
		return null;
	}

	function sendMsg($message, $url)
	{
		self::init();
		
		$share = array(
			'title' => '来自@社区送 的分享',
			'comment' => $message,
			'site' => __BASE_URL__,
			'url' => $url,
			'fromurl' => $url,
		);
		$this->qc->add_share($share);
	}
}