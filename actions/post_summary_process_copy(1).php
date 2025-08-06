<?PHP
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
include '../db_config_main.php';
if($functions->OnlineStatus(is_connected,ip_hosts) == 1)
{
	$conn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);
}
$functions = new TheFunctions;
if(isset($_POST['mode'])) {
	$mode = $_POST['mode'];
} else {
	print_r('<script>app_alert("Warning"," The Mode you are trying to pass does not exist","warning","Ok","","true");</script>');exit();
}
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$time_stamp = date("Y-m-d H:i:s");
/* ###################################### FGTS POST SUMMARY ###################################### */
if($mode == 'postsummaries')
{
	if($_POST['file_name'] == 'summary')
	{
		$table = 'store_summary_data';
	}
	if($_POST['file_name'] == 'rm_summary')
	{
		$table = 'store_rm_summary_data';
	}

	$queryDataUpdates = "UPDATE $table SET posted='Posted',`status`='Closed'  WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
	if($db->query($queryDataUpdates) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				swal("Success","Summary Successfuly Posted","success");
			</script>
		';	
		print_r($cmd);
	} else { echo "Error::: ".$db->error;}
}
/* ###################################### RAWMATS BAD ORDER SUMMARY ###################################### */
if($mode == 'rm_pcount')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$category = $ROW['category'];
		$shiftS = $ROW['shift'];
		$time_covered = $ROW['time_covered'];
		$actual_count = $ROW['actual_count'];

		$to_shift = $ROW['to_shift'];
		$to_shift_date = $ROW['to_shift_date'];
		$unitprice = $functions->getItemPrice($item_id,$db);
				
		$kwiri = "branch='$branch'";
		$shifting = $functions->GetSession('shifting');
		$beginning = $functions->GetBeginning($kwiri,$item_id,$shift,$shifting,$to_shift,$to_shift_date,$transdate,$tbl,$db);
		
		$QUERY ="SELECT * FROM store_rm_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$item_name'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_rm_summary_data SET beginning='$beginning',actual_count='$actual_count',price_kg='$unitprice' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$item_name'";
			if($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo "UPDATER ERROR::: ". $db->error; exit(); }
			
		} else {				
			$queryDataInsert = "INSERT INTO store_rm_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,actual_count,beginning,price_kg,date_created)			
			VALUES ('$item_id','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$beginning','$unitprice','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo "INSERT ERROR::: ".$db->error; exit(); }					
		}
		if($rowcount == $x)
		{
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		}
	}
}
/* ###################################### RAWMATS BAD ORDER SUMMARY ###################################### */
if($mode == 'rm_badorder')
{
	$tbl = "store_".$mode."_data";
	/*  ---------------------------------------------- */
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$data=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$item_id = $ROW['item_id'];
		$itemname = $ROW['item_name'];
		$actual_count = $ROW['actual_count'];
		$time_covered = $ROW['time_covered'];

		$QUERY ="SELECT * FROM store_rm_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_rm_summary_data SET bo='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)
			{
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			}
			else { echo $db->error;}
		} else {
		
			$queryDataInsert = "INSERT INTO store_rm_summary_data (item_id,branch,report_date,shift,time_covered,item_name,bo)
			VALUES ('$item_id','$branch','$transdate','$shift','$time_covered','$itemname','$actual_count')";
			if ($db->query($queryDataInsert) === TRUE) {
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			} 
			else {  echo $db->error; }
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","Rawmats Summary Successfuly Report Posted","success","Ok","","");
					$("#" + sessionStorage.navcount).click();</script>
				</script>
			');
		}			
	} 			
	/*  ---------------------------------------------- */
}
/* ###################################### RAWMATS RECIEVING SUMMARY ###################################### */
if($mode == 'rm_receiving')
{
	$tbl = "store_".$mode."_data";
	/*  ---------------------------------------------- */
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$item_id = $ROW['item_id'];
		$category = $ROW['category'];
		$item_name = $ROW['item_name'];
		$actual_count = $ROW['qty'];
		$time_covered = $ROW['time_covered'];

		$QUERY ="SELECT * FROM store_rm_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_rm_summary_data SET stock_in='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			}
			else { echo $db->error;}
		} else {
			$queryDataInsert = "INSERT INTO store_rm_summary_data (branch,report_date,shift,time_covered,category,item_id,item_name,stock_in,date_created)
			VALUES ('$branch','$transdate','$shift','$time_covered','$category','$item_id','$item_name','$actual_count','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) {
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			} 
			else {  echo $db->error; }
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","Rawmats Summary Successfuly Report Posted","success","Ok","","");
					$("#" + sessionStorage.navcount).click();</script>
				</script>
			');
		}
	} 
	/*  ---------------------------------------------- */
}
/* ###################################### RAWMATS TRANSFER SUMMARY ###################################### */
if($mode == 'rm_transfer')
{
	$table = "store_".$mode."_data";
	/*  ---------------------------------------------- */
	$query ="SELECT *, SUM(weight) as qty  FROM $table WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_id";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;		
		$rowid = $ROW['id'];
		$branch = $ROW['branch'];
		$report_date = $ROW['report_date'];
		$shift = $ROW['shift'];
		$time_covered = $ROW['time_covered'];
		$person = $ROW['employee_name'];
		$encoder = $ROW['supervisor'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$weight = $ROW['weight'];
		$units = $ROW['units'];
		$transfer_to = $ROW['transfer_to'];
		$date_created = $ROW['date_created'];
		$date_updated = $ROW['date_updated'];
		$updated_by = $ROW['updated_by'];
		$posted = $ROW['posted'];
		$status = $ROW['status'];

		$QUERY ="SELECT * FROM store_rm_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_rm_summary_data SET transfer_out='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$item_name'";
			if ($db->query($queryDataUpdates) === TRUE)	{
				if($functions->OnlineStatus(is_connected,ip_hosts) == 1)
				{
					$functions->iTransferOutRM($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn);
				}				
				$functions->setUpdatePosting($table,$branch,$transdate,$shift,$rowid,$db);
			}
			else { echo $db->error;}
		} else {
			$queryDataInsert = "INSERT INTO store_rm_summary_data (branch,report_date,shift,time_covered,item_name,transfer_out,date_created)
			VALUES ('$branch','$transdate','$shift','$time_covered','$item_name','$weight','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) {
				if($functions->OnlineStatus(is_connected,ip_hosts) == 1)
				{
					$functions->iTransferOutRM($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn);
				}				
				$functions->setUpdatePosting($table,$branch,$transdate,$shift,$rowid,$db);
			} 
			else {  echo $db->error; }
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","Rawmats Transfer Out Summary Successfuly Report Posted","success","Ok","","");
				//	$("#" + sessionStorage.navcount).click();
				</script>
			'); 
		}
	} 
	/*  ---------------------------------------------- */
}
/* ###################################### RAWMATS TRANSFER IN SUMMARY ###################################### */
if($mode == 'rm_transferin')
{
	echo $mode;
	$tbl = "store_rm_transfer_data";
	$row_id = $_POST['rowid'];
	$query2 ="SELECT *, SUM(weight) as qty  FROM $tbl WHERE id='$row_id'";  
	$result2 = mysqli_query($db, $query2);  
	$rowcount=mysqli_num_rows($result2);
	$x=0;$datain=array();$dataup=array();$save = 0;	
	while($ROW = mysqli_fetch_array($result2)) 
	{
		$x++;
		$rowid =  $ROW['id'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$actual_count = $ROW['qty'];
		$time_covered = $ROW['time_covered'];
		if($item_id == '')	{ echo "No Id"; exit(); }
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_rm_summary_data SET transfer_in='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
		} else {
			$pid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_rm_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,transfer_in,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		
		if($rowcount == $x) { 
			print_r('<script>app_alert("System Message","Rawmats Transfer In Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); 
		}
	}
}
/* ###################################### DISCOUNT SUMMARY ###################################### */
if($mode == 'discount')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT * FROM $tbl WHERE branch='$branch' AND report_date='$transdate'";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		echo $rowid;
		$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);

		if($rowcount == $x)
		{
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		}
	}
}
/* ###################################### PHYSICAL COUNT SUMMARY ###################################### */
if($mode == 'pcount')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$category = $ROW['category'];
		$shiftS = $ROW['shift'];
		$time_covered = $ROW['time_covered'];
		$actual_count = $ROW['actual_count'];
		$to_shift = $ROW['to_shift'];
		$to_shift_date = $ROW['to_shift_date'];
		$unitprice = $functions->getItemPrice($item_id,$db);
		$kwiri = "branch='$branch'";
		$shifting = $functions->GetSession('shifting');
		$beginning = $functions->GetBeginning($kwiri,$item_id,$shift,$shifting,$to_shift,$to_shift_date,$transdate,$tbl,$db);
		
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$item_name'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET beginning='$beginning',actual_count='$actual_count',unit_price='$unitprice' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$item_name'";
			if($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo "A ". $db->error;}
			
		} else {				
			$pid = $functions->getPID($item_id,$db);
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,actual_count,beginning,unit_price)			
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$beginning','$unitprice')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo "B ".$db->error; }					
		}
		if($rowcount == $x)
		{
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		}
	}
}
/* ###################################### FROZEN DOUGH SUMMARY ###################################### */
if($mode == 'frozendough')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT * FROM $tbl WHERE shift='$shift' AND branch='$branch' AND report_date='$transdate'";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$branch = $ROW['branch'];
		$transdate = $ROW['report_date'];
		$shift = $ROW['shift'];		
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$unit_price = $ROW['unit_price'];
		$time_covered = $ROW['time_covered'];
		$standard_yield = $ROW['standard_yield'];
		$kilo_used = $ROW['kilo_used'];
		$actual_yield = $ROW['actual_yield'];		
		$inputtime = $ROW['inputtime'];
		$supervisor = $ROW['supervisor'];

		echo $item_name;

		$quantity = $functions->GetTotalValue($tbl,'actual_yield',$item_id,$branch,$transdate,$shift,$db);		
		$QUERYS ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$item_name'";  
		$RESULTS = mysqli_query($db, $QUERYS);  
		if($RESULTS->num_rows  > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET frozendough='$quantity' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
			
		} else {
			$pid = $functions->getPID($item_id,$db);
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,supervisor,category,item_name,frozendough)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$supervisor','$category','$item_name','$quantity')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }			
		}
		
		if($rowcount == $x)
		{
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		}
	}
}
/* ###################################### CASH COUNT SUMMARY ###################################### */
if($mode == 'cashcount')
{
	$tbl = "store_".$mode."_data";
	/*  ---------------------------------------------- */
	$query ="SELECT * FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate'";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$queryInvUpdates = "UPDATE $tbl SET posted='Posted',status='Closed' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		if ($db->query($queryInvUpdates) === TRUE) { } else { echo $db->error; }
		if($rowcount == $x)
		{
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); 
		}
	} 
}
/* ###################################### RECEIVING SUMMARY ###################################### */
if($mode == 'receiving')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$category = $ROW['category'];
		$item_name = $ROW['item_name'];
		$item_id = $ROW['item_id'];
		$actual_count = $ROW['quantity'];
		$time_covered = $ROW['time_covered'];
		echo $item_name;
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET stock_in='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
		} else {			
			$pid = $functions->getPID($item_id,$db);
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,stock_in,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }
	} 
}
/* ###################################### REQUEST SUMMARY ###################################### */
if($mode == 'request')
{
	$tbl = "store_".$mode."_data";
	$query2 ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result2 = mysqli_query($db, $query2);  
	$rowcount=mysqli_num_rows($result2);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result2)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$category = $ROW['category'];		/* THIS PART OF THE CODE IS RESERVED AND NOT USED FOR THE MEANTIME */
		$item_name = $ROW['item_name'];
		$item_id = $ROW['item_id'];
		$actual_count = $ROW['qty'];
		$time_covered = $ROW['time_covered'];
		$queryInvUpdates = "UPDATE $tbl SET posted='Posted' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
		if ($db->query($queryInvUpdates) === TRUE) {
		} else { echo $db->error; }
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }
	} 
	/*  ---------------------------------------------- */
}
if($mode == 'complimentary')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$category = $ROW['category'];
		$quantity = $ROW['qty'];
		$shift = $ROW['shift'];
		$time_covered = $ROW['time_covered'];
		
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET complimentary='$quantity' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
		} else {
			$mid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,complimentary)
			VALUES ('$item_id','$branch','$transdate','$shift','$time_covered','$category','$item_name','$quantity')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }
	} 
}
/* ###################################### DAMAGE SUMMARY ###################################### */
if($mode == 'damage')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$category = $ROW['category'];
		$quantity = $ROW['qty'];
		$shift = $ROW['shift'];
		$time_covered = $ROW['time_covered'];
		
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET damaged='$quantity' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
		} else {
			$pid = $functions->getPID($item_id,$db);		
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,damaged)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$quantity')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }
	} 
}
/* ###################################### BAD ORDER SUMMARY ###################################### */
if($mode == 'badorder')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$data=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$category = $ROW['category'];
		$quantity = $ROW['qty'];
		$shift = $ROW['shift'];
		$time_covered = $ROW['time_covered'];			

		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET bo='$quantity' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
		} else {		
			$pid = $functions->getPID($item_id,$db);		
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,bo)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$quantity')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }			
	}
}
/* ###################################### SNACKS SUMMARY ###################################### */
if($mode == 'snacks')
{
	$tbl = "store_".$mode."_data";	
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$category = $ROW['category'];
		$actual_count = $ROW['qty'];
		$shift = $ROW['shift'];
		$time_covered = $ROW['time_covered'];

		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET snacks='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);	 } else { echo $db->error; }
		} else {
		
			$pid = $functions->getPID($item_id,$db);				
			$queryDataInsert ="INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,snacks)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }
		
	} 
}
/* ###################################### CHARGES SUMMARY ###################################### */
if($mode == 'charges')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result = mysqli_query($db, $query); 	 
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$pid = '';
		$row_id = $ROW['id'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$actual_count = $ROW['qty'];
		$shift = $ROW['shift'];
		$time_covered = $ROW['time_covered'];
		$slip_no = $ROW['slip_no'];
		$supervisor = $ROW['supervisor'];

		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET charges='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$row_id,$db); } else { echo $db->error;}
		} else {			
			$pid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,supervisor,category,item_name,charges)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$supervisor','$category','$item_name','$actual_count')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}								
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }
	} 
}
if($mode == 'transferin')
{
	$tbl = "store_transfer_data";
	$row_id = $_POST['rowid'];
	$query2 ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE id='$row_id'";  
	$result2 = mysqli_query($db, $query2);  
	$rowcount=mysqli_num_rows($result2);
	$x=0;$datain=array();$dataup=array();$save = 0;	
	while($ROW = mysqli_fetch_array($result2)) 
	{
		$x++;
		$rowid =  $ROW['id'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$actual_count = $ROW['qty'];
		$time_covered = $ROW['time_covered'];
		if($item_id == '')	{ echo "No Id"; exit(); }
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET t_in='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
		} else {
			$pid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,t_in,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		
		if($rowcount == $x) { 
			print_r('<script>app_alert("System Message","Transfer In Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); 
		}
	}
}
if($mode == 'transfer')
{
	$tbl = "store_".$mode."_data";
	$query2 ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' GROUP BY item_name";  
	$result2 = mysqli_query($db, $query2);  
	$rowcount=mysqli_num_rows($result2);
	$x=0;
	while($ROW = mysqli_fetch_array($result2)) 
	{
		$x++;
		$rowid =  $ROW['id'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$actual_count = $ROW['qty'];
		$time_covered = $ROW['time_covered'];
		$to_branch = $ROW['transfer_to'];
		$date = $ROW['report_date'];
		$person = $ROW['employee_name'];
		$encoder = $ROW['supervisor'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$quantity = $ROW['quantity'];
		$unit_price = $ROW['unit_price'];
		$amount = $ROW['amount'];
		$to_branch = $ROW['transfer_to'];
		$amount = $ROW['amount'];		
		
		if($item_id == '')	{ echo "No Id"; exit(); }
			
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$item_name'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET t_out='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$item_name'";
			if ($db->query($queryDataUpdates) === TRUE) { 
				if($functions->OnlineStatus(is_connected,ip_hosts) == 1)
				{
					$functions->iTransferOut($tbl,'FGTS',$rowid,$branch,$date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$quantity,$unit_price,$amount,$to_branch,$time_stamp,$conn);				
				}
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); 
			} else { echo $db->error; }
		} 
		else
		{
			$pid = $functions->getPID($item_id,$db);
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,t_in,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { 
				if($functions->OnlineStatus(is_connected,ip_hosts) == 1)
				{
					iTransferOut($tbl,'FGTS',$rowid,$branch,$date,$shift,$time_covered,$person,$encoder,$category,$item_id,$itemname,$quantity,$unit_price,$amount,$to_branch,$time_stamp,$conn);
				}
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } 
			else {  echo $db->error; }
		}
				
		if($rowcount == $x) { 
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); 
		}
	}
}
if($mode == 'fgts')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT * FROM $tbl WHERE shift='$shift' AND branch='$branch' AND report_date='$transdate'";  
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$unit_price = $ROW['unit_price'];
		$time_covered = $ROW['time_covered'];
		$standard_yield = $ROW['standard_yield'];
		$kilo_used = $ROW['kilo_used'];
		$actual_yield = $ROW['actual_yield'];		
		$inputtime = $ROW['inputtime'];
		$supervisor = $ROW['supervisor'];
		$quantity=$actual_yield;

		$QUERYS ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$item_name'";  
		$RESULTS = mysqli_query($db, $QUERYS);  
		if($RESULTS->num_rows  > 0)
		{
			$sumitemactualcount = $functions->getSumfgts($branch,$transdate,$shift,$item_name,$db);
			$queryDataUpdates = "UPDATE store_summary_data SET category='$category',stock_in='$sumitemactualcount',unit_price='$unit_price',actual_yield='$actual_yield' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
			
		} else {
			$pid = $functions->getPID($item_id,$db);
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,supervisor,inputtime,category,item_name,stock_in,standard_yield,kilo_used,actual_yield,unit_price)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$supervisor','$inputtime','$category','$item_name','$quantity','$standard_yield','$kilo_used','$actual_yield','$unit_price')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }			
		}
		
		if($rowcount == $x)
		{
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		}
	} 
}
