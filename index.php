<?php
include 'init.php';
if(!isset($_SESSION['appstore_user']))
{
	include 'includes/user_login.php';
	exit();
} else {	
	include 'main.php';
}
