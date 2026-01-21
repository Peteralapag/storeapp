<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class inventoryRecords
{
	
	public function rm_receiving($branch,$transdate,$shift,$db){	
	
		$query = "SELECT id, time_covered, employee_name, supervisor, remarks, category, item_name, item_id, quantity, units FROM store_rm_receiving_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
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
				$quantity = $this->getSumValueFromTables('store_rm_receiving_data','quantity',$itemid,$branch,$transdate,$shift,$db);
				$units = $ROWS['units'];				
				
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{				
					$queryDataUpdates = "UPDATE store_rm_summary_data SET stock_in='$quantity' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_rm_receiving_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_rm_summary_data (branch,report_date,shift,time_covered,category,item_name,item_id,stock_in)
					VALUES ('$branch','$transdate','$shift','$time_covered','$category','$item_name','$itemid','$quantity')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePosting('store_rm_receiving_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}

		} else {
			echo $db->error;
		}
	}
	

	public function rm_transferin($branch,$transdate,$shift,$db){
		
		$query = "SELECT id, item_id, item_name, category, employee_name, weight  FROM store_rm_transfer_data WHERE branch='$branch' AND transfer_to='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				$weight = $this->getSumValueFromTablesTransferin('store_rm_transfer_data','weight',$itemid,$branch,$transdate,$shift,$db);
											
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_rm_summary_data SET transfer_in='$weight' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePostingTransferin('store_rm_transfer_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_rm_summary_data (branch,report_date,shift,category,item_name,item_id,transfer_in)
					VALUES ('$branch','$transdate','$shift','$category','$item_name','$itemid','$weight')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePostingTransferin('store_rm_transfer_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}

		} else {
			echo $db->error;
		}
	}

	public function rm_transferout($branch,$transdate,$shift,$db){
		
		$query = "SELECT id, item_id, item_name, category, employee_name, weight FROM store_rm_transfer_data WHERE branch='$branch' AND transfer_from='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				$weight = $this->getSumValueFromTablesTransferout('store_rm_transfer_data','weight',$itemid,$branch,$transdate,$shift,$db);
											
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_rm_summary_data SET transfer_out='$weight' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_rm_transfer_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_rm_summary_data (branch,report_date,shift,category,item_name,item_id,transfer_out)
					VALUES ('$branch','$transdate','$shift','$category','$item_name','$itemid','$weight')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePosting('store_rm_transfer_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}

		} else {
			echo $db->error;
		}
	}
	
	
	public function rm_badorder($branch,$transdate,$shift,$db){
	
		$query = "SELECT id, item_id, item_name, category, actual_count, employee_name FROM store_rm_badorder_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				$actual_count = $this->getSumValueFromTables('store_rm_badorder_data','actual_count',$itemid,$branch,$transdate,$shift,$db);
											
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_rm_summary_data SET bo='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_rm_badorder_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_rm_summary_data (branch,report_date,shift,category,item_name,item_id,bo)
					VALUES ('$branch','$transdate','$shift','$category','$item_name','$itemid','$actual_count')";
					if ($db->query($queryDataInsert) === TRUE) {  $this->setUpdatePosting('store_rm_badorder_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }			
				}
			}

		} else {
			echo $db->error;
		}
	}
	

	
	
	public function rm_pcount($branch,$transdate,$shift,$db){
	
		$query = "SELECT id, item_id, item_name, category, actual_count FROM store_rm_pcount_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$itemid = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				$category = $ROWS['category'];
				
				$actual_count = $this->getSumValueFromTables('store_rm_pcount_data','actual_count',$itemid,$branch,$transdate,$shift,$db);
				
				$data = [
			        'itemid' => $itemid,
			        'item_name' => $item_name,
			        'category' => $category,
			        'actual_count' => $actual_count			    ];
			
				if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db) == '1')
				{
					$queryDataUpdates = "UPDATE store_rm_summary_data SET actual_count='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
					if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_rm_pcount_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
					
				} else {

					$queryDataInsert = "INSERT INTO store_rm_summary_data (branch,report_date,shift,category,item_name,item_id,actual_count)
					VALUES ('$branch','$transdate','$shift','$category','$item_name','$itemid','$actual_count')";
					if ($db->query($queryDataInsert) === TRUE) {  
						$this->setUpdatePosting('store_rm_pcount_data',$branch,$transdate,$shift,$rowid,$db); 
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
	
	public function rm_summary($branch,$transdate,$shift,$db){
	
		$query = "SELECT * FROM store_rm_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
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
				$t_in = $ROWS['transfer_in'];
				$cout = $ROWS['counter_out'];
				$bo = $ROWS['bo'];
				$t_out = $ROWS['transfer_out'];
				$actualusage = $this->getRMBuildAssemblyTotal($item_name, $itemid, $branch, $transdate, $shift, $db);
				$unitprice = $this->getItemPrice($itemid,$db);
				
				$actualcount = $ROWS['actual_count'];								
				
				$subtotal = $beginning + $stock_in + $t_in + $cout;
				$grandtotal = $subtotal - $t_out - $bo - $actualusage;
				
				$difference = $actualcount - $grandtotal;				
				$totalamount = $difference * $unitprice;
				
				$variances = $difference * $unitprice;
												
				$queryDataUpdates = "UPDATE store_rm_summary_data SET sub_total='$subtotal',total='$grandtotal',difference='$difference',price_kg='$unitprice',amount='$totalamount',actual_usage='$actualusage',variances='$variances',posted='Posted',status='Closed' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
				if($db->query($queryDataUpdates) === TRUE) { $this->setUpdatePosting('store_rm_summary_data',$branch,$transdate,$shift,$rowid,$db); } else { echo $db->error; }
			}
			
		} else {
			echo $db->error;
		}
//		print_r('<script>app_alert("System Message","Reports posted to summary","success","Ok","","");$("#" + sessionStorage.navcount).click();</script>');
	}
	

	
	
	public function insertThis($data, $branch, $transdate, $shift, $db) {
    
	    if (is_array($data) && isset($data['itemid'])) {
	        $itemid = $data['itemid'];
	        $category = $data['category'];
	        $item_name = $data['item_name'];
	        $actual_count = $data['actual_count'];

	        if($this->summarycheckerifexist($itemid,$branch,$transdate,$shift,$db)==1){
	        
	        	$queryDataUpdates = "UPDATE store_rm_summary_data SET beginning='$actual_count' WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
				if($db->query($queryDataUpdates) === TRUE) { } else { echo $db->error; }
	        
	        } else {
	        	
	        	$queryDataInsert = "INSERT INTO store_rm_summary_data (branch,report_date,shift,category,item_name,item_id,beginning)
				VALUES ('$branch','$transdate','$shift','$category','$item_name','$itemid','$actual_count')";
				if ($db->query($queryDataInsert) === TRUE) { } else { echo $db->error; }
	        }
	        
	    } else {
	        echo "Invalid data format";
	    }
    }
	
	public function summarytopcountcheckerifexist($itemid,$branch,$transdate,$shift,$db){
		
		$sql = "SELECT * FROM store_rm_pcount_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
	    $result = $db->query($sql);
	    if ($result->num_rows > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function summarycheckerifexist($itemid,$branch,$transdate,$shift,$db){
		
		$sql = "SELECT * FROM store_rm_summary_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
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
		$sql = "SELECT SUM($tableColumn) AS totalvalue FROM $table WHERE branch='$branch' AND transfer_to='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
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
	
	public function getSumValueFromTablesTransferout($table,$tableColumn,$itemid,$branch,$transdate,$shift,$db) // For transfer in only
	{
		$sql = "SELECT SUM($tableColumn) AS totalvalue FROM $table WHERE branch='$branch' AND transfer_from='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function getRMBuildAssemblyTotal($rmName, $rm_item_id, $branch, $report_date, $shift, $db)
	{
	    $totalUsage = 0;
	

	    $totalUsage += $this->getRMBuildAssemblyTotalgenerated(
	        $rmName, 
	        $rm_item_id, 
	        $branch, 
	        $report_date, 
	        $shift, 
	        $db
	    );
	

	    // Now get merge items
	    $stmt = $db->prepare("SELECT merge_item_id FROM store_merge_items WHERE primary_item_id = ?");
	    $stmt->bind_param("s", $rm_item_id);
	    $stmt->execute();
	    $res = $stmt->get_result();
	
	    while ($row = $res->fetch_assoc()) {
	        $mergeId = $row['merge_item_id'];
	
	        
	        
	        $mergeRmName = $db->query("SELECT product_name FROM store_items WHERE id = $mergeId")
			                  ->fetch_assoc()['product_name'] ?? $rmName;
			
			$totalUsage += $this->getRMBuildAssemblyTotalgenerated(
			    $mergeRmName,
			    $mergeId,
			    $branch, 
			    $report_date, 
			    $shift, 
			    $db
			);
	    }

	    return $totalUsage;
	}
	
	public function getRMBuildAssemblyTotalgenerated($rmName, $rm_item_id, $branch, $report_date, $shift, $db)
	{
	
		
	
	    $sql = "
	        SELECT 
	            SUM(bg.unit_in_grams * (s.stock_in / NULLIF(i.yield_per_kilo,0))) AS total_usage
	        FROM store_summary_data s
	        LEFT JOIN store_items i ON s.item_id = i.id
	        LEFT JOIN store_bakers_guide bg ON bg.itemcode = s.item_id AND bg.rawmats_name = ?
	        WHERE s.branch = ?
	          AND s.report_date = ?
	          AND s.stock_in > 0
	    ";
		
	    $types = "sss";
	    $params = [$rmName, $branch, $report_date];
	
	    
		if (!empty($shift)) {

	        if ($shift === "FIRST SHIFT") {
	            $sql .= " AND s.shift = ?";
	            $types .= "s";
	            $params[] = "FIRST SHIFT";
	        }
	        else if ($shift === "SECOND SHIFT") {
	            $sql .= " AND (s.shift = ? OR s.shift = ?)";
	            $types .= "ss";
	            $params[] = "SECOND SHIFT";
	            $params[] = "THIRD SHIFT";
	        }
	        else if ($shift === "THIRD SHIFT") {
	            $sql .= " AND s.shift = ?";
	            $types .= "s";
	            $params[] = "THIRD SHIFT";
	        }
	    }	    

	
	
	    $stmt2 = $db->prepare($sql);
	    if ($stmt2 === false) {
	        // Log SQL error for debugging
	        error_log("getRMBuildAssemblyTotal prepare failed (usage sql): " . $db->error . " -- SQL: " . $sql);
	        return 0;
	    }
	
	    // bind params dynamically
	    $bind_names[] = $types;
	    for ($i = 0; $i < count($params); $i++) {
	        $bind_name = 'param' . $i;
	        $$bind_name = $params[$i];
	        $bind_names[] = &$$bind_name; // note: bind_param requires references
	    }
	    call_user_func_array([$stmt2, 'bind_param'], $bind_names);
	
	    $stmt2->execute();
	    $res2 = $stmt2->get_result();
	    $data = $res2 ? $res2->fetch_assoc() : null;
	    $stmt2->close();
	
	    return floatval($data['total_usage'] ?? 0);
	}





}

