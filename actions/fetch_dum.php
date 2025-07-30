<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
$items = new TheFunctions;
$store_branch = $_SESSION['appstore_branch'];
if(!isset($_SESSION['session_date'])) { $trans_date = $date->get_date(); } else { $trans_date = $_SESSION['session_date']; }
if(!isset($_SESSION['session_shift'])) { $store_shift = ''; } else { $store_shift = $_SESSION['session_shift']; }

	$query ="SELECT * FROM store_rm_summary_data WHERE branch='$store_branch' AND shift='$store_shift' AND report_date='$trans_date'";
	$result = mysqli_query($db, $query);  
	$count = $result->num_rows;
	if($result->num_rows > 0)
	{
		while($FGTSDATA = mysqli_fetch_array($result))  
		{				
			$rowid = $FGTSDATA['id'];
			$shift = $FGTSDATA['shift'];
			$store_branch = $FGTSDATA['branch'];
			$report_date = $FGTSDATA['report_date'];
			$beginning = $FGTSDATA['beginning'];
			$item_id = $FGTSDATA['item_id'];
			$item_name = $FGTSDATA['item_name'];
			$delivery = $FGTSDATA['stock_in'];
			$price_kg = $FGTSDATA['price_kg'];
			$transfer_in = $FGTSDATA['transfer_in'];
			$transfer_out = $FGTSDATA['transfer_out'];
			$actual_count = $FGTSDATA['actual_count'];
			
			// $price_kg = $items->getItemPrice($item_id,$db);
			$dum_id = $items->GetSummary($rowid,$store_branch,$trans_date,$store_shift,$db);
			if($dum_id != $rowid)
			{
				$value = "'$rowid','$item_id','$store_branch','$report_date','$shift','$item_name','$beginning','$delivery','$transfer_in','$transfer_out','$actual_count','$price_kg'";
				echo $items->SaveToDUM($value,$db);
			}
		}
	} else {
		echo "No Records";
	}
?>
