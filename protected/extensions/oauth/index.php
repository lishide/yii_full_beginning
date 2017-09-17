<?php
	
	require_once 'APILogin.class.php';
	
	$type = isset($_GET['type']) ? $_GET['type'] : 'qq';
	
	APILogin::create($type)->doLogout();
	$url = APILogin::create($type)->getLoginUrl();
	
?>

<a href="<?php echo $url ?>">login</a>
