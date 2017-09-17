<?php
	
	require_once 'APILogin.class.php';
	
	$type = isset($_GET['type']) ? $_GET['type'] : 'qq';

	APILogin::create($type)->doCallback();
    $userInfo = APILogin::create($type)->getUserInfo();
	echo json_encode($userInfo);
	exit;
?>

<?php if ($userInfo){ echo $userInfo['nickname']; } else { 
	header('location: index.php');
} ?>
<a href="logout.php?type=<?php echo $type ?>">logout</a>