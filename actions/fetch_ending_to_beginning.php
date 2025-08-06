<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;

$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$shifting = $_SESSION['appstore_shifting'];

$currentDate = new DateTime($transdate);
$currentDate->modify('-1 day');

if($shifting == 2)
{
	if($shift == 'FIRST SHIFT')
	{
		$back_shift = 'SECOND SHIFT';
		$back_date = $currentDate->format('Y-m-d');
		$time_covered = '04:00 AM - 01:00 PM';
	}
	else if($shift == 'SECOND SHIFT')
	{
		$back_shift = 'FIRST SHIFT';
		$back_date = $transdate;
		$time_covered = '1:00 PM - 10:00 PM';
		
	}
}
else if($shifting == 3)
{
	if($shift == 'FIRST SHIFT')
	{
		$back_shift = 'THIRD SHIFT';
		$back_date = $currentDate->format('Y-m-d');
		$time_covered = '04:00 AM - 01:00 PM';
	}
	else if($shift == 'SECOND SHIFT')
	{
		$back_shift = 'FIRST SHIFT';
		$back_date = $transdate;
		$time_covered = '1:00 PM - 10:00 PM';
	}
	else if($shift == 'THIRD SHIFT')
	{
		$back_shift = 'SECOND SHIFT';
		$back_date = $transdate;
		$time_covered = '10:00 PM - 07:00 AM';
	}
}

$query = "SELECT * FROM store_pcount_data WHERE shift='$back_shift' AND report_date='$back_date' AND status='Closed'";
$result = $db->query($query);
if ($result->num_rows > 0) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $item_id = $row['item_id'];
        $item_name = $row['item_name'];
        $branch = $row['branch'];
        $actual_count = $row['actual_count'];
		
		$update = "beginning='$actual_count'";
		
        $CheckQuery = "SELECT * FROM store_summary_data WHERE item_id='$item_id' AND report_date='$transdate'";
        $CheckResult = $db->query($CheckQuery);
        if ($CheckResult->num_rows == 0) {
            $data[] = "('$item_id', '$item_name', '$branch', '$actual_count','$transdate','$shift','$time_covered')";
        } 
        else
        {
            $queryDataUpdate = "UPDATE store_summary_data SET $update WHERE item_id='$item_id'";
			if ($db->query($queryDataUpdate) === TRUE)
			{
			} else {
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}

        }
    }
    if (!empty($data)) {
        $queryInsert = "INSERT INTO store_summary_data (item_id, item_name, branch, beginning,report_date,shift,time_covered) VALUES " . implode(', ', $data);

        if ($db->query($queryInsert) === TRUE) {
        } else {
            echo $db->error;
        }
    } else {
    	// MESSAGES
    }
} else {
   	// MESSAGES
}

$db->close();