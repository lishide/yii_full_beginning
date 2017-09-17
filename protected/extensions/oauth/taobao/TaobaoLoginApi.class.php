<?php

class TaobaoLoginApi extends APILogin
{
	function init()
	{
		
	}
	
	function getLoginUrl ()
	{
		return 'https://oauth.taobao.com/authorize?response_type=code&client_id=' . $this->apiConfig['appId'] . '&redirect_uri='.$this->apiConfig['appCallbackUrl'].'&state=1212&view=web';
	}

	function doCallback ()
	{
		$code = $_REQUEST['code'];   //通过访问https://oauth.taobao.com/authorize获取code
		$grant_type = 'authorization_code';

		//请求参数
		$postFields = array(
			'grant_type'     => $grant_type,
			'client_id'     => $this->apiConfig['appId'],
			'client_secret' => $this->apiConfig['appKey'],
			'code'          => $code,
			'redirect_uri'  => $this->apiConfig['appCallbackUrl'],
		);

		$url = 'https://oauth.taobao.com/token';

		if (empty($_SESSION['taobao_data'])) {
			$token = json_decode(curl($url,$postFields));

			if ($token){
				$token = (array)$token;

				$_SESSION['taobao_data'] = array(
					'open_id' => $token['taobao_user_id'],
					'access_token' => $token['access_token'],

					'gender' => null,
					'nickname' => $token['taobao_user_nick'],

					'extra' => $token,
				);
			}
		}
	}

	function getUserInfo()
	{
		return $_SESSION['taobao_data'];
	}

	function sendMsg ($message, $url)
	{

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