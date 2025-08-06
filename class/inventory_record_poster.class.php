<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class inventoryRecords
{
	public function fgts($branch,$transdate,$shift,$db){
		
		$query = "SELECT * FROM store_fgts_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				$actual_yield = $this->getSumValueFromTables('store_fgts_data','actual_yield',$itemid,$branch,$transdate,$shift,$db);
				
				$time_covered = $ROWS['time_covered'];
				$slip_number = $ROWS['slip_no'];
				$supervisor = $ROWS['supervisor'];
				
				$inputtime = $ROWS['inputtime'];
				
				$kilo_used = $this->getSumValueFromTables('store_fgts_data','kilo_used',$itemid,$branch,$transdate,$shift,$db);
				
				
							
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_summary_data SET stock_in='$actual_yield', kilo_used='$kilo_used' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_fgts_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_summary_data (branch,report_date,shift,time_covered,supervisor,inputtime,category,item_name,item_id,kilo_used,stock_in)
					VALUES ('$branch','$transdate','$shift','$time_covered','$supervisor','$inputtime','$category','$item_name','$itemid','$kilo_used','$actual_yield')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePosting('store_fgts_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}

		} else {
			echo $db->error;
		}
	}
	public function receiving($branch,$transdate,$shift,$db){	
	
		$query = "SELECT * FROM store_receiving_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				
				$time_covered = $ROWS['time_covered'];
				$employee_name = $ROWS['employee_name'];
				$supervisor = $ROWS['supervisor'];
				$remarks = $ROWS['remarks'];
				$category = $ROWS['category'];
				$item_name = $ROWS['item_name'];				
				$itemid = $ROWS['item_id'];
				$quantity = $this->getSumValueFromTables('store_receiving_data','quantity',$itemid,$branch,$transdate,$shift,$db);
				$units = $ROWS['units'];				
				
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$fgtsandreceivingtotal = $quantity + $this->getSumValueFromTables('store_fgts_data','actual_yield',$itemid,$branch,$transdate,$shift,$db);
				
					$queryDataUpdates = "UPDATE store_summary_data SET stock_in='$fgtsandreceivingtotal' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_receiving_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_summary_data (branch,report_date,shift,time_covered,category,item_name,item_id,stock_in)
					VALUES ('$branch','$transdate','$shift','$time_covered','$category','$item_name','$itemid','$quantity')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePosting('store_receiving_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}

		} else {
			echo $db->error;
		}
	}
	public function frozendough($branch,$transdate,$shift,$db){	
		
		$query = "SELECT * FROM store_frozendough_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				$actual_yield = $this->getSumValueFromTables('store_frozendough_data','actual_yield',$itemid,$branch,$transdate,$shift,$db);
				$employee_name = $ROWS['employee_name'];
											
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_summary_data SET frozendough='$actual_yield' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_frozendough_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_summary_data (branch,report_date,shift,supervisor,category,item_name,item_id,frozendough)
					VALUES ('$branch','$transdate','$shift','$employee_name','$category','$item_name','$itemid','$actual_yield')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePosting('store_frozendough_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}

		} else {
			echo $db->error;
		}	
	}

	public function transferin($branch,$transdate,$shift,$db){
		
		$query = "SELECT * FROM store_transfer_data WHERE transfer_to='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				$quantity = $this->getSumValueFromTablesTransferin('store_transfer_data','quantity',$itemid,$branch,$transdate,$shift,$db);
				$employee_name = $ROWS['employee_name'];
											
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_summary_data SET t_in='$quantity' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePostingTransferin('store_transfer_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_summary_data (branch,report_date,shift,supervisor,category,item_name,item_id,t_in)
					VALUES ('$branch','$transdate','$shift','$employee_name','$category','$item_name','$itemid','$quantity')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePostingTransferin('store_transfer_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}

		} else {
			echo $db->error;
		}
	}

	public function transferout($branch,$transdate,$shift,$db){
		
		$query = "SELECT * FROM store_transfer_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				$quantity = $this->getSumValueFromTables('store_transfer_data','quantity',$itemid,$branch,$transdate,$shift,$db);
				$employee_name = $ROWS['employee_name'];
											
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_summary_data SET t_out='$quantity' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_transfer_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_summary_data (branch,report_date,shift,supervisor,category,item_name,item_id,t_out)
					VALUES ('$branch','$transdate','$shift','$employee_name','$category','$item_name','$itemid','$quantity')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePosting('store_transfer_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}

		} else {
			echo $db->error;
		}
	}
	
	public function charges($branch,$transdate,$shift,$db){
		
		$query = "SELECT * FROM store_charges_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				$quantity = $this->getSumValueFromTables('store_charges_data','quantity',$itemid,$branch,$transdate,$shift,$db);
				$employee_name = $ROWS['employee_name'];
											
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_summary_data SET charges='$quantity' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_charges_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_summary_data (branch,report_date,shift,supervisor,category,item_name,item_id,charges)
					VALUES ('$branch','$transdate','$shift','$employee_name','$category','$item_name','$itemid','$quantity')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePosting('store_charges_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}

		} else {
			echo $db->error;
		}
	}
	public function badorder($branch,$transdate,$shift,$db){
	
		$query = "SELECT * FROM store_badorder_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				$quantity = $this->getSumValueFromTables('store_badorder_data','quantity',$itemid,$branch,$transdate,$shift,$db);
				$employee_name = $ROWS['employee_name'];
											
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_summary_data SET bo='$quantity' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_badorder_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_summary_data (branch,report_date,shift,supervisor,category,item_name,item_id,bo)
					VALUES ('$branch','$transdate','$shift','$employee_name','$category','$item_name','$itemid','$quantity')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePosting('store_badorder_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}

		} else {
			echo $db->error;
		}
	}
	public function damage($branch,$transdate,$shift,$db){
	
		$query = "SELECT * FROM store_damage_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				$quantity = $this->getSumValueFromTables('store_damage_data','quantity',$itemid,$branch,$transdate,$shift,$db);
				$employee_name = $ROWS['employee_name'];
											
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_summary_data SET damaged='$quantity' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_damage_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_summary_data (branch,report_date,shift,supervisor,category,item_name,item_id,damaged)
					VALUES ('$branch','$transdate','$shift','$employee_name','$category','$item_name','$itemid','$quantity')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePosting('store_damage_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}
		
		} else {
			echo $db->error;
		}
	}

	public function pcount($branch,$transdate,$shift,$db){
	
		$query = "SELECT * FROM store_pcount_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				$employee_name = $ROWS['employee_name'];
				$actual_count = $this->getSumValueFromTables('store_pcount_data','actual_count',$itemid,$branch,$transdate,$shift,$db);
				
				$beginning = $this->getSumValueFromTables('store_summary_data','beginning',$itemid,$branch,$transdate,$shift,$db);
				$stock_in = $this->getSumValueFromTables('store_summary_data','stock_in',$itemid,$branch,$transdate,$shift,$db);
				$t_in = $this->getSumValueFromTablesTransferin('store_transfer_data','quantity',$itemid,$branch,$transdate,$shift,$db);
				$frozendough = $this->getSumValueFromTables('store_summary_data','frozendough',$itemid,$branch,$transdate,$shift,$db);
				
				$bo = $this->getSumValueFromTables('store_badorder_data','quantity',$itemid,$branch,$transdate,$shift,$db);
				$charges = $this->getSumValueFromTables('store_charges_data','quantity',$itemid,$branch,$transdate,$shift,$db);
				$t_out = $this->getSumValueFromTables('store_transfer_data','quantity',$itemid,$branch,$transdate,$shift,$db);
				$damage = $this->getSumValueFromTables('store_damage_data','quantity',$itemid,$branch,$transdate,$shift,$db);

				$total = $beginning + $stock_in + $t_in + $frozendough;
				$shouldbe = $total - $t_out - $charges  - $bo - $damage;
				$sold = $shouldbe == 0 ? 0: $shouldbe - $actual_count;
				$unitprice = $this->getItemPrice($itemid,$db);
				$amount = $sold * $unitprice;
								
				$data = [
			        'itemid' => $itemid,
			        'item_name' => $item_name,
			        'category' => $category,
			        'actual_count' => $actual_count,
			        'employee_name' => $employee_name
			    ];				
			
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_summary_data SET actual_count='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_pcount_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_summary_data (branch,report_date,shift,supervisor,category,item_name,item_id,actual_count)
					VALUES ('$branch','$transdate','$shift','$employee_name','$category','$item_name','$itemid','$actual_count')";
					if ($db->query($queryDataInsert) === TRUE) {  
						$this->setUpdatePosting('store_pcount_data',$branch,$transdate,$shift,$rowid,$db); 
					} else { 
						echo $db->error; 
					}			
				}
				
				$myShifting = $_SESSION['appstore_shifting'];
				$shiftnow = $shift;
				$shiftforward = $myShifting == 2? $this->twoShiftingShiftGet($shift):$this->threeShiftingShiftGet($shift);
				$transdatenow = $transdate;
				$transdateforward = $myShifting == 2? $this->twoShiftingTransDateGet($shift,$transdate): $this->threeShiftingTransDateGet($shift,$transdate);

				$this->insertThis($data,$branch,$transdateforward,$shiftforward,$db);
				
			}
			
		} else {
			echo $db->error;
		}
	}

	public function summary($branch,$transdate,$shift,$db){
	
		$query = "SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];


				$this->summarytopcountcheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1'? $actual_count = $ROWS['actual_count']: $actual_count = 0;
				
				
				$beginning = $ROWS['beginning'];
				$stock_in = $ROWS['stock_in'];
				$t_in = $ROWS['t_in'];
				$frozendough = $ROWS['frozendough'];
				
				$bo = $ROWS['bo'];
				$charges = $ROWS['charges'];
				$t_out = $ROWS['t_out'];
				$damage = $ROWS['damaged'];
				
				$total = $beginning + $stock_in + $t_in + $frozendough;
				$shouldbe = $total - $t_out - $charges  - $bo - $damage;
				$sold = $shouldbe == 0 ? 0: $shouldbe - $actual_count;
				$unitprice = $this->getItemPrice($itemid,$db);
				$amount = $sold * $unitprice;
								
			
				$queryDataUpdates = "UPDATE store_summary_data SET total='$total',should_be='$shouldbe',sold='$sold',unit_price='$unitprice',amount='$amount',posted='Posted',status='Closed' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
				if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_pcount_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
			}
			
		} else {
			echo $db->error;
		}
		print_r('<script>app_alert("System Message","Reports posted to summary","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
	}
	
	
	public function insertThis($data, $branch, $transdate, $shift, $db) {
    
	    if (is_array($data) && isset($data['itemid'])) {
	        $itemid = $data['itemid'];
	        $category = $data['category'];
	        $item_name = $data['item_name'];
	        $actual_count = $data['actual_count'];
	        $employee_name = $data['employee_name'];

	        if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db)==1){
	        
	        	$queryDataUpdates = "UPDATE store_summary_data SET beginning='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
				if($db->query($queryDataUpdates) === TRUE) { } else { echo $db->error; }
	        
	        } else {
	        	
	        	$queryDataInsert = "INSERT INTO store_summary_data (branch,report_date,shift,supervisor,category,item_name,item_id,beginning)
				VALUES ('$branch','$transdate','$shift','$employee_name','$category','$item_name','$itemid','$actual_count')";
				if ($db->query($queryDataInsert) === TRUE) { } else { echo $db->error; }
	        }
	        
	    } else {
	        echo "Invalid data format";
	    }
    }
	
	public function summarytopcountcheckerifexist($itemid,$branch,$transdate,$shift,$db){
		
		$sql = "SELECT * FROM store_pcount_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
	    $result = $db->query($sql);
	    if ($result->num_rows > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function summarycheckerifexist($itemid,$branch,$transdate,$shift,$db){
		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
	    $result = $db->query($sql);
	    if ($result->num_rows > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	public function setUpdatePosting($tbl,$branch,$transdate,$shift,$rowid,$db)
    {    
		$queryInvUpdates = "UPDATE $tbl SET posted='Posted',status='Closed' WHERE branch='$branch' AND  report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		if ($db->query($queryInvUpdates) === TRUE) { } else { }
	}
	
	public function setUpdatePostingTransferin($tbl,$branch,$transdate,$shift,$rowid,$db) // for transferin only
    {    
		$queryInvUpdates = "UPDATE $tbl SET posted='Posted',status='Closed' WHERE transfer_to='$branch' AND  report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		if ($db->query($queryInvUpdates) === TRUE) { } else { }
	}
	
	public function getSumValueFromTables($table,$tableColumn,$itemid,$branch,$transdate,$shift,$db)
	{
		$sql = "SELECT SUM($tableColumn) AS totalvalue FROM $table WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
		$result = mysqli_query($db, $sql);
		$val = 0;
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$val = $row['totalvalue'];
			}
		return $val;
		} 
		else {
		  return $val;
		}
	}

	public function getSumValueFromTablesTransferin($table,$tableColumn,$itemid,$branch,$transdate,$shift,$db) // For transfer in only
	{
		$sql = "SELECT SUM($tableColumn) AS totalvalue FROM $table WHERE transfer_to='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
		$result = mysqli_query($db, $sql);
		$val = 0;
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$val = $row['totalvalue'];
			}
		return $val;
		} 
		else {
		  return $val;
		}
	}
	
	public function threeShiftingShiftGet($shift)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$val3 = 'THIRD SHIFT';
		
		if ($shift == $val1) {
		    $shift = $val2;
		}
		elseif($shift == $val2){
			$shift = $val3;
		}
		else {
		    $shift = $val1;
		}
		return $shift;
    }

	public function twoShiftingShiftGet($shift)
	{
	    $val1 = 'FIRST SHIFT';
	    $val2 = 'SECOND SHIFT';
	
	    if ($shift == $val1) 
	    {
	        $shift = $val2;
	    } 
	    else 
	    {
	        $shift = $val1;
	    }
	    return $shift;   
	}
	public function threeShiftingTransDateGet($shift,$transdate)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$val3 = 'THIRD SHIFT';
		$date = new DateTime($transdate);
		
		if ($shift == $val3) {
		    $date->modify('+1 day');
		    $transdate = $date->format('Y-m-d');
		}
		return $transdate;
    }
	public function twoShiftingTransDateGet($shift,$transdate)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$date = new DateTime($transdate);
		
		if ($shift == $val2) {
		    $date->modify('+1 day');
		    $transdate = $date->format('Y-m-d');
		}
		return $transdate;
    }
    public function getTableValue($table,$column,$itemid,$branch,$transdate,$shift,$db)
	{
		$tbl = "store_".$table."_data";
		$query = "SELECT SUM($column) AS value FROM $tbl WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
	    	$value = 0;
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$value = $ROWS['value'];
			}
			return $value;
		} else {
			return 0;
		}
	}
	
	public function getItemPrice($itemid,$db)
	{
		$query ="SELECT * FROM store_items WHERE id='$itemid'";  
		$result = mysqli_query($db, $query); 
		if ($result->num_rows > 0) {
			$unit_price = 0;
			while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$actual_yield = $ROWS['yield_per_kilo'];
				$standard_yield = $ROWS['yield_per_kilo'];
				$unit_price = $ROWS['unit_price'];
				$unit_price_new = $ROWS['unit_price_new'];
				$effectivity_date = $ROWS['effectivity_date'];
				$session_date = $_SESSION['session_date'];		
				$functions = new TheFunctions;
				
				if($session_date >= $effectivity_date)
				{
					$this->validateDateWithFormat($effectivity_date) ? $unit_price = $ROWS['unit_price_new']:$unit_price = $ROWS['unit_price'];
				}
				else{
					$unit_price = $ROWS['unit_price'];
				}
			}
			return $unit_price;
			
		}
		else
		{
			return 0;
		}
	}
	public function validateDateWithFormat($date, $format = 'Y-m-d')
	{
	   $d = DateTime::createFromFormat($format, $date);
	   $errors = DateTime::getLastErrors();
	 
	   return $d && empty($errors['warning_count']) && $d->format($format) == $date;
	      
	}


}

