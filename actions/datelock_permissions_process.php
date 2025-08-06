<?php
require '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
$functions = new TheFunctions;
include '../db_config_main.php';
$conn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);

$branch = $functions->AppBranch();


$sql = "SELECT branch, report_date, lock_by, unlock_by FROM store_datelock_checker WHERE branch='$branch' AND branch_execute=0 AND office_execute=1 AND status=0";
$result = $conn->query($sql);
if ($result->num_rows > 0){
	while($row = $result->fetch_assoc())
	{
		$branch = $row["branch"];
		$reportDate = $row["report_date"];
		$lockBy = $row["lock_by"];
		$unlockBy = $row["unlock_by"];
		$functions->dateLockCheckerInsert($branch, $reportDate, $lockBy, $unlockBy, $db);
		$functions->dateLockCheckerUpdate($branch, $reportDate, $lockBy, $unlockBy, $conn);
	}
}


$sql2 = "SELECT branch, report_date, lock_by, unlock_by FROM store_datelock_checker_rm WHERE branch='$branch' AND branch_execute=0 AND office_execute=1 AND status=0";
$result = $conn->query($sql2);
if ($result->num_rows > 0){
	while($row = $result->fetch_assoc())
	{
		$branch = $row["branch"];
		$reportDate = $row["report_date"];
		$lockBy = $row["lock_by"];
		$unlockBy = $row["unlock_by"];
		$functions->dateLockCheckerInsertRm($branch, $reportDate, $lockBy, $unlockBy, $db);
		$functions->dateLockCheckerUpdateRm($branch, $reportDate, $lockBy, $unlockBy, $conn);
	}
}



?>
		
