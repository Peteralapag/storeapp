<?PHP
include '../init.php';
if(!isset($_SESSION['appstore_userlevel']) || !$_SESSION['appstore_userlevel']) {	
	session_destroy();
	header("Location: ../log_awt.php");
}
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
include '../db_config_main.php';
//if($functions->OnlineStatus(is_connected,ip_hosts) == 1)
if($_SESSION["OFFLINE_MODE"] == 0)
{
	$conn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);
}
include '../class/functions_forms.class.php';
$functions = new TheFunctions;
$FunctionForms = new FunctionForms;
if(isset($_POST['mode'])) {
	$mode = $_POST['mode'];
} else {
	print_r('<script>app_alert("Warning"," The Mode you are trying to pass does not exist","warning","Ok","","true");</script>');exit();
}
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$time_stamp = date("Y-m-d H:i:s");

if($mode == 'rm_inventory_record')
{

	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);
	$itemListTable = $functions->checkItemListifExistTable($db);

	if($itemListTable != 1){
		print_r('<script>app_alert("System Message","We are unable to post if store_items table not exist","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}
	
/*		
	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}
*/

	include '../class/rm_inventory_record_poster.class.php';
	$inventoryRecords = new inventoryRecords;
	$postThisModules = ['rm_receiving','rm_transferin','rm_transferout','rm_badorder','rm_pcount','rm_summary'];
	foreach($postThisModules as $module){
		$inventoryRecords->$module($branch,$transdate,$shift,$db);
	}

}

if($mode == 'inventory_record')
{
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);
	$itemListTable = $functions->checkItemListifExistTable($db);

	if($itemListTable != 1){
		print_r('<script>app_alert("System Message","We are unable to post if store_items table not exist","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}
	
	
	$checkTransferInPosted = $functions->checkTransferInPosted($branch,$transdate,$shift,$db);
	if($checkTransferInPosted == 1){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting transfer in","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}
	
	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}

	
	$cashcountCheckingPosted = $functions->checkCashcountPosted($branch,$transdate,$shift,$db);
	if($cashcountCheckingPosted == '0'){ 
		print_r('<script>app_alert("System Message","You need to submit the cash count before proceeding with this action.","warning");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}

	include '../class/inventory_record_poster.class.php';
	$inventoryRecords = new inventoryRecords;
	$postThisModules = ['fgts','receiving','frozendough','transferout','charges','badorder','damage','pcount','summary'];
	foreach($postThisModules as $module){
		$inventoryRecords->$module($branch,$transdate,$shift,$db);
	}
	
}

if($mode == 'pakati')
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
if($mode == 'grab')
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

if($mode == 'foodpanda')
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

if($mode == 'gcash')
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
if($mode == 'boinventory_pcount')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		
		$QUERY ="SELECT * FROM store_boinventory_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_boinventory_summary_data SET beginning='$beginning',actual_count='$actual_count',price_kg='$unitprice' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo "UPDATER ERROR::: ". $db->error; exit(); }
			
		} else {				
			$queryDataInsert = "INSERT INTO store_boinventory_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,actual_count,beginning,price_kg,date_created)			
			VALUES ('$item_id','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$beginning','$unitprice','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo "INSERT ERROR::: ".$db->error; exit(); }					
		}
		if($rowcount == $x)
		{
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		}
	}
}

if($mode == 'boinventory_transfer')
{
	$table = "store_".$mode."_data";
	
	/*  ---------------------------------------------- */
	$query ="SELECT * FROM $table WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!=''";  
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
		$weight = $ROW['weight'] + $functions->GetBoInventoryTransferOutQtySum($branch,$transdate,$shift,$item_id,$db);
		$units = $ROW['units'];
		$transfer_to = $ROW['transfer_to'];
		$date_created = $ROW['date_created'];
		$date_updated = $ROW['date_updated'];
		$updated_by = $ROW['updated_by'];
		$posted = $ROW['posted'];
		$status = $ROW['status'];

		$QUERY ="SELECT * FROM store_boinventory_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_boinventory_summary_data SET transfer_out='$weight' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{
				if($_SESSION["OFFLINE_MODE"] == 0)
				{
					$functions->iTransferOutBoInventory($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn);
				}				
				$functions->setUpdatePosting($table,$branch,$transdate,$shift,$rowid,$db);
			}
			else { echo $db->error;}
		} else {
			$queryDataInsert = "INSERT INTO store_boinventory_summary_data (branch,report_date,shift,time_covered,item_id,item_name,transfer_out,date_created)
			VALUES ('$branch','$transdate','$shift','$time_covered','$item_id','$item_name','$weight','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) {
				if($_SESSION["OFFLINE_MODE"] == 0)
				{
					$functions->iTransferOutBoInventory($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn);
				}				
				$functions->setUpdatePosting($table,$branch,$transdate,$shift,$rowid,$db);
			} 
			else
			{  
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","B.O Inventory Transfer Out Summary Successfuly Report Posted","success","Ok","","");
					$("#" + sessionStorage.navcount).click();
				</script>
			'); 
		}
	} 
	/*  ---------------------------------------------- */
}
if($mode == 'boinventory_transferin') 
{
	$tbl = "store_boinventory_transfer_data";
	$row_id = $_POST['rowid'];
	$query2 ="SELECT * FROM $tbl WHERE id='$row_id' AND item_id!=''";  
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
		$weight = $ROW['weight'];
		$time_covered = $ROW['time_covered'];
		$qty = $ROW['weight'] + $functions->GetBoInventoryTransferInQtySum($branch,$transdate,$shift,$item_id,$db);

		
		if($item_id == '')	{ echo "No Id"; exit(); }
		$QUERY ="SELECT * FROM store_boinventory_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_boinventory_summary_data SET transfer_in='$weight' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
		} else {
			$pid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_boinventory_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,transfer_in,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$weight','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		
		if($rowcount == $x) { 
			print_r('<script>app_alert("System Message","Scrap Inventory Transfer In Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); 
		}
	}
}

if($mode == 'boinventory_receiving')
{
	$tbl = "store_".$mode."_data";
	/*  ---------------------------------------------- */
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		
		$QUERY ="SELECT * FROM store_boinventory_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_boinventory_summary_data SET stock_in='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			}
			else { echo $db->error;}
		} else {
			$queryDataInsert = "INSERT INTO store_boinventory_summary_data (branch,report_date,shift,time_covered,category,item_id,item_name,stock_in,date_created)
			VALUES ('$branch','$transdate','$shift','$time_covered','$category','$item_id','$item_name','$actual_count','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) {
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			} 
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","B.O Inventory Summary Successfuly Report Posted","success","Ok","","");
					$("#" + sessionStorage.navcount).click();</script>
				</script>
			');
		}
	} 
	/*  ---------------------------------------------- */
}

if($mode == 'boinventory_badorder')
{
	$tbl = "store_".$mode."_data";
	/*  ---------------------------------------------- */
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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

		$QUERY ="SELECT * FROM store_boinventory_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_boinventory_summary_data SET bo='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)
			{
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			}
			else 
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		} else {
		
			$queryDataInsert = "INSERT INTO store_boinventory_summary_data (item_id,branch,report_date,shift,time_covered,item_name,bo)
			VALUES ('$item_id','$branch','$transdate','$shift','$time_covered','$itemname','$actual_count')";
			if ($db->query($queryDataInsert) === TRUE) {
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			} 
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
			}
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","B.O Inventory Summary Successfuly Report Posted","success","Ok","","");
					$("#" + sessionStorage.navcount).click();</script>
				</script>
			');
		}			
	} 			
	/*  ---------------------------------------------- */
}
////////////////////////////// SCRAP ////////////////////////////////////////////////
if($mode == 'scrapinventory_pcount')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		
		$QUERY ="SELECT * FROM store_scrapinventory_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_scrapinventory_summary_data SET beginning='$beginning',actual_count='$actual_count',price_kg='$unitprice' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo "UPDATER ERROR::: ". $db->error; exit(); }
			
		} else {				
			$queryDataInsert = "INSERT INTO store_scrapinventory_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,actual_count,beginning,price_kg,date_created)			
			VALUES ('$item_id','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$beginning','$unitprice','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo "INSERT ERROR::: ".$db->error; exit(); }					
		}
		if($rowcount == $x)
		{
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		}
	}
}

if($mode == 'scrapinventory_transfer')
{
	$table = "store_".$mode."_data";
	
	/*  ---------------------------------------------- */
	$query ="SELECT * FROM $table WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!=''";  
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
		$weight = $ROW['weight'] + $functions->GetScrapInventoryTransferOutQtySum($branch,$transdate,$shift,$item_id,$db);
		$units = $ROW['units'];
		$transfer_to = $ROW['transfer_to'];
		$date_created = $ROW['date_created'];
		$date_updated = $ROW['date_updated'];
		$updated_by = $ROW['updated_by'];
		$posted = $ROW['posted'];
		$status = $ROW['status'];

		$QUERY ="SELECT * FROM store_scrapinventory_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_scrapinventory_summary_data SET transfer_out='$weight' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{
				if($_SESSION["OFFLINE_MODE"] == 0)
				{
					$functions->iTransferOutScrapInventory($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn);
				}				
				$functions->setUpdatePosting($table,$branch,$transdate,$shift,$rowid,$db);
			}
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		} else {
			$queryDataInsert = "INSERT INTO store_scrapinventory_summary_data (branch,report_date,shift,time_covered,item_id,item_name,transfer_out,date_created)
			VALUES ('$branch','$transdate','$shift','$time_covered','$item_id','$item_name','$weight','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) {
				if($_SESSION["OFFLINE_MODE"] == 0)
				{
					$functions->iTransferOutScrapInventory($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn);
				}				
				$functions->setUpdatePosting($table,$branch,$transdate,$shift,$rowid,$db);
			} 
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","Scrap Inventory Transfer Out Summary Successfuly Report Posted","success","Ok","","");
					$("#" + sessionStorage.navcount).click();
				</script>
			'); 
		}
	} 
	/*  ---------------------------------------------- */
}
if($mode == 'scrapinventory_transferin') 
{
	$tbl = "store_scrapinventory_transfer_data";
	$row_id = $_POST['rowid'];
	$query2 ="SELECT * FROM $tbl WHERE id='$row_id' AND item_id!=''";  
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
		$weight = $ROW['weight'];
		$time_covered = $ROW['time_covered'];
		$qty = $ROW['weight'] + $functions->GetScrapInventoryTransferInQtySum($branch,$transdate,$shift,$item_id,$db);

		
		if($item_id == '')	{ echo "No Id"; exit(); }
		$QUERY ="SELECT * FROM store_scrapinventory_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_scrapinventory_summary_data SET transfer_in='$weight' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
		} else {
			$pid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_supplies_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,transfer_in,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$weight','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		
		if($rowcount == $x) { 
			print_r('<script>app_alert("System Message","Scrap Inventory Transfer In Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); 
		}
	}
}

if($mode == 'scrapinventory_receiving')
{
	$tbl = "store_".$mode."_data";
	/*  ---------------------------------------------- */
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		
		$QUERY ="SELECT * FROM store_scrapinventory_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_scrapinventory_summary_data SET stock_in='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			}
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		} else {
			$queryDataInsert = "INSERT INTO store_scrapinventory_summary_data (branch,report_date,shift,time_covered,category,item_id,item_name,stock_in,date_created)
			VALUES ('$branch','$transdate','$shift','$time_covered','$category','$item_id','$item_name','$actual_count','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) {
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			} 
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","Scrap Inventory Summary Successfuly Report Posted","success","Ok","","");
					$("#" + sessionStorage.navcount).click();</script>
				</script>
			');
		}
	} 
	/*  ---------------------------------------------- */
}

if($mode == 'scrapinventory_badorder')
{
	$tbl = "store_".$mode."_data";
	/*  ---------------------------------------------- */
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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

		$QUERY ="SELECT * FROM store_scrapinventory_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_scrapinventory_summary_data SET bo='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)
			{
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			}
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		} else {
		
			$queryDataInsert = "INSERT INTO store_scrapinventory_summary_data (item_id,branch,report_date,shift,time_covered,item_name,bo)
			VALUES ('$item_id','$branch','$transdate','$shift','$time_covered','$itemname','$actual_count')";
			if ($db->query($queryDataInsert) === TRUE) {
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			} 
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","Scrap Inventory Summary Successfuly Report Posted","success","Ok","","");
					$("#" + sessionStorage.navcount).click();</script>
				</script>
			');
		}			
	} 			
	/*  ---------------------------------------------- */
}


/* ###################################### SUPPLIES TRANSFER IN ALL POST SUMMARY ###################################### */
if($mode == 'postAllSuppliesTransferIn'){

	$tbl = "store_supplies_transfer_data";

	$query2 ="SELECT * FROM $tbl WHERE report_date='$transdate' AND shift='$shift' AND transfer_to='$branch' AND item_id!=''";  
	$result2 = mysqli_query($db, $query2);  
	$rowcount=mysqli_num_rows($result2);
	$x=0;
	$datain=array();
	$dataup=array();
	$save = 0;	
	while($ROW = mysqli_fetch_array($result2)) 
	{
		$x++;
		$rowid =  $ROW['id'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$actual_count = $ROW['weight'] + $functions->GetSuppliesTransferInQtySum($branch,$transdate,$shift,$item_id,$db);
	
		$time_covered = $ROW['time_covered'];
		if($item_id == '')	{ echo "No Id"; exit(); }
		
		$QUERY ="SELECT * FROM store_supplies_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_supplies_summary_data SET transfer_in='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePostingtranRMIN($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
		} else {
			$pid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_supplies_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,transfer_in,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePostingtranRMIN($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		if($rowcount == $x) { 
			print_r('
				<script>
					app_alert("System Message","SUPPLIES Transfer-in Successfuly Posted","success","Ok","","");
					set_function("Transfer In/Out","supplies_transfer")
				</script>
			'); 
		}
	}
}
/* ###################################### SUPPLIES BAD ORDER SUMMARY ###################################### */
if($mode == 'supplies_pcount')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		
		$QUERY ="SELECT * FROM store_supplies_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_supplies_summary_data SET beginning='$beginning',actual_count='$actual_count',price_kg='$unitprice' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo "UPDATER ERROR::: ". $db->error; exit(); }
			
		} else {				
			$queryDataInsert = "INSERT INTO store_supplies_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,actual_count,beginning,price_kg,date_created)			
			VALUES ('$item_id','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$beginning','$unitprice','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo "INSERT ERROR::: ".$db->error; exit(); }					
		}
		if($rowcount == $x)
		{
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		}
	}
}
/* ###################################### SUPPLIES BAD ORDER SUMMARY ###################################### */
if($mode == 'supplies_badorder')
{
	$tbl = "store_".$mode."_data";
	/*  ---------------------------------------------- */
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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

		$QUERY ="SELECT * FROM store_supplies_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_supplies_summary_data SET bo='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)
			{
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			}
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		} else {
		
			$queryDataInsert = "INSERT INTO store_supplies_summary_data (item_id,branch,report_date,shift,time_covered,item_name,bo)
			VALUES ('$item_id','$branch','$transdate','$shift','$time_covered','$itemname','$actual_count')";
			if ($db->query($queryDataInsert) === TRUE) {
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			} 
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","Supplies Summary Successfuly Report Posted","success","Ok","","");
					$("#" + sessionStorage.navcount).click();</script>
				</script>
			');
		}			
	} 			
	/*  ---------------------------------------------- */
}
/* ###################################### SUPPLIES RECIEVING SUMMARY ###################################### */
if($mode == 'supplies_receiving')
{
	$tbl = "store_".$mode."_data";
	/*  ---------------------------------------------- */
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		
		$QUERY ="SELECT * FROM store_supplies_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_supplies_summary_data SET stock_in='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			}
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		} else {
			$queryDataInsert = "INSERT INTO store_supplies_summary_data (branch,report_date,shift,time_covered,category,item_id,item_name,stock_in,date_created)
			VALUES ('$branch','$transdate','$shift','$time_covered','$category','$item_id','$item_name','$actual_count','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) {
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			} 
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","Supplies Summary Successfuly Report Posted","success","Ok","","");
					$("#" + sessionStorage.navcount).click();</script>
				</script>
			');
		}
	} 
	/*  ---------------------------------------------- */
}
/* ###################################### SUPPLIES TRANSFER SUMMARY ###################################### */
if($mode == 'supplies_transfer')//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
{
	$table = "store_".$mode."_data";
	
	/*  ---------------------------------------------- */
	$query ="SELECT * FROM $table WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!=''";  
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
		$weight = $ROW['weight'] + $functions->GetSuppliesTransferOutQtySum($branch,$transdate,$shift,$item_id,$db);
		$units = $ROW['units'];
		$transfer_to = $ROW['transfer_to'];
		$date_created = $ROW['date_created'];
		$date_updated = $ROW['date_updated'];
		$updated_by = $ROW['updated_by'];
		$posted = $ROW['posted'];
		$status = $ROW['status'];

		$QUERY ="SELECT * FROM store_supplies_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_supplies_summary_data SET transfer_out='$weight' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{
				if($_SESSION["OFFLINE_MODE"] == 0)
				{
					$functions->iTransferOutSupplies($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn);
				}				
				$functions->setUpdatePosting($table,$branch,$transdate,$shift,$rowid,$db);
			}
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		} else {
			$queryDataInsert = "INSERT INTO store_supplies_summary_data (branch,report_date,shift,time_covered,item_id,item_name,transfer_out,date_created)
			VALUES ('$branch','$transdate','$shift','$time_covered','$item_id','$item_name','$weight','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) {
				if($_SESSION["OFFLINE_MODE"] == 0)
				{
					$functions->iTransferOutSupplies($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn);
				}				
				$functions->setUpdatePosting($table,$branch,$transdate,$shift,$rowid,$db);
			} 
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					app_alert("System Message","Supplies Transfer Out Summary Successfuly Report Posted","success","Ok","","");
					$("#" + sessionStorage.navcount).click();
				</script>
			'); 
		}
	} 
	/*  ---------------------------------------------- */
}
/* ###################################### SUPPLIES TRANSFER IN SUMMARY ###################################### */
if($mode == 'supplies_transferin') 
{
	$tbl = "store_supplies_transfer_data";
	$row_id = $_POST['rowid'];
	$query2 ="SELECT * FROM $tbl WHERE id='$row_id' AND item_id!=''";  
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
		$weight = $ROW['weight'];
		$time_covered = $ROW['time_covered'];
		$qty = $ROW['weight'] + $functions->GetRMTransferInQtySum($branch,$transdate,$shift,$item_id,$db);

		
		if($item_id == '')	{ echo "No Id"; exit(); }
		$QUERY ="SELECT * FROM store_supplies_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_supplies_summary_data SET transfer_in='$weight' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
		} else {
			$pid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_supplies_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,transfer_in,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$weight','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		
		if($rowcount == $x) { 
			print_r('<script>app_alert("System Message","Supplies Transfer In Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); 
		}
	}
}




/* ###################################### RAWMATS TRANSFER IN ALL POST SUMMARY ###################################### */
if($mode == 'postAllRMTransferIn'){

	$tbl = "store_rm_transfer_data";

	$query2 ="SELECT * FROM $tbl WHERE report_date='$transdate' AND shift='$shift' AND transfer_to='$branch' AND item_id!=''";  
	$result2 = mysqli_query($db, $query2);  
	$rowcount=mysqli_num_rows($result2);
	$x=0;
	$datain=array();
	$dataup=array();
	$save = 0;	
	while($ROW = mysqli_fetch_array($result2)) 
	{
		$x++;
		$rowid =  $ROW['id'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$actual_count = $ROW['weight'] + $functions->GetRMTransferInQtySum($branch,$transdate,$shift,$item_id,$db);
	
		$time_covered = $ROW['time_covered'];
		if($item_id == '')	{ echo "No Id"; exit(); }
		
		$QUERY ="SELECT * FROM store_rm_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_rm_summary_data SET transfer_in='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePostingtranRMIN($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
		} else {
			$pid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_rm_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,transfer_in,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePostingtranRMIN($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		if($rowcount == $x) { 
			print_r('
				<script>
					app_alert("System Message","RAWMATS Transfer-in Successfuly Posted","success","Ok","","");
					set_function("Transfer In/Out","rm_transfer")
				</script>
			'); 
		}
	}
}
/* ###################################### TRANSFER IN ALL POST SUMMARY ###################################### */
if($mode == 'postAllTransferIn'){

	$tbl = "store_transfer_data";

	$query2 ="SELECT * FROM $tbl WHERE report_date='$transdate' AND shift='$shift' AND transfer_to='$branch' AND item_id!=''";  
	$result2 = mysqli_query($db, $query2);  
	$rowcount=mysqli_num_rows($result2);
	$x=0;
	$datain=array();
	$dataup=array();
	$save = 0;	
	while($ROW = mysqli_fetch_array($result2)) 
	{
		$x++;
		$rowid =  $ROW['id'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$actual_count = $ROW['quantity'] + $functions->GetTransferInQtySum($branch,$transdate,$shift,$item_id,$db);
				
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
			print_r('
				<script>
					app_alert("System Message","Transfer-in Successfuly Posted","success","Ok","","");
					set_function("Transfer In/Out","transfer")
				</script>
			'); 
		}
	}
}
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
	if($_POST['file_name'] == 'supplies_summary')
	{
		$table = 'store_supplies_summary_data';
	}
	if($_POST['file_name'] == 'scrapinventory_summary')
	{
		$table = 'store_scrapinventory_summary_data';
	}
	if($_POST['file_name'] == 'boinventory_summary')
	{
		$table = 'store_boinventory_summary_data';
	}

	$queryDataUpdates = "UPDATE $table SET posted='Posted',`status`='Closed' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id!=''";
	if($db->query($queryDataUpdates) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				swal("Success","Summary Successfuly Posted","success");
			</script>
		';	
		print_r($cmd);
	}
	else
	{
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
/* ###################################### RAWMATS BAD ORDER SUMMARY ###################################### */
if($mode == 'rm_pcount')
{
	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		
		$QUERY ="SELECT * FROM store_rm_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_rm_summary_data SET beginning='$beginning',actual_count='$actual_count',price_kg='$unitprice' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
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
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		} else {
		
			$queryDataInsert = "INSERT INTO store_rm_summary_data (item_id,branch,report_date,shift,time_covered,item_name,bo)
			VALUES ('$item_id','$branch','$transdate','$shift','$time_covered','$itemname','$actual_count')";
			if ($db->query($queryDataInsert) === TRUE) {
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			} 
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
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
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		} else {
			$queryDataInsert = "INSERT INTO store_rm_summary_data (branch,report_date,shift,time_covered,category,item_id,item_name,stock_in,date_created)
			VALUES ('$branch','$transdate','$shift','$time_covered','$category','$item_id','$item_name','$actual_count','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) {
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);
			} 
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
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


if($mode == 'rm_transfer') {
    $table = "store_".$mode."_data";
    
    $query = "SELECT * FROM $table WHERE branch='$branchNow' AND shift='$shift' AND report_date='$transdate' AND item_id!=''";  
    $result = mysqli_query($db, $query);  
    $rowcount = mysqli_num_rows($result);
    $x = 0;
    $datain = array();
    $dataup = array();
    $save = 0;

    while($ROW = mysqli_fetch_array($result)) {
        $x++;        
        $rowid       = $ROW['id'];
        $rowBranch   = $ROW['branch'];
        $report_date = $ROW['report_date'];
        $shift       = $ROW['shift'];
        $time_covered= $ROW['time_covered'];
        $person      = $ROW['employee_name'];
        $encoder     = $ROW['supervisor'];
        $category    = $ROW['category'];
        $item_id     = $ROW['item_id'];
        $item_name   = $ROW['item_name'];
        $weight      = $ROW['weight'] + $functions->GetRMTransferOutQtySum($rowBranch, $transdate, $shift, $item_id, $db);
        $units       = $ROW['units'];
        $transfer_from = $ROW['transfer_from'];
        $transfer_to   = $ROW['transfer_to'];
        $date_created  = $ROW['date_created'];
        $date_updated  = $ROW['date_updated'];
        $updated_by    = $ROW['updated_by'];
        $posted        = $ROW['posted'];
        $status        = $ROW['status'];

        // --- Determine transfer type relative to current branch ---
        if ($transfer_to == $branchNow) {
            $transferCol = 'transfer_in';
        } elseif ($transfer_from == $branchNow) {
            $transferCol = 'transfer_out';
        } else {
            $transferCol = null; // skip if not related to this branch
        }

        if ($transferCol !== null) {
            $summaryCheck = "SELECT * FROM store_rm_summary_data 
                             WHERE branch='$branchNow' AND report_date='$transdate' 
                             AND shift='$shift' AND item_id='$item_id'";
            $summaryResult = mysqli_query($db, $summaryCheck);  

            if($summaryResult->num_rows > 0) {
                $queryUpdate = "UPDATE store_rm_summary_data 
                                SET $transferCol='$weight' 
                                WHERE branch='$branchNow' AND report_date='$transdate' 
                                  AND shift='$shift' AND item_id='$item_id'";
                $db->query($queryUpdate);
                $functions->setUpdatePosting($table, $branchNow, $transdate, $shift, $rowid, $db);
            } else {
                $queryInsert = "INSERT INTO store_rm_summary_data 
                                (branch, report_date, shift, time_covered, item_id, item_name, $transferCol, date_created)
                                VALUES ('$branchNow', '$transdate', '$shift', '$time_covered', '$item_id', '$item_name', '$weight', '$date_created')";
                $db->query($queryInsert);
                $functions->setUpdatePosting($table, $branchNow, $transdate, $shift, $rowid, $db);
            }
        }

        if($rowcount == $x) {
            echo '
            <script>
                app_alert("System Message","Rawmats Transfer Summary Successfully Posted","success","Ok","","");
                $("#"+sessionStorage.navcount).click();
            </script>';
        }
    } 
}



/* ###################################### RAWMATS TRANSFER IN SUMMARY ###################################### */
if($mode == 'rm_transferin') 
{
	$tbl = "store_rm_transfer_data";
	$row_id = $_POST['rowid'];
	$query2 ="SELECT * FROM $tbl WHERE id='$row_id' AND item_id!=''";  
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
		$weight = $ROW['weight'];
		$time_covered = $ROW['time_covered'];
		$qty = $ROW['weight'] + $functions->GetRMTransferInQtySum($branch,$transdate,$shift,$item_id,$db);

		
		if($item_id == '')	{ echo "No Id"; exit(); }
		$QUERY ="SELECT * FROM store_rm_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_rm_summary_data SET transfer_in='$qty' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
		} else {
			$pid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_rm_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,transfer_in,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$qty','$time_stamp')";
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
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}

	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(actual_count) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
	
	$result = mysqli_query($db, $query);  
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$rowid = $ROW['id'];
		$supervisor = $ROW['supervisor'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$category = $ROW['category'];
		$shiftS = $ROW['shift'];
		$time_covered = $ROW['time_covered'];
		$actual_count = $ROW['actual_count'];
		$to_shift = $ROW['to_shift'];
		$to_shift_date = $ROW['to_shift_date'];
		
		$unit_pricee = $FunctionForms->unitPriceGet($item_id,$shift,$transdate,$branch,$db);
		
		$beginning = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
		$t_in = $FunctionForms->transferInGet($item_id,$shift,$transdate,$branch,$db);
		$t_out = $FunctionForms->transferOutGet($item_id,$shift,$transdate,$branch,$db);
		$charges = $FunctionForms->chargesGet($item_id,$shift,$transdate,$branch,$db);
		$snacks = $FunctionForms->snacksGet($item_id,$shift,$transdate,$branch,$db);
		$bo = $FunctionForms->boGet($item_id,$shift,$transdate,$branch,$db);
		
		$stock_in = $FunctionForms->stockInGet($item_id,$shift,$transdate,$branch,$db);
		
		$damage = $FunctionForms->damageGet($item_id,$shift,$transdate,$branch,$db);
		$complementary = $FunctionForms->complementaryGet($item_id,$shift,$transdate,$branch,$db);

		$frozendough = $FunctionForms->frozenDoughGet($item_id,$shift,$transdate,$branch,$db);
		$actualCount = $actual_count;
		
		
		$total = $beginning + $stock_in + $t_in + $frozendough;
		$shouldbe = $total - $t_out - $charges - $snacks - $bo - $damage - $complementary;
		
		$sold = $shouldbe == 0 ? 0: $shouldbe - $actualCount;
		
		
		$unit_price = $unit_pricee == 0 ? $FunctionForms->itemItemsPriceGet($item_id,$transdate,$db): $unit_pricee;

		$amount = $sold * $unit_price;
		
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET actual_count='$actual_count', beginning='$beginning', should_be='$shouldbe', sold='$sold', amount='$amount', total='$total' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo "A ". $db->error;}
			
		} else {				
			$pid = $functions->getPID($item_id,$db);
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,supervisor,category,item_name,actual_count,beginning,should_be,sold,amount,total,unit_price)			
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$supervisor','$category','$item_name','$actual_count','$beginning','$shouldbe','$sold','$amount','$total','$unit_price')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo "B ".$db->error; }					
		}
		if($rowcount == $x)
		{
			/*######### ABANG na CODE ##############*/
			
			$shiftnow = $shift;
			$shiftforward = $myShifting == 2? $FunctionForms->twoShiftingShiftGet($shift):$FunctionForms->threeShiftingShiftGet($shift);
			$transdatenow = $transdate;
			$transdateforward = $myShifting == 2? $FunctionForms->twoShiftingTransDateGet($shift,$transdate): $FunctionForms->threeShiftingTransDateGet($shift,$transdate);
			$FunctionForms->backTrachPcountLost($shiftnow, $shiftforward, $transdatenow, $transdateforward, $branch, $db);
			/*######### ABANG na CODE ##############*/

			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		}
	}
}
/* ###################################### FROZEN DOUGH SUMMARY ###################################### */
if($mode == 'frozendough')
{
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}

	$tbl = "store_".$mode."_data";
	$query ="SELECT * FROM $tbl WHERE shift='$shift' AND branch='$branch' AND report_date='$transdate' AND item_id!=''";  
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

		$kioUsedGetVal = $FunctionForms->kiloUsedGet($item_id, $shift, $transdate, $branch, $db) == 0? 0: $FunctionForms->kiloUsedGet($item_id, $shift, $transdate, $branch, $db);
		$kilo_used = $FunctionForms->frozenFrozenKiloUsedGet($item_id, $shift, $transdate, $branch, $db);
		
		$actual_yield = $ROW['actual_yield'];		
		$inputtime = $ROW['inputtime'];
		$supervisor = $ROW['supervisor'];
		
		$myShifting = $_SESSION['appstore_shifting'];
		$beginning = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
		$t_in = $FunctionForms->transferInGet($item_id,$shift,$transdate,$branch,$db);
		$t_out = $FunctionForms->transferOutGet($item_id,$shift,$transdate,$branch,$db);
		$charges = $FunctionForms->chargesGet($item_id,$shift,$transdate,$branch,$db);
		$snacks = $FunctionForms->snacksGet($item_id,$shift,$transdate,$branch,$db);
		$bo = $FunctionForms->boGet($item_id,$shift,$transdate,$branch,$db);
		
		$stock_in = $FunctionForms->stockInGet($item_id,$shift,$transdate,$branch,$db);
		
		$damage = $FunctionForms->damageGet($item_id,$shift,$transdate,$branch,$db);
		$complementary = $FunctionForms->complementaryGet($item_id,$shift,$transdate,$branch,$db);

		$frozendough = $FunctionForms->frozenFrozenDoughGet($item_id,$shift,$transdate,$branch,$db);
		$actualCount = $FunctionForms->actualCountGet($item_id,$shift,$transdate,$branch,$db);
		
		$total = $beginning + $stock_in + $t_in + $frozendough;
		$shouldbe = $total - $t_out - $charges - $snacks - $bo - $damage - $complementary;
		$sold = $shouldbe - $actualCount;
		$amount = $sold * $unit_price;
			
		$QUERYS ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULTS = mysqli_query($db, $QUERYS);  
		if($RESULTS->num_rows  > 0)
		{

			$queryDataUpdates = "UPDATE store_summary_data SET category='$category',frozendough='$frozendough',unit_price='$unit_price',actual_yield='$actual_yield',kilo_used='$kilo_used',beginning='$beginning',should_be='$shouldbe', sold='$sold', amount='$amount', total='$total' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
			
		} else {
			$pid = $functions->getPID($item_id,$db);
			
			$kilo_used = $functions->getSumfgtsKiloUse($branch,$transdate,$shift,$item_id,$db);
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,supervisor,inputtime,category,item_name,standard_yield,kilo_used,beginning,t_in,frozendough,total,should_be,sold,unit_price,amount)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$supervisor','$inputtime','$category','$item_name','$standard_yield','$kilo_used','$beginning','$t_in','$frozendough','$total','$shouldbe','$sold','$unit_price','$amount')";
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
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}


	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		$unit_pricee = $FunctionForms->unitPriceGet($item_id,$shift,$transdate,$branch,$db);
		
		$myShifting = $_SESSION['appstore_shifting'];
		$beginning = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
		$t_in = $FunctionForms->transferInGet($item_id,$shift,$transdate,$branch,$db);
		$t_out = $FunctionForms->transferOutGet($item_id,$shift,$transdate,$branch,$db);
		$charges = $FunctionForms->chargesGet($item_id,$shift,$transdate,$branch,$db);
		$snacks = $FunctionForms->snacksGet($item_id,$shift,$transdate,$branch,$db);
		$bo = $FunctionForms->boGet($item_id,$shift,$transdate,$branch,$db);
		
		$stock_in = $FunctionForms->fgtsActualYieldGetPosted($item_id,$shift,$transdate,$branch,$db) + $actual_count;
		
		$damage = $FunctionForms->damageGet($item_id,$shift,$transdate,$branch,$db);
		$complementary = $FunctionForms->complementaryGet($item_id,$shift,$transdate,$branch,$db);

		$frozendough = $FunctionForms->frozenDoughGet($item_id,$shift,$transdate,$branch,$db);
		$actualCount = $FunctionForms->actualCountGet($item_id,$shift,$transdate,$branch,$db);
		
		$total = $beginning + $stock_in + $t_in + $frozendough;
		$shouldbe = $total - $t_out - $charges - $snacks - $bo - $damage - $complementary;
		$sold = $shouldbe - $actualCount;
		
		$unit_price = $unit_pricee == 0 ? $functions->getItemPrice($item_id,$db): $unit_pricee;

		$amount = $sold * $unit_price;

		
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET stock_in='$stock_in', beginning='$beginning', should_be='$shouldbe', sold='$sold', amount='$amount', total='$total' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
		} else {			
			$pid = $functions->getPID($item_id,$db);
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,stock_in,date_created,beginning,should_be,sold,amount,total,unit_price)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$stock_in','$time_stamp','$beginning','$shouldbe','$sold','$amount','$total','$unit_price')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }
	} 
}
/* ###################################### REQUEST SUMMARY ###################################### */
if($mode == 'request')
{
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}

	$tbl = "store_".$mode."_data";
	$query2 ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}

	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		$unit_price = $ROW['unit_price'];	
		
		$myShifting = $_SESSION['appstore_shifting'];
		$beginning = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
		$t_in = $FunctionForms->transferInGet($item_id,$shift,$transdate,$branch,$db);
		$t_out = $FunctionForms->transferOutGet($item_id,$shift,$transdate,$branch,$db);
		$charges = $FunctionForms->chargesGet($item_id,$shift,$transdate,$branch,$db);
		$snacks = $FunctionForms->snacksGet($item_id,$shift,$transdate,$branch,$db);
		$bo = $FunctionForms->boGet($item_id,$shift,$transdate,$branch,$db);
		
		$stock_in = $FunctionForms->stockInGet($item_id,$shift,$transdate,$branch,$db);
		
		$damage = $FunctionForms->damageGet($item_id,$shift,$transdate,$branch,$db);
		$complementary = $quantity;//$FunctionForms->complementaryGet($item_id,$shift,$transdate,$branch,$db);

		$frozendough = $FunctionForms->frozenDoughGet($item_id,$shift,$transdate,$branch,$db);
		$actualCount = $FunctionForms->actualCountGet($item_id,$shift,$transdate,$branch,$db);
		
		$total = $beginning + $stock_in + $t_in + $frozendough;
		$shouldbe = $total - $t_out - $charges - $snacks - $bo - $damage - $complementary;
		$sold = $shouldbe - $actualCount;
		$amount = $sold * $unit_price;

		
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET complimentary='$complementary', beginning='$beginning', should_be='$shouldbe', sold='$sold', amount='$amount', total='$total' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
		} else {
			$mid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,complimentary,beginning,should_be,sold,amount,total,unit_price)
			VALUES ('$item_id','$branch','$transdate','$shift','$time_covered','$category','$item_name','$complementary','$beginning','$shouldbe','$sold','$amount','$total','$unit_price')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }
	} 
}
/* ###################################### DAMAGE SUMMARY ###################################### */
if($mode == 'damage')
{
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}

	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		$unit_price = $ROW['unit_price'];	
		
		$myShifting = $_SESSION['appstore_shifting'];
		$beginning = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
		$t_in = $FunctionForms->transferInGet($item_id,$shift,$transdate,$branch,$db);
		$t_out = $FunctionForms->transferOutGet($item_id,$shift,$transdate,$branch,$db);
		$charges = $FunctionForms->chargesGet($item_id,$shift,$transdate,$branch,$db);
		$snacks = $FunctionForms->snacksGet($item_id,$shift,$transdate,$branch,$db);
		$bo = $FunctionForms->boGet($item_id,$shift,$transdate,$branch,$db);
		
		$stock_in = $FunctionForms->stockInGet($item_id,$shift,$transdate,$branch,$db);
		
		$damage = $quantity;//$FunctionForms->damageGet($item_id,$shift,$transdate,$branch,$db);
		$complementary = $FunctionForms->complementaryGet($item_id,$shift,$transdate,$branch,$db);

		$frozendough = $FunctionForms->frozenDoughGet($item_id,$shift,$transdate,$branch,$db);
		$actualCount = $FunctionForms->actualCountGet($item_id,$shift,$transdate,$branch,$db);
		
		
		$total = $beginning + $stock_in + $t_in + $frozendough;
		$shouldbe = $total - $t_out - $charges - $snacks - $bo - $damage - $complementary;
		$sold = $shouldbe - $actualCount;
		$amount = $sold * $unit_price;

		
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET damaged='$damage', beginning='$beginning', should_be='$shouldbe', sold='$sold', amount='$amount', total='$total' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
		} else {
			$pid = $functions->getPID($item_id,$db);		
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,damaged,beginning,should_be,sold,amount,total,unit_price)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$damage','$beginning','$shouldbe','$sold','$amount','$total','$unit_price')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }
	} 
}
/* ###################################### BAD ORDER SUMMARY ###################################### */
if($mode == 'badorder')
{
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}


	$tbl = "store_".$mode."_data";
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		$unit_price = $ROW['unit_price'];		

		$myShifting = $_SESSION['appstore_shifting'];
		$beginning = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
		$t_in = $FunctionForms->transferInGet($item_id,$shift,$transdate,$branch,$db);
		$t_out = $FunctionForms->transferOutGet($item_id,$shift,$transdate,$branch,$db);
		$charges = $FunctionForms->chargesGet($item_id,$shift,$transdate,$branch,$db);
		$snacks = $FunctionForms->snacksGet($item_id,$shift,$transdate,$branch,$db);
		$bo = $quantity;
		
		$stock_in = $FunctionForms->stockInGet($item_id,$shift,$transdate,$branch,$db);
		
		$damage = $FunctionForms->damageGet($item_id,$shift,$transdate,$branch,$db);
		$complementary = $FunctionForms->complementaryGet($item_id,$shift,$transdate,$branch,$db);

		$frozendough = $FunctionForms->frozenDoughGet($item_id,$shift,$transdate,$branch,$db);
		$actualCount = $FunctionForms->actualCountGet($item_id,$shift,$transdate,$branch,$db);
		
		
		$total = $beginning + $stock_in + $t_in + $frozendough;
		$shouldbe = $total - $t_out - $charges - $snacks - $bo - $damage - $complementary;
		$sold = $shouldbe - $actualCount;
		$amount = $sold * $unit_price;


		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET bo='$bo', beginning='$beginning', should_be='$shouldbe', sold='$sold', amount='$amount', total='$total' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
		} else {		
			$pid = $functions->getPID($item_id,$db);		
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,bo,beginning,should_be,sold,amount,total,unit_price)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$bo','$beginning','$shouldbe','$sold','$amount','$total','$unit_price')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }			
	}
}
/* ###################################### SNACKS SUMMARY ###################################### */
if($mode == 'snacks')
{
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}


	$tbl = "store_".$mode."_data";	
	$query ="SELECT *, SUM(quantity) as qty  FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!='' GROUP BY item_id";  
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
		$unit_price = $ROW['unit_price'];
		
		$myShifting = $_SESSION['appstore_shifting'];
		$beginning = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
		$t_in = $FunctionForms->transferInGet($item_id,$shift,$transdate,$branch,$db);
		$t_out = $FunctionForms->transferOutGet($item_id,$shift,$transdate,$branch,$db);
		$charges = $FunctionForms->chargesGet($item_id,$shift,$transdate,$branch,$db);
		$snacks = $actual_count;
		$bo = $FunctionForms->boGet($item_id,$shift,$transdate,$branch,$db);
		
		$stock_in = $FunctionForms->stockInGet($item_id,$shift,$transdate,$branch,$db);
		
		$damage = $FunctionForms->damageGet($item_id,$shift,$transdate,$branch,$db);
		$complementary = $FunctionForms->complementaryGet($item_id,$shift,$transdate,$branch,$db);

		$frozendough = $FunctionForms->frozenDoughGet($item_id,$shift,$transdate,$branch,$db);
		$actualCount = $FunctionForms->actualCountGet($item_id,$shift,$transdate,$branch,$db);
		
		
		$total = $beginning + $stock_in + $t_in + $frozendough;
		$shouldbe = $total - $t_out - $charges - $snacks - $bo - $damage - $complementary;
		$sold = $shouldbe - $actualCount;
		$amount = $sold * $unit_price;


		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET snacks='$snacks', beginning='$beginning', should_be='$shouldbe', sold='$sold', amount='$amount', total='$total' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db);	 } else { echo $db->error; }
		} else {
		
			$pid = $functions->getPID($item_id,$db);				
			$queryDataInsert ="INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,snacks,beginning,should_be,sold,amount,total,unit_price)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$snacks','$beginning','$shouldbe','$sold','$amount','$total','$unit_price')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		
		if($rowcount == $x) { print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); }
		
	} 
}
/* ###################################### CHARGES SUMMARY ###################################### */
if($mode == 'charges')
{
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}

	$tbl = "store_".$mode."_data";
	$query ="SELECT * FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!=''";  
	$result = mysqli_query($db, $query); 	 
	$rowcount=mysqli_num_rows($result);
	$x=0;$datain=array();$dataup=array();$save = 0;
	while($ROW = mysqli_fetch_array($result)) 
	{
		$x++;
		$pid = '';
		$rowid = $ROW['id'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$actual_count = $functions->GetChargesQtySum($branch,$transdate,$shift,$item_id,$db);
		$shift = $ROW['shift'];
		$time_covered = $ROW['time_covered'];
		$slip_no = $ROW['slip_no'];
		$supervisor = $ROW['supervisor'];
		$unit_price = $ROW['unit_price'];
		
		$myShifting = $_SESSION['appstore_shifting'];
		$beginning = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
		$t_in = $FunctionForms->transferInGet($item_id,$shift,$transdate,$branch,$db);
		$t_out = $FunctionForms->transferOutGet($item_id,$shift,$transdate,$branch,$db);
		$charges = $FunctionForms->chargesChargesGet($item_id,$shift,$transdate,$branch,$db);
		$snacks = $FunctionForms->snacksGet($item_id,$shift,$transdate,$branch,$db);
		$bo = $FunctionForms->boGet($item_id,$shift,$transdate,$branch,$db);
		
		$stock_in = $FunctionForms->stockInGet($item_id,$shift,$transdate,$branch,$db);
		
		$damage = $FunctionForms->damageGet($item_id,$shift,$transdate,$branch,$db);
		$complementary = $FunctionForms->complementaryGet($item_id,$shift,$transdate,$branch,$db);

		$frozendough = $FunctionForms->frozenDoughGet($item_id,$shift,$transdate,$branch,$db);
		$actualCount = $FunctionForms->actualCountGet($item_id,$shift,$transdate,$branch,$db);
		
		
		$total = $beginning + $stock_in + $t_in + $frozendough;
		$shouldbe = $total - $t_out - $charges - $snacks - $bo - $damage - $complementary;
		$sold = $shouldbe - $actualCount;
		$amount = $sold * $unit_price;


		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET charges='$charges', beginning='$beginning', should_be='$shouldbe', sold='$sold', amount='$amount', total='$total' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE)	{ $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
		} else {	
			$pid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,supervisor,category,item_name,charges,beginning,should_be,sold,amount,total,unit_price)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$supervisor','$category','$item_name','$charges','$beginning','$shouldbe','$sold','$amount','$total','$unit_price')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}								
		if($rowcount == $x) { 
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); 
		}
	} 
}
if($mode == 'transferin')
{
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}

	$tbl = "store_transfer_data";
	$row_id = $_POST['rowid'];
	$query2 ="SELECT * FROM $tbl WHERE id='$row_id' AND item_id!=''";  
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
		$unit_price = $ROW['unit_price'];
		$actual_count = $ROW['quantity'] + $FunctionForms->transferInGet($item_id, $shift, $transdate, $branch, $db);
		
		
		$myShifting = $_SESSION['appstore_shifting'];
		$beginning = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
		$t_in = $actual_count;
		
		$t_out = $FunctionForms->transferOutActualYieldGet($item_id,$shift,$transdate,$branch,$db);
		
		$charges = $FunctionForms->chargesGet($item_id,$shift,$transdate,$branch,$db);
		$snacks = $FunctionForms->snacksGet($item_id,$shift,$transdate,$branch,$db);
		$bo = $FunctionForms->boGet($item_id,$shift,$transdate,$branch,$db);
		
		$stock_in = $FunctionForms->stockInGet($item_id,$shift,$transdate,$branch,$db);
		
		$damage = $FunctionForms->damageGet($item_id,$shift,$transdate,$branch,$db);
		$complementary = $FunctionForms->complementaryGet($item_id,$shift,$transdate,$branch,$db);

		$frozendough = $FunctionForms->frozenDoughGet($item_id,$shift,$transdate,$branch,$db);
		$actualCount = $FunctionForms->actualCountGet($item_id,$shift,$transdate,$branch,$db);
		
		$total = $beginning + $stock_in + $actual_count + $frozendough;
		$shouldbe = $total - $t_out - $charges - $snacks - $bo - $damage - $complementary;
		$sold = $shouldbe - $actualCount;
		$amount = $sold * $unit_price;		

				
		$time_covered = $ROW['time_covered'];
		if($item_id == '')	{ echo "No Id"; exit(); }
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET t_in='$t_in', beginning='$beginning', should_be='$shouldbe', sold='$sold', amount='$amount', total='$total' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
		} else {
			$pid = $functions->getPID($item_id,$db);			
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,t_in,beginning,should_be,sold,amount,unit_price,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$actual_count','$beginning','$shouldbe','$sold','$amount','$unit_price','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }
		}
		
		if($rowcount == $x) {
			print_r('<script>app_alert("System Message","Transfer In Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>'); 
		}
	}
}
if($mode == 'transfer')
{
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}

	$tbl = "store_".$mode."_data";
	$query2 ="SELECT * FROM $tbl WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id!=''";  
	$result2 = mysqli_query($db, $query2);  
	$rowcount=mysqli_num_rows($result2);
	$x=0;
	$actual_count = 0;
	while($ROW = mysqli_fetch_array($result2)) 
	{
		$x++;
		$rowid =  $ROW['id'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$time_covered = $ROW['time_covered'];
		$to_branch = $ROW['transfer_to'];
		$date = $ROW['report_date'];
		$person = $ROW['employee_name'];
		$encoder = $ROW['supervisor'];
		$item_id = $ROW['item_id'];
		$quantity = $ROW['quantity'];
		$unit_price = $ROW['unit_price'];
		$amount = $ROW['amount'];
		$to_branch = $ROW['transfer_to'];
		$amount = $ROW['amount'];	
		
		$actual_count = $FunctionForms->transferTransferOutQuantityGet($item_id,$shift,$transdate,$branch,$db);	
		
		$myShifting = $_SESSION['appstore_shifting'];
		$beginning = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
		$t_in = $FunctionForms->transferInGet($item_id,$shift,$transdate,$branch,$db);
		
		$t_out = $actual_count;
		
		$charges = $FunctionForms->chargesGet($item_id,$shift,$transdate,$branch,$db);
		$snacks = $FunctionForms->snacksGet($item_id,$shift,$transdate,$branch,$db);
		$bo = $FunctionForms->boGet($item_id,$shift,$transdate,$branch,$db);
		
		$stock_in = $FunctionForms->stockInGet($item_id,$shift,$transdate,$branch,$db);
		
		$damage = $FunctionForms->damageGet($item_id,$shift,$transdate,$branch,$db);
		$complementary = $FunctionForms->complementaryGet($item_id,$shift,$transdate,$branch,$db);

		$frozendough = $FunctionForms->frozenDoughGet($item_id,$shift,$transdate,$branch,$db);
		$actualCount = $FunctionForms->actualCountGet($item_id,$shift,$transdate,$branch,$db);
		
		$total = $beginning + $stock_in + $t_in + $frozendough;
		$shouldbe = $total - $t_out - $charges - $snacks - $bo - $damage - $complementary;
		$sold = $shouldbe - $actualCount;
		$amountt = $sold * $unit_price;		
		
		
		if($item_id == '')	{ echo "No Id"; exit(); }
			
		$QUERY ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULT = mysqli_query($db, $QUERY);  
		if($RESULT->num_rows > 0)
		{
			$queryDataUpdates = "UPDATE store_summary_data SET t_out='$actual_count', beginning='$beginning', should_be='$shouldbe', sold='$sold', amount='$amountt', total='$total' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if ($db->query($queryDataUpdates) === TRUE) { 
				if($_SESSION["OFFLINE_MODE"] == 0)
				{
					$functions->iTransferOut($tbl,'FGTS',$rowid,$branch,$date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$quantity,$unit_price,$amount,$to_branch,$time_stamp,$conn);				
				}
				$functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); 
			}
			else
			{
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}
		} 
		else
		{
			$pid = $functions->getPID($item_id,$db);
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,category,item_name,t_out,total,should_be,sold,amount,unit_price,beginning,date_created)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$category','$item_name','$t_out','$total','$shouldbe','$sold','$amountt','$unit_price','$beginning','$time_stamp')";
			if ($db->query($queryDataInsert) === TRUE) { 
				if($_SESSION["OFFLINE_MODE"] == 0)
				{
					$functions->iTransferOut($tbl,'FGTS',$rowid,$branch,$date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$quantity,$unit_price,$amount,$to_branch,$time_stamp,$conn);
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
	$myShifting = $_SESSION['appstore_shifting'];
	$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

	if($prevCutPcountStatus == 0){
		print_r('<script>app_alert("System Message","We are unable to provide a summary without posting previous pcount","warning","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		exit();
	}

	
	$tbl = "store_".$mode."_data";
	$query ="SELECT * FROM $tbl WHERE shift='$shift' AND branch='$branch' AND report_date='$transdate' AND item_id!=''";  
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
		$kilo_used = $FunctionForms->fgtsKiloUsedGet($item_id, $shift, $transdate, $branch, $db);
		$actual_yield = $ROW['actual_yield'];		
		$inputtime = $ROW['inputtime'];
		$supervisor = $ROW['supervisor'];
//		$quantity=$actual_yield;
		
		$myShifting = $_SESSION['appstore_shifting'];
		$beginning = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
		$t_in = $FunctionForms->transferInGet($item_id,$shift,$transdate,$branch,$db);
		$t_out = $FunctionForms->transferOutGet($item_id,$shift,$transdate,$branch,$db);
		$charges = $FunctionForms->chargesGet($item_id,$shift,$transdate,$branch,$db);
		$snacks = $FunctionForms->snacksGet($item_id,$shift,$transdate,$branch,$db);
		$bo = $FunctionForms->boGet($item_id,$shift,$transdate,$branch,$db);
		
		$quantity = $FunctionForms->fgtsActualYieldGet($item_id,$shift,$transdate,$branch,$db);
		
		$damage = $FunctionForms->damageGet($item_id,$shift,$transdate,$branch,$db);
		$complementary = $FunctionForms->complementaryGet($item_id,$shift,$transdate,$branch,$db);

		$frozendough = $FunctionForms->frozenDoughGet($item_id,$shift,$transdate,$branch,$db);
		$actualCount = $FunctionForms->actualCountGet($item_id,$shift,$transdate,$branch,$db);
		
		
		$total = $beginning + $quantity + $t_in + $frozendough;
		$shouldbe = $total - $t_out - $charges - $snacks - $bo - $damage - $complementary;
		$sold = $shouldbe - $actualCount;
		$amount = $sold * $unit_price;
		
		$QUERYS ="SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";  
		$RESULTS = mysqli_query($db, $QUERYS);  
		if($RESULTS->num_rows  > 0)
		{
			$sumitemactualcount = $functions->getSumfgts($branch,$transdate,$shift,$item_id,$db);
			$queryDataUpdates = "UPDATE store_summary_data SET category='$category',stock_in='$sumitemactualcount',unit_price='$unit_price',actual_yield='$actual_yield',kilo_used='$kilo_used',beginning='$beginning',should_be='$shouldbe', sold='$sold', amount='$amount', total='$total' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
			if($db->query($queryDataUpdates) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error;}
			
		} else {
			$pid = $functions->getPID($item_id,$db);
			
			$kilo_used = $functions->getSumfgtsKiloUse($branch,$transdate,$shift,$item_id,$db);
			$queryDataInsert = "INSERT INTO store_summary_data (item_id,branch,report_date,shift,time_covered,supervisor,inputtime,category,item_name,stock_in,standard_yield,kilo_used,actual_yield,beginning,t_in,frozendough,total,should_be,sold,unit_price,amount)
			VALUES ('$pid','$branch','$transdate','$shift','$time_covered','$supervisor','$inputtime','$category','$item_name','$quantity','$standard_yield','$kilo_used','$actual_yield','$beginning','$t_in','$frozendough','$total','$shouldbe','$sold','$unit_price','$amount')";
			if ($db->query($queryDataInsert) === TRUE) { $functions->setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db); } else {  echo $db->error; }			
		}
		
		if($rowcount == $x)
		{	
			print_r('<script>app_alert("System Message","Summary Successfuly Report Posted","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
		}
	} 
}
