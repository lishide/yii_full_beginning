<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2017012005273124",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIICXAIBAAKBgQC2LYP/wFCQtSSp+gjM8hbgMNgaFHArHSnKzzDYI2Ros7akVh39K61DXMAM5iVRSmakSxkJ9riXl3oC61t0ynKJN+jjww3GXOY2ypW5Pof/g/p5Vxi54L0s8TbO73bqQIUuIkH/D6jv3D2tsn3MzgfuOWD6gptzA4koMAZWRUmsxQIDAQABAoGAQR5v6oFRywgU/PU1JBz89FJBTME1fxPDlo0NBpVQFOT4SjHGMnLkUgnbVEEH4dDYc62sW5VnUjiBEn4SiOK8oZkEVkOCNJuTMj4mWf4NoBgxkkebKqRfrZobEPGT2kpOoec4r1uloQ8GjigVviwP2d6lXPRl+XdChu4URc+oohkCQQDqZMX309JUAqqavWk5p4623FfrojZN37+1puTa8GtmdJBjA5Xv75TWQQ1vUN6sG41K5z/Hbz0kU1hXJHGpgDmfAkEAxviNLIvYvSjmHB/S9ErKZCWOcmAhceyjhoVpeNuavQesve0OWiJuiJ/r68vMkFFrgSNWN734T9AMS1+F12fHGwJAElnfcf7AidlHmCPaOCxZLRHlREqH6+LntIYjhsyp6/SWVVozg/yC759aOOvg8yKZFlMymB+qbsyjrvboezfRywJBAKIAXXGXq7DYTAM6JhihjIhdy810V8baVYqBtY6hvyuJxCwfhz/8KOM00nH6TWRz5oQOsXRRSIwzaM81x1PLO2ECQAyOBCvdK2M32drohHS+DSR0BswniHaC1BvkYtN5iIZdomCA3YGcbZscwiX8XbycyMhI1Xbi0Zr67bn5bcgz8zw=",
		
		//异步通知地址
		'notify_url' => "http://fulong.moreovernet.com/web/pay/wapnotify",
		
		//同步跳转
		'return_url' => "http://fulong.moreovernet.com/web/pay/wapreturn",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB",
		
	
);