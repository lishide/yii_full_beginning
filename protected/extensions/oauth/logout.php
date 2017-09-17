<?php
	
	require_once 'APILogin.class.php';
	
	$type = isset($_GET['type']) ? $_GET['type'] : 'qq';

	APILogin::create($type)->doLogout();
		
	header('location: index.php');