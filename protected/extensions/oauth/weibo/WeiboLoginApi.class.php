<?php

include_once( 'class/saetv2.ex.class.php' );

class WeiboLoginApi extends APILogin
{
	protected $oAuth;
	protected $client;

	protected function init()
	{
		$this->oAuth = new SaeTOAuthV2( $this->apiConfig['appId'] , $this->apiConfig['appKey'] );

		if (! empty($_SESSION['weibo_data']) ){
			$this->client = new SaeTClientV2( $this->apiConfig['appId'] , $this->apiConfig['appKey'], $_SESSION['weibo_data']['access_token'] );
		}
	}
	
	function getLoginUrl ()
	{
		return $this->oAuth->getAuthorizeURL( $this->apiConfig['appCallbackUrl'] );
	}
	
	function doLogout()
	{
		parent::doLogout();
		
		// 清除cookie
		setcookie( 'weibojs_' . $this->oAuth->client_id, '', time() - 86400 );
	}

	function doCallback ()
	{
		$token = '';
		
		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = $this->apiConfig['appCallbackUrl'];
			try {
				$token = $this->oAuth->getAccessToken( 'code', $keys ) ;
				
				if ($token) {
					$_SESSION['weibo_data'] = $token;
					setcookie( 'weibojs_' . $this->oAuth->client_id, http_build_query($token) );
				}
			} catch (OAuthException $e) {
				echo $e->getMessage();
				die();
			}
		}

		return $token;
	}

	function getUserInfo()
	{
		// 这里需要重新初始化
		$this->init();
		
		if (! $this->client)
		{
			return null;
		}
		
		$uid_get = $this->client->get_uid();
		$userInfo = $this->client->show_user_by_id($uid_get['uid']);

		if ($userInfo){
			return array(
				'open_id' => $userInfo['id'],
				'access_token' => $_SESSION['weibo_data']['access_token'],

				'nickname' => $userInfo['screen_name'],
				'gender' => $userInfo['gender'] == 'm',
				'head_icon' => $userInfo['profile_image_url'],

				'extra' => $userInfo,
			);
		}
		
		return null;
	}

	function sendMsg($message, $url)
	{
		// 这里需要重新初始化
		$this->init();
		
		$this->client->update(
			'来自@社区送 的分享: ' . $message . ' 链接：' . $url
		);
	}
}


//POST请求函数
function curl ($url, $postFields = null)
{
	$ch = curl_init ();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_FAILONERROR, false);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);

	if (is_array ($postFields) && 0 < count ($postFields)) {
		$postBodyString = "";
		foreach ($postFields as $k => $v) {
			$postBodyString .= "$k=" . urlencode ($v) . "&";
		}
		unset($k, $v);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, substr ($postBodyString, 0, -1));
	}
	$reponse = curl_exec ($ch);
	if (curl_errno ($ch)) {
		throw new Exception(curl_error ($ch), 0);
	}
	else {
		$httpStatusCode = curl_getinfo ($ch, CURLINFO_HTTP_CODE);
		if (200 !== $httpStatusCode) {
			throw new Exception($reponse, $httpStatusCode);
		}
	}
	curl_close ($ch);

	return $reponse;
}