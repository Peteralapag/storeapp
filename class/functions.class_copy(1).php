<?php
class TheFunctions
{
	public function GetShifting()
	{
		if(file_exists('./.file/shifting.conf'))
		{
			foreach(file('./.file/shifting.conf') as $line) {
				$shiftting = $line;
				return $shiftting;
			}
		} else {
			return '';
		}
	}
	public function CalculateSummary($rowid,$total,$should_be,$sold,$amount,$branch_name,$branch_date,$branch_shift,$db)
	{
		$query = "UPDATE store_summary_data SET should_be='$should_be',sold='$sold',amount='$amount',total='$total'
		WHERE branch='$branch_name' AND report_date='$branch_date' AND shift='$branch_shift' AND id='$rowid'";
		if ($db->query($query) === TRUE) { } else { echo $db->error; exit(); }
	}
	public function OnlineStatus($is_connected,$ip_hosts)
	{
		if($is_connected == 1)
		{
			return 1;
		} else {
			return 0;
		}
	}
	public function GetSubmittedData($module,$branch,$transdate,$shift,$conn)
	{
		$table = "store_".$module."_data";
		$sqlMainEmployee = "SELECT COUNT(id) as mecnt FROM $table WHERE  branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$MainEmpResult = mysqli_query($conn, $sqlMainEmployee);
		return mysqli_fetch_assoc($MainEmpResult)['mecnt'];
	}
	public function GetDataStatus($table,$type,$branch,$db,$conn)
	{
		if($table == 'employees')
		{
			if($type == 'main')
			{
				$sqlMainEmployee = "SELECT COUNT(id) as mecnt FROM tbl_employees WHERE branch='$branch' AND status='Active'";
				$MainEmpResult = mysqli_query($conn, $sqlMainEmployee);
				$mecount = mysqli_fetch_assoc($MainEmpResult)['mecnt'];
				return $mecount;
			}
			if($type == 'branch')
			{
				$sqlBranchEmployee = "SELECT COUNT(id) as becnt FROM tbl_employees WHERE branch='$branch' AND status='Active'";
				$branchEmpResult = mysqli_query($db, $sqlBranchEmployee);
				$becount = mysqli_fetch_assoc($branchEmpResult)['becnt'];
				return $becount;
			}
		}
		if($table == 'users')
		{
			if($type == 'main')
			{
				$sqlMainUser = "SELECT COUNT(id) as mainusercnt FROM tbl_system_user  WHERE branch='$branch'";
				$MainUsrResult = mysqli_query($conn, $sqlMainUser);
				$mucount = mysqli_fetch_assoc($MainUsrResult)['mainusercnt'];
				return $mucount;
			}
			if($type == 'branch')
			{
				$sqlBranchEmployee = "SELECT COUNT(id) as branchusercnt FROM tbl_system_user WHERE branch='$branch' AND level<80";
				$branchEmpResult = mysqli_query($db, $sqlBranchEmployee);
				$bucount = mysqli_fetch_assoc($branchEmpResult)['branchusercnt'];
				return $bucount;
			}
		}
		if($table == 'items')
		{
			if($type == 'main')
			{
				$sqlMainFGTS = "SELECT COUNT(id) as mecnt FROM store_items";
				$MainFGTSResult = mysqli_query($conn, $sqlMainFGTS);
				$mainBCount = mysqli_fetch_assoc($MainFGTSResult)['mecnt'];
				return $mainBCount;
			}
			if($type == 'branch')
			{
				$sqlBranchItems = "SELECT COUNT(id) as itemcnt FROM store_items";
				$branchItemsResult = mysqli_query($db, $sqlBranchItems);
				$mainItemCount = mysqli_fetch_assoc($branchItemsResult)['itemcnt'];
				return $mainItemCount;
			}
		}			
	}
	public function ProductionDailyTotalAmount($branch,$report_date,$items,$db) // PSA CLASS
	{
		$query ="SELECT * FROM store_fgts_data WHERE branch='$branch' AND report_date='$report_date' AND item_name='$items'";  
		$result = mysqli_query($db, $query); 
		$val = 0; 
		if($result->num_rows > 0)
		{
		while($ROW = mysqli_fetch_array($result))  
			{
				$kilo_used = $ROW['kilo_used'];
				$val += $kilo_used;
			}
			return $val;
		}
		else{
			return $val;
		}
	}
	public function GetSummary($rowid,$db)
	{
		$query ="SELECT * FROM store_dum_data WHERE sid='$rowid'";
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{
			while($rows = mysqli_fetch_array($result))  
			{
			 	return $rows['sid'];
			}
		} else {
			return 0;
		}
	}
	public function CalculateRMSummary($rowid,$sub_total,$grand_total,$difference,$total_amount,$branch_name,$branch_date,$branch_shift,$db)
	{
		$query = "UPDATE store_rm_summary_data SET sub_total='$sub_total',total='$grand_total',difference='$difference',amount='$total_amount'
		WHERE branch='$branch_name' AND report_date='$branch_date' AND shift='$branch_shift' AND id='$rowid'";
		if ($db->query($query) === TRUE) { } else { echo $db->error; exit(); }
	}
	public function ThemeColor($color)
	{
		$option = '<option value="">--- SELECT COLOR ---</option>';
		$includes = array(
			"Green Theme" => 0,
			"Orange Theme" => "1"
		);				
		foreach ( $includes as $key => $value )
		{
			$chosen = '';
			if($value == $color)		
			{
				$chosen = 'selected';
			}
			echo '<option '.$chosen.' value="'.$value.'">'.$key.'</option>';
		}
	}
	public function GetDaysNum($month,$year)
	{
		return cal_days_in_month(CAL_GREGORIAN,$month,$year);
	}
	public function GetMonths($months)
	{		
		$option = '<option value="">--- SELECT ---</option>';
		$includes = array(
			"January" => "January",
			"February" => "February",
			"March" => "March",
			"April" => "April",
			"May" => "May",
			"June" => "June",
			"July" => "July",
			"August" => "August",
			"September" => "September",
			"October" => "October",
			"November" => "November",
			"December" => "December",
		);				
		foreach ( $includes as $key => $value )
		{
			$chosen = '';
			if($value == $months)		
			{
				$chosen = 'selected';
			}
			echo '<option '.$chosen.' value="'.$value.'">'.$key.'</option>';
		}
	}
	public function GetMonthNumber($months)
	{	
		if($months == "January") { return '1'; }
		else if($months == "February") { return '2'; }
		else if($months == "March") { return '3'; }
		else if($months == "April") { return '4'; }
		else if($months == "May") { return '5'; }
		else if($months == "June") { return '6'; }
		else if($months == "July") { return '7'; }
		else if($months == "August") { return '8'; }
		else if($months == "September") { return '9'; }
		else if($months == "October") { return 10; }
		else if($months == "November") { return 11; }
		else if($months == "December") { return 12; }
		else { return 0; }
	}
	public function BeginningToSummary($tbl,$actual_count,$item_id,$branch_name,$branch_date,$shift,$db)
    {
		$query = "UPDATE $tbl SET beginning='$actual_count' WHERE branch='$branch_name' AND report_date='$branch_date' AND item_id='$item_id'";
		if ($db->query($query) === TRUE) { } else { echo $db->error; exit(); }
	}
	public function GetDiscount($branch_name,$branch_date,$db)
	{
		$query ="SELECT *  FROM store_discount_data WHERE branch='$branch_name' AND report_date='$branch_date' AND posted='Posted'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{			
			while($ROW = mysqli_fetch_array($result))
			{
				return $ROW['discount'];
			}
		} else {
			return 0;
		}
	}
	public function GetBeginning($kwiri,$item_id,$shift,$shifting,$to_shift,$to_shift_date,$transdate,$tbl,$db)
	{
		if($shifting == 2)
		{
			if($shift == 'FIRST SHIFT')
			{
				$tdate = date('Y-m-d', strtotime($transdate. '-1 day'));
				$q = "AND shift='SECOND SHIFT' AND to_shift='FIRST SHIFT' AND to_shift_date='$transdate' AND from_shift='$tdate'";	
			}
			else if($shift == 'SECOND SHIFT')
			{
				$q = "AND shift='FIRST SHIFT' AND to_shift='SECOND SHIFT' AND to_shift_date='$transdate' AND from_shift='$transdate'";	
			} 
			else {
				$q='0';	
			}
		}
		if($shifting == 3)
		{		
			if($shift == 'FIRST SHIFT')
			{
				$tdate = date('Y-m-d', strtotime($transdate. '-1 day'));
				$q = "AND shift='THIRD SHIFT' AND to_shift='FIRST SHIFT' AND to_shift_date='$transdate' AND from_shift='$tdate'";	
			}
			else if($shift == 'SECOND SHIFT')
			{
				$q = "AND shift='FIRST SHIFT' AND to_shift='SECOND SHIFT' AND to_shift_date='$transdate' AND from_shift='$transdate'";	
			} 
			else if($shift == 'THIRD SHIFT')
			{
				$q = "AND shift='SECOND SHIFT' AND to_shift='THIRD SHIFT' AND to_shift_date='$transdate'";	
			}
			else {
				$q='0';	
			}
		}		
		$query ="SELECT *  FROM $tbl WHERE $kwiri $q AND item_id='$item_id'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{			
			while($ROW = mysqli_fetch_array($result))
			{
				return $ROW['actual_count'];
			}
		} else {
			return 0;
		}
	}
	public function GetTotalValue($tbl,$column,$item_id,$branch,$transdate,$shift,$db)
	{
		$query ="SELECT *, SUM($column) as totale FROM $tbl WHERE item_id='$item_id' AND branch='$branch' AND shift='$shift' AND report_date='$transdate'";  
		$result = mysqli_query($db, $query);  
		$value = 0;
		if($result->num_rows > 0) { while($ROW = mysqli_fetch_array($result)) { $value = $ROW['totale']; } return $value;
		} else { return $value; }
	}
	public function setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db)
    {
		$queryInvUpdates = "UPDATE $tbl SET posted='Posted',status='Closed' WHERE branch='$branch' AND  report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		if ($db->query($queryInvUpdates) === TRUE) { echo '<script>console.log("Posted A")</script>'; } else { echo $db->error; exit(); }
	}
	public function setUpdatePostingTOUT($tbl,$branch,$transdate,$shift,$rowid,$db)
    {
		$queryInvUpdates = "UPDATE $tbl SET posted='Posted',status='Closed' WHERE transfer_to='$branch' AND  report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		if ($db->query($queryInvUpdates) === TRUE) { echo '<script>console.log("Posted B")</script>'; } else { echo $db->error; exit(); }
	}
	public function GetMessageExist($message)
	{
		$return = '';
		$return .= '
			<script>
				var message = "'.$message.'";
				swal("Duplicate Warning",message,"warning");
			</script>
		';
		return $return;
	}
	public function CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db)
	{
		if($table == 'store_fgts_data' || $table == 'store_frozendough_data') { $q = " AND inputtime='$time'"; } else { $q=''; }
		$query ="SELECT * FROM $table WHERE branch='$branch' AND report_date='$date' AND shift='$shift' AND item_id='$item_id' $q";  
		return $query;
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0) { return 1; } else { return 0; }
	}
	/* ################################ FGTS REMOTE TRANSFER DELETE ######################################### */
/*	public function iTransferOutdelete(rowid,itemname,branchfrom,branchto,report_date,shift,$conn)
	{
		$query ="SELECT * FROM store_remote_transfer WHERE bid='$rowid' AND branch='$from_branch' AND report_date='$date' AND item_name='$itemname' AND transfer_type='$form_type'";  
		$result = mysqli_query($conn, $query);
		if($result->num_rows === 0)
		{
		
		}
		else{
		
		}
	}
*/	
	 ################################ FGTS REMOTE TRANSFER ################################################## */	
	public function iTransferOut($table,$form_type,$rowid,$from_branch,$date,$shift,$timecovered,$person,$encoder,$category,$item_id,$itemname,$quantity,$unit_price,$amount,$to_branch,$time_stamp,$conn)
	{
		$query ="SELECT * FROM store_remote_transfer WHERE bid='$rowid' AND branch='$from_branch' AND report_date='$date' AND item_name='$itemname' AND transfer_type='$form_type'";  
		$result = mysqli_query($conn, $query);  
		if($result->num_rows === 0)
		{		
			$queryInsert = "INSERT INTO store_remote_transfer (`bid`,`transfer_type`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,item_id,`item_name`,`quantity`,`unit_price`,`amount`,`transfer_to`,`date_created`)";
			$queryInsert .= "VALUES('$rowid','$form_type','$from_branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$quantity','$unit_price','$amount','$to_branch','$time_stamp')";
			if ($conn->query($queryInsert) === TRUE)
			{		
				echo '<script>console.log("Insert Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		} else {
			$update = "
				bid='$rowid',transfer_type='$form_type',branch='$from_branch',report_date='$date',shift='$shift',time_covered='$timecovered',employee_name='$person',supervisor='$encoder',
				category='$category',item_id='$item_id',item_name='$itemname',quantity='$quantity',unit_price='$unit_price',amount='$amount',transfer_to='$to_branch',date_created='$time_stamp'
			";
			$queryDataUpdate = "UPDATE store_remote_transfer SET $update WHERE bid='$rowid' AND branch='$from_branch' AND report_date='$date' AND item_name='$itemname' AND transfer_type='$form_type'";
			if ($conn->query($queryDataUpdate) === TRUE)
			{
				echo '<script>console.log("Update Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		}
	}
	
	/* ################################ RAWMATS REMOTE TRANSFER ################################################## */
	public function iTransferOutRM($rowid,$branch,$reportdate,$shift,$timecovered,$person,$encoder,$category,$item_id,$itemname,$weight,$units,$to_branch,$time_stamp,$conn)
	{		
		$query ="SELECT * FROM store_remote_rmtransfer WHERE bid='$rowid' AND branch='$branch' AND report_date='$reportdate'AND shift='$shift' AND item_id='$item_id'";  
		$result = mysqli_query($conn, $query);  
		if($result->num_rows === 0)
		{		
			$queryInsert = "INSERT INTO store_remote_rmtransfer (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`)";
			$queryInsert .= "VALUES('$rowid','$branch','$reportdate','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$to_branch','$time_stamp')";
			if ($conn->query($queryInsert) === TRUE)
			{		
				echo '<script>console.log("Insert Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		} else {
		
			$update = "
				time_covered='$timecovered',employee_name='$person',item_id='$item_id',item_name='$itemname',weight='$weight',
				units='$units',transfer_to='$to_branch',date_updated='$time_stamp'
			";
			$queryDataUpdate = "UPDATE store_remote_rmtransfer SET $update WHERE bid='$rowid' AND branch='$branch' AND report_date='$reportdate' AND shift='$shift'";
			if ($conn->query($queryDataUpdate) === TRUE)
			{
				echo '<script>console.log("Update Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		}
		
	}
	public function GetTransferMode($tmode)
	{
		$includes = array("TRANSFER OUT" => "TRANSFER OUT","TRANSFER IN" => "TRANSFER IN");
		$option = '';
		foreach ( $includes as $key => $value )
		{
			$chosen = '';
			if($value == $tmode)
			{
				$chosen = 'selected';
			}
			$option .= '<option '.$chosen.' value="'.$value.'">'.$key.'</option>';
		}
		return $option;
	}
	public function GetBranch($branch,$db)
	{		
		$query = "SELECT * FROM tbl_branch ORDER BY branch ASC";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
			$return = '<option value="">--SELECT BRANCH--</option>';
		    while($ROW = mysqli_fetch_array($results))  
			{
				$brench = $ROW['branch'];
				$selected = '';
				if($brench == $branch)
				{
					$selected = 'selected';
				}
				$return .= '<option '.$selected.' value="'.$brench.'">'.$brench.'</option>';
			}
			return $return;
		} else {
			return '<option value="">--NO BRANCH--</option>';;
		}
	}
	public function getSumsummaryBreakdown($category,$branch,$transdate,$shift,$db)
	{	
		$sql = "SELECT * FROM store_summary_data WHERE category='$category' AND branch='$branch' AND report_date='$transdate' AND shift='$shift'";				
		$result = $db->query($sql);
		$sold = 0;$val = 0;$unitprice = 0;$beginning = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {			
				$total=0;$transfer_out=0;$should_be=0;$sold;
				
				$item_id = $row['item_id'];	
				$item_name = $row['item_name'];	
				$beginning = $row['beginning'];
				$stock = $row['stock_in'];
				$transfer_in = $row['t_in'];
				$transfer_out = $row['t_out'];
				$charges = $row['charges'];
				$snacks = $row['snacks'];
				$bad_order = $row['bo'];
				$damaged = $row['damaged'];
				$complimentary = $row['complimentary'];
				$actual_count = $row['actual_count'];
				$frozendough = $row['frozendough'];				
	    	

				$queryInv ="SELECT unit_price FROM store_items WHERE id='$item_id'";  
				$resultInv = mysqli_query($db, $queryInv);  
				while($rows = mysqli_fetch_array($resultInv))  
				{
				 	$unitprice = $rows['unit_price'];
				}
				
				$total += ($beginning + $stock + $transfer_in + $frozendough);
				$should_be = ($total - $transfer_out - $charges - $snacks - $bad_order - $damaged - $complimentary);
				$sold = ($should_be - $actual_count);
								
				$amount =  $sold * $unitprice;				
				$val += $amount;
			}
			return $val;
		} 
		else{
			return $val;
		}		
		
	}
	public function getCashCountTotal($branch,$report_date,$shift,$db){
		$sql = "SELECT SUM(total_amount) AS val FROM store_cashcount_data WHERE branch='$branch' AND report_date='$report_date' AND shift='$shift'";
		$result = mysqli_query($db, $sql);
		$val = 0;
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$val = $row['val'];
			}
		return $val;
		} 
		else {
		  return $val;
		}
	}
	public function getItemPrice($itemid,$db)
	{
		$query ="SELECT *  FROM store_items WHERE id='$itemid'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{
			while($ROW = mysqli_fetch_array($result))  
			{
				return $ROW['unit_price'];
			}
		} else {
			return 0;
		}
	}
	public function getSumfgts($branch,$transdate,$shift,$item_name,$db)
	{
		$sql = "SELECT SUM(actual_yield) AS actual_total FROM store_fgts_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$item_name'";
		$result = mysqli_query($db, $sql);
		$val = 0;
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$val = $row['actual_total'];
			}
		return $val;
		} 
		else {
		  return $val;
		}
	}
	public function GetPid($itemid,$db)
	{
		$query = "SELECT * FROM store_items WHERE id='$itemid'";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{ 
		    while($IDROW = mysqli_fetch_array($results))  
			{
				return $IDROW['id'];
			}
		} else {
			return 0;
		}
	}
	public function CheckData($table,$branch,$report_date,$shift,$db)
	{
		if($table == 'store_transfer_data')
		{
			$q = "branch='$branch' || transfer_to='$branch'";
		} else {
			$q = "branch='$branch'";
		}
		$query ="SELECT * FROM $table WHERE $q AND report_date='$report_date' AND shift='$shift'";  
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{
			return 1;
		} else {
			return 0;
		}
	}
	public function GetEditInfo($table,$rowid,$column,$db)
	{
		$query ="SELECT * FROM $table WHERE id='$rowid'";  
		$result = mysqli_query($db, $query);  
		while($ROWS = mysqli_fetch_array($result))  
		{
			return $ROWS[$column];
		}
	}
	public function GetUnitPrice($params,$db)
	{
		$query ="SELECT product_name, unit_price FROM store_items WHERE product_name='$params'";  
		$result = mysqli_query($db, $query);  
		while($ROWS = mysqli_fetch_array($result))  
		{
			return $ROWS['unit_price'];
		}
	}
	public function GetThemes($params)
	{
		include "themes/".$params.".php";
	}
	public function GetShift($shift)
	{
		$includes = array("FIRST SHIFT" => "FIRST SHIFT","SECOND SHIFT" => "SECOND SHIFT","THIRD SHIFT" => "THIRD SHIFT");
		foreach ( $includes as $key => $value )
		{
			$chosen = '';
			if($value == $shift)
			{
				$chosen = 'selected';
			}
			$option .= '<option '.$chosen.' value="'.$value.'">'.$key.'</option>';
		}
		return $option;
	}
	public function AppBranch()
	{
		foreach(file('../.file/app_branch.conf') as $line) {
			if($line !== '')
			{
				return $line;
			} else {
				return 'Branch not defined';
			}
		} 
	}
	public function GetSession($params)
	{
		if($params == 'appuser')
		{
			return $_SESSION['appstore_user'];
		}
		else if($params == 'branchdate')
		{
			if(isset($_SESSION['session_date']))
			{
				return $_SESSION['session_date'];
			} else {
				return " --- ";
			}
		}
		else if($params == 'cluster')
		{
			return $_SESSION['appstore_cluster'];
		} 
		else if($params == 'branch')
		{
			return $_SESSION['appstore_branch'];
		}
		else if($params == 'shift')
		{
			if(isset($_SESSION['session_shift']))
			{
				return $_SESSION['session_shift'];
			} else {
				return " --- ";
			}
		}
		else if($params == 'shifting')
		{	
			return $_SESSION['appstore_shifting'];
		}
		else if($params == 'encoder')
		{	
			return $_SESSION['appstore_appnameuser'];
		} 
		else if($params == 'idcode')
		{	
			return $_SESSION['appstore_idcode'];
		}
		else if($params == 'userlevel')
		{
			return $_SESSION['appstore_userlevel'];
		} 
		else if($params == 'userrole') 
		{
			return $_SESSION['appstore_userrole'];
		}
		else if($params == 'transfermode') 
		{
			return $_SESSION['session_transfer'];
		} else {
			return '';
		}
	}
}
class SystemTools {  
	public function GetModuleItems($module)
	{
		if($module == 'fgts')
		{
	        $units = array(            
	            "FGTS" => "store_fgts_data",            
	            "TRANSFER" => "store_transfer_data",
				"CHARGES" => "store_charges_data",
				"SNACKS" => "store_snacks_data",
				"BAD ORDER" => "store_badorder_data",
				"DAMAGE" => "store_damage_data",
				"COMPLIMENTARY" => "store_complimentary_data",
				"RECEIVING" => "store_receiving_data",
				"CASH COUNT" => "store_cashcount_data",
				"FROZEN DOUGH" => "store_frozendough_data",
				"PHYSICAL COUNT" => "store_pcount_data",
				"SUMMARY" => "store_summary_data"
	        );
       }
       if($module == 'rawmats')
       {
	        $units = array(            
		        "RECEIVING" => "store_rm_receiving_data",
	            "TRANSFER" => "store_rm_transfer_data",
				"BAD ORDER" => "store_rm_badorder_data",				
				"PHYSICAL COUNT" => "store_rm_pcount_data",
				"SUMMARY" => "store_rm_summary_data"
				
	        );
		}
		$return='';
		foreach ( $units as $key => $value )
		{
		    $return .= '<option value="'.$value.'">'.$key.'</option>';                        
		}
		return $return;
	}
}
function includeItemPrice()
{
	$queryInv ="SELECT unit_price FROM store_items WHERE id='$item_id'";  
	$resultInv = mysqli_query($db, $queryInv);  
	while($rows = mysqli_fetch_array($resultInv))  
	{
	 	$unitprice = $rows['unit_price'];
	}
}