<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
$store_branch = $_SESSION['appstore_branch'];
$report_date = $_POST['reportdate'];
$queryDataDelete = "DELETE FROM store_dum_data WHERE branch='$store_branch' AND report_date='$report_date'";
if ($db->query($queryDataDelete) === TRUE) { } else { echo $db->error; }	
