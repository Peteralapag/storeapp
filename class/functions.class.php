<?php
class TheFunctions
{	
	
	public function checkTransferInPosted($branch,$reportDate,$shift,$db)
    {
		echo $sql = "SELECT * FROM store_transfer_data WHERE report_date='$reportDate' AND shift='$shift' AND transfer_to='$branch' AND posted='No' AND status='Open' LIMIT 1";
	    $result = $db->query($sql);
	    if ($result->num_rows > 0) {
			return 1;
		}
		else{
			return 0;
		}
	}

	
	public function checkItemNamecategorydiscountonly($itemname,$category,$db){
		
        $sql = "SELECT * FROM store_items WHERE product_name='$itemname' AND category_name='$category'";
	    $result = $db->query($sql);
	    if ($result->num_rows > 0) {
			return 1;
		}
		else{
			return 0;
		}
	}


	public function insertNavigationMenus($db)
	{
	    
	    
		$menus = [
	        [0, 100000, 'Overhead', 'overheadcount', 'fa-solid fa-users', 'text-primary', null, 1],
	        [1, 100000, 'Expense Input', 'expenses_input', 'fa-solid fa-table', 'text-primary', null, 1],
	        [0, 110000, 'BRRR Summary', 'brrr_summary', 'fa-solid fa-table', 'text-primary', null, 1],
	        [1, 110000, 'BRRR Submit To Server', 'brrr_submitserver', 'fa-solid fa-cloud', 'text-primary', null, 1],
	    ];
	
	    $checkSql = "SELECT id FROM store_navigation WHERE page_name = ?";
	    $insertSql = "INSERT INTO store_navigation 
	        (sorting, category_id, menu_name, page_name, display_icon, icon_color, table_columns, active) 
	        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
	
	    $checkStmt = $db->prepare($checkSql);
	    $insertStmt = $db->prepare($insertSql);
	
	    foreach ($menus as $menu) {
	        $pageName = $menu[3];
	        $checkStmt->bind_param("s", $pageName);
	        $checkStmt->execute();
	        $result = $checkStmt->get_result();
	
	        if ($result->num_rows == 0) {

	            $insertStmt->bind_param("iisssssi", 
	                $menu[0], $menu[1], $menu[2], $menu[3], 
	                $menu[4], $menu[5], $menu[6], $menu[7]
	            );
	            $insertStmt->execute();
	        }
	    }


	}

	
	public function importSQLFile($db, $filePath) {
	
	    if (!file_exists($filePath)) {
//	        echo "SQL file dili makita: $filePath<br>";
	        return false;
	    }
	
	    $sql = file_get_contents($filePath);
	    if (!$sql || trim($sql) == '') {
//	        echo "SQL file way sulod or dili ma basa: $filePath<br>";
	        return false;
	    }
	

	    $queries = array_filter(array_map('trim', explode(";", $sql)));
	
	    foreach ($queries as $query) {
	        if (!empty($query)) {
	            if (!$db->query($query)) {
//	                echo "Query error ni: " . $db->error . "<br>";
//	                echo "Query: <pre>$query</pre><br>";
	                return false;
	            }
	        }
	    }
	
//	    echo "Success imported: $filePath<br>";
	    return true;
	}



	public function checkIfTableExists($db, $tableName) {
	    $sql = "SHOW TABLES LIKE ?";
	    $stmt = $db->prepare($sql);
	    if ($stmt) {
	        $stmt->bind_param("s", $tableName);
	        $stmt->execute();
	        $result = $stmt->get_result();
	        return ($result && $result->num_rows > 0);
	    }
	    return false;
	}
	
	public function addChargesColumns($db)
	{
	    $alter = "
	        ALTER TABLE store_charges_data 
	            ADD COLUMN charges_type VARCHAR(50) AFTER unit_price,
	            ADD COLUMN personal_charges DOUBLE(11,2) NOT NULL DEFAULT 0 AFTER charges_type,
	            ADD COLUMN inventory_charges DOUBLE(11,2) NOT NULL DEFAULT 0 AFTER personal_charges,
	            ADD COLUMN raw_material_charges DOUBLE(11,2) NOT NULL DEFAULT 0 AFTER inventory_charges,
	            ADD COLUMN infraction_charges DOUBLE(11,2) NOT NULL DEFAULT 0 AFTER raw_material_charges,
	            ADD COLUMN other_charges DOUBLE(11,2) NOT NULL DEFAULT 0 AFTER infraction_charges;
	    ";
	
	    if ($db->query($alter)) {
	        return 1;
	    } else {
	        error_log("ALTER TABLE failed: " . $db->error);
	        return 0;
	    }
	}
	public function checkchargestypeexist($db)
	{
	    $sql = "SELECT COLUMN_NAME 
	            FROM INFORMATION_SCHEMA.COLUMNS 
	            WHERE TABLE_NAME = 'store_charges_data' 
	              AND COLUMN_NAME = 'charges_type' 
	            LIMIT 1";
	

	    $stmt = $db->prepare($sql);
	    if ($stmt) {
	        $stmt->execute();
	        $result = $stmt->get_result();
	

	        if ($result && $result->num_rows > 0) {
	            return 1;
	        } else {
	            return 0;
	        }
	    } else {

	        return 0;
	    }
	}
	
	public function getFoodPandaTotal($branch,$report_date,$shift,$db){
		$sql = "SELECT SUM(total) AS val FROM store_foodpanda_data WHERE branch='$branch' AND report_date='$report_date' AND shift='$shift'";
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

	
	
	public function getTableValue($table,$column,$itemname,$branch,$transdate,$shift,$db)
	{
		$tbl = "store_".$table."_data";
		$query = "SELECT $column FROM $tbl WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$itemname'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
	    	$value = 0;
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$value = $ROWS[$column];
			}
			return $value;
		} else {
			return 0;
		}
	}

	
	public function syncActiveEmployeesToHO($sourceConn, $targetConn) {
	
		$targetConn->query("DELETE FROM tbl_employees_ho");
	
	    $query = "SELECT * FROM tbl_employees";
	    $result = $sourceConn->query($query);
	
	    if ($result && $result->num_rows > 0) {
	        while ($row = $result->fetch_assoc()) {
	            $fields = array_keys($row);
	            $columns = implode(",", $fields);
	
	            $values = [];
	            foreach ($fields as $field) {
	                $values[] = "'" . mysqli_real_escape_string($targetConn, $row[$field]) . "'";
	            }
	            $values_str = implode(",", $values);
	
	            $updates = [];
	            foreach ($fields as $field) {
	                $updates[] = "$field=VALUES($field)";
	            }
	            $update_str = implode(",", $updates);
	
	            $sql = "INSERT INTO tbl_employees_ho ($columns) VALUES ($values_str)
	                    ON DUPLICATE KEY UPDATE $update_str";
	
	            $targetConn->query($sql);
	        }
	    }
	}




	public function getFoodPandaBreakdown($category,$branch,$transdate,$shift,$db)
	{	
		$sql = "SELECT * FROM store_foodpanda_data WHERE category='$category' AND branch='$branch' AND report_date='$transdate' AND shift='$shift'";				
		$result = $db->query($sql);
		$val = 0;$unitprice = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {			
				$total=0;$transfer_out=0;$should_be=0;$sold;
				
				$item_id = $row['item_id'];	
				$item_name = $row['item_name'];	
				$quantity = $row['quantity'];
				$unitprice = $row['unit_price'];
												
				$amount =  $quantity * $unitprice;				
				$val += $amount;
			}
			return $val;
		} 
		else{
			return $val;
		}		
		
	}

	public function createTableIfNotExistsrmlocker($tableName, $createSQL, $db) {
	    if (!$this->checkTableExists($tableName, $db)) {
	        $dropQuery = "DROP TABLE IF EXISTS `$tableName`";
	        if ($db->query($dropQuery)) {
	            if ($db->query($createSQL)) {
	                // echo "Table `$tableName` created successfully.<br>";
	            } else {
	                // echo "Error creating `$tableName`: " . $db->error . "<br>";
	            }
	        } else {
	            // echo "Error dropping `$tableName`: " . $db->error . "<br>";
	        }
	    } else {
	        // echo "Table `$tableName` already exists.<br>";
	    }
	}

	public function analystValVer2($branch,$transdate,$sidebar,$conn)
	{
		$table = 'store_datelock_checker';
		if($sidebar=='rawmats'){
			$table = 'store_datelock_checker_rm';
		}
		
		$sql = "SELECT * FROM $table WHERE report_date='$transdate' AND branch='$branch' AND office_execute=1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc())
			{
				return $row['lock_by'];
			}
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
	}


	public function dateLockCheckerRM($branch,$reportDate,$db)
	{
		$sql = "SELECT * FROM store_datelock_checker_rm WHERE report_date='$reportDate' AND branch='$branch' AND office_execute=1";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			return 1;
		} 
		else
		{	  
			return 0; 
		}
		$db->close();
	}


	public function dateLockCheckerUpdateRm($branch, $reportDate, $lockBy, $unlockBy, $db)
    {
        $sql = "UPDATE store_datelock_checker_rm SET branch_execute=1, status='1', lock_by='$lockBy', unlock_by='$unlockBy' WHERE report_date='$reportDate' AND branch='$branch' AND branch_execute=0 AND status=0";
        $result = $db->query($sql);

        if ($result) {
            return 1;
            $db->close();
        } else {
            return 0;
            $db->close();
        }
        $db->close();
    }


	public function dateLockCheckerInsertRm($branch, $reportDate, $lockBy, $unlockBy, $db)
    {
        $sql = "INSERT INTO store_datelock_checker_rm (report_date, branch, branch_execute, office_execute, status, lock_by, unlock_by) VALUES ('$reportDate', '$branch', '1', '1', '1', '$lockBy', '$unlockBy')";
        $result = $db->query($sql);

        if ($result) {
            return 1;
            $db->close();
        } else {
            return 0;
            $db->close();
        }
        $db->close();
    }


	public function GetItemCategoryNew($itemid,$db)
	{
		$query = "SELECT * FROM store_items WHERE id='$itemid'";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{ 
		    while($IDROW = mysqli_fetch_array($results))  
			{
				return $IDROW['category_name'];
			}
		} else {
			return '';
		}
	}

	
	public function checkItemNamecategoryitemid($itemname,$category,$itemid,$db){
		
        $sql = "SELECT * FROM store_items WHERE id='$itemid' AND product_name='$itemname' AND category_name='$category'";
	    $result = $db->query($sql);
	    if ($result->num_rows > 0) {
			return 1;
		}
		else{
			return 0;
		}
	}

	public function insertRecords($queries, $db)
	{
	    foreach ($queries as $query) {
	    	if ($db->query($query)) { } else { }
	    }
	}

	public function createTables($tables, $db)
	{
		foreach ($tables as $query) {
		    if ($db->query($query)) { } else { }
		}
	}
	
	public function dropTables($tables, $db)
	{
	    foreach ($tables as $table) {
	        $query = "DROP TABLE IF EXISTS `$table`";
	        if ($db->query($query)) {} else {}
	    }
	}
	public function checkTableExists($tableName, $db) {
	    $query = "SELECT COUNT(*) AS count FROM INFORMATION_SCHEMA.TABLES 
	              WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '$tableName'";
	    $result = $db->query($query);
	    
	    if ($result) {
	        $row = $result->fetch_assoc();
	        return $row['count'] > 0;
	    } else {
	        return false;
	    }
	}



	public function GetItemIdViaItemName($itemname, $db)
	{
	    $query = "SELECT id FROM store_items WHERE product_name = ?";
	    $stmt = $db->prepare($query);
	    
	    if ($stmt === false) {
	        trigger_error($db->error, E_USER_ERROR);
	        return '';
	    }
	
	    $stmt->bind_param('s', $itemname);
	    $stmt->execute();
	    $stmt->bind_result($id);
	    $stmt->fetch();
	    
	    $stmt->close();
	
	    return $id ?? '';
	}
	
	public function checkItemNameofItemListTable($itemname,$db){
		
        $sql = "SELECT * FROM store_items WHERE product_name='$itemname'";
	    $result = $db->query($sql);
	    if ($result->num_rows > 0) {
			return 1;
		}
		else{
			return 0;
		}
	}
	
	public function checkItemListifExistTable($db){
		
        $sql = "SELECT 1 FROM information_schema.tables WHERE table_schema = 'storeapp_data' AND table_name = 'store_items' LIMIT 1";
	    $result = $db->query($sql);
	    if ($result->num_rows > 0) {
			return 1;
		}
		else{
			return 0;
		}
	}


	
	public function checkInternetConnection($site = "storeupdate.rosebakeshops.com", $port = 80, $timeout = 10) {
	    $connected = @fsockopen($site, $port, $errno, $errstr, $timeout);
	    if ($connected) {
	        fclose($connected);
	        return 1;
	    }
	    return 0;
	}



	public function getEmployeeAcctname($idcode,$branch,$db){
		$query ="SELECT * FROM tbl_employees WHERE branch='$branch' AND idcode='$idcode'";  
		$result = mysqli_query($db, $query);  
		$acctname = '';
		while($ROWS = mysqli_fetch_array($result))  
		{
			$acctname = $ROWS["acctname"];
		}
		return $acctname;
	}
	public function numbertowords($number) {
	    $ones = array(
	        0 => 'ZERO',
	        1 => 'ONE',
	        2 => 'TWO',
	        3 => 'THREE',
	        4 => 'FOUR',
	        5 => 'FIVE',
	        6 => 'SIX',
	        7 => 'SEVEN',
	        8 => 'EIGHT',
	        9 => 'NINE',
	        10 => 'TEN',
	        11 => 'ELEVEN',
	        12 => 'TWELVE',
	        13 => 'THIRTEEN',
	        14 => 'FOURTEEN',
	        15 => 'FIFTEEN',
	        16 => 'SIXTEEN',
	        17 => 'SEVENTEEN',
	        18 => 'EIGHTEEN',
	        19 => 'NINETEEN'
	    );
	    
	    $tens = array(
	        0 => 'ZERO',
	        1 => 'TEN',
	        2 => 'TWENTY',
	        3 => 'THIRTY',
	        4 => 'FORTY',
	        5 => 'FIFTY',
	        6 => 'SIXTY',
	        7 => 'SEVENTY',
	        8 => 'EIGHTY',
	        9 => 'NINETY'
	    );
	    
	    $hundreds = array(
	        'HUNDRED',
	        'THOUSAND',
	        'MILLION',
	        'BILLION',
	        'TRILLION'
	    );
	    
	    $words = '';
	    
	    // Split the number into its integer and decimal parts
	    $integerPart = floor($number);
	    $decimalPart = round($number - $integerPart, 2) * 100;  // Convert decimal part to cents
	    
	    if ($integerPart < 20) {
	        $words = $ones[$integerPart];
	    } elseif ($integerPart < 100) {
	        $words = $tens[(int)($integerPart / 10)];
	        if ($integerPart % 10 > 0) {
	            $words .= ' ' . $ones[$integerPart % 10];
	        }
	    } elseif ($integerPart < 1000) {
	        $words = $ones[(int)($integerPart / 100)] . ' ' . $hundreds[0];
	        if ($integerPart % 100 > 0) {
	            $words .= ' AND ' . $this->numbertowords($integerPart % 100);
	        }
	    } elseif ($integerPart < 1000000) {
	        $words = $this->numbertowords((int)($integerPart / 1000)) . ' ' . $hundreds[1];
	        if ($integerPart % 1000 > 0) {
	            $words .= ' ' . $this->numbertowords($integerPart % 1000);
	        }
	    } else {
	        // Handle very large numbers if needed
	        $words = 'Number is too large to convert';
	    }
	    
	    if ($decimalPart > 0) {
	        $words .= ' AND ' . $this->numbertowords($decimalPart) . ' CENTS';  // Handle cents
	    }
	    
	    return $words;
	}

	public function checkSummaryPosted($branch,$reportDate,$shift,$db)
    {
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$reportDate' AND shift='$shift' AND posted='Posted' AND status='Closed'";
	    $result = $db->query($sql);
	    if ($result->num_rows > 0) {
			return 1;
		}
		else{
			return 0;
		}
	}
	public function checkCashcountPosted($branch,$reportDate,$shift,$db)
    {
		$sql = "SELECT * FROM store_cashcount_data WHERE branch='$branch' AND report_date='$reportDate' AND shift='$shift' AND posted='Posted' AND status='Closed'";
	    $result = $db->query($sql);
	    if ($result->num_rows > 0) {
			return 1;
		}
		else{
			return 0;
		}
	}

	public function itemItemsPriceGet($itemid, $transdate, $db)
    {
        $query = "SELECT * FROM store_items WHERE id='$itemid'";
        $result = mysqli_query($db, $query);

        if ($result->num_rows > 0) {
            while ($ROW = mysqli_fetch_array($result)) {
                $product = $ROW['product_name'];
                $unit_price = $ROW['unit_price'];
                $unit_price_new = $ROW['unit_price_new'];
                $effectivity_date = $ROW['effectivity_date'];
                
                $dateToValidate = $effectivity_date;
                $isValid = $this->validateDateWithFormat($dateToValidate);

                if ($transdate >= $effectivity_date) {
                    return $this->validateDateWithFormat($effectivity_date) ? $ROW['unit_price_new'] : $ROW['unit_price'];
                } else {
                    return $ROW['unit_price'];
                }
            }
        } else {
            return 0;
        }
    }

	public function GetItemCategory($itemname,$db)
	{
		$query = "SELECT * FROM store_items WHERE product_name='$itemname'";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{ 
		    while($IDROW = mysqli_fetch_array($results))  
			{
				return $IDROW['category_name'];
			}
		} else {
			return '';
		}
	}

	public function cashCountDataCheckingOverAllAmount($branch,$shift,$transdate,$db)
	{
		$sql = "SELECT SUM(total_amount) AS val FROM store_cashcount_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";			
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}

	public function cashCountDataCheckingForExist($branch,$transdate,$shift,$db)
	{
		$sql = "SELECT * FROM store_cashcount_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function GetUsernames($db)
	{
		$query = "SELECT * FROM tbl_system_user WHERE void_access=0";
		$return = '<option value=""> --- SELECT USER --- </option>';
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$username = $ROWS['username'];
				$return .= '<option value="'.$username.'">'.$username.'</option>';
			}
			return $return;
		} else {
			return '<option value="">No User</option>';
		}
	}
	public function branchCheckingIfVisayasArea($branch,$db)
	{
		$sql = "SELECT location FROM tbl_branch WHERE branch='$branch'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			$val = '';
			while($row = mysqli_fetch_assoc($result))
			{
				$val = $row['location'];
				if($val == 'CEBU CLUSTER' || $val == 'LEYTE CLUSTER'){
					$val = 'VISAYAS';
				}
				else{
					$val = 'MINDANAO';
				}
			}
			return $val;
		}
		else
		{
			return '';
		}
	}

	public function pcountPcountCheckingIsInputNotLessthanInventory($branch,$shift,$itemid,$transdate,$db)
	{
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND item_id='$itemid' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			$val = 0;
			while($row = mysqli_fetch_assoc($result))
			{
				$val = $row['total'];
			}
			return $val;
		}
		else
		{
			return 0;
		}
	}

	public function tableDataCheckingForPosted($table,$branch,$transdate,$shift,$db)
	{
		$sql = "SELECT * FROM $table WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND posted='Posted'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public function itemsItemsCheckingMatchingData($itemid,$itemname,$db)
	{
		$sql = "SELECT * FROM store_items WHERE id='$itemid' AND product_name='$itemname'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function OverAllACInventoryReport($branch,$shift,$itemid,$transdate,$db)
	{
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND item_id='$itemid' AND report_date='$transdate' AND shift='$shift'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			$val = 0;
			while($row = mysqli_fetch_assoc($result))
			{
				$val = $row['actual_count'];
			}
			return $val;
		}
		else
		{
			return 0;
		}
	}

	public function OverReadingShiftInventoryReport($branch, $transdatefrom, $transdateto, $db)
	{
		$sql = "SELECT DISTINCT shift FROM store_summary_data WHERE branch='$branch' AND report_date BETWEEN '$transdatefrom' AND '$transdateto'";
		$result = mysqli_query($db, $sql);
		
		$shifts = [];
		$val = '';
		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				 $shifts[] = $row['shift'];
			}
			mysqli_free_result($result);
			if (in_array('THIRD SHIFT', $shifts)) 
			{
	            $val = "THIRD SHIFT";
	        }
	        else 
	        {
	            $val = "SECOND SHIFT";
	        }
	        return $val;
		}
		else
		{
			mysqli_free_result($result); 
	        return "";
		}
	}

	public function OverAllBegInventoryReport($branch,$itemid,$transdate,$db)
	{
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND item_id='$itemid' AND report_date='$transdate' AND shift='FIRST SHIFT'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			$val = 0;
			while($row = mysqli_fetch_assoc($result))
			{
				$val = $row['beginning'];
			}
			return $val;
		}
		else
		{
			return 0;
		}

	}
	
	public function getItemPriceDateSelectTo($transdate,$itemid,$db)
	{
		$query ="SELECT *  FROM store_items WHERE id='$itemid'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{
			while($ROW = mysqli_fetch_array($result))  
			{
				$product = $ROW['product_name'];
				$unit_price = $ROW['unit_price'];
				$unit_price_new = $ROW['unit_price_new'];
				$effectivity_date = $ROW['effectivity_date'];
				$session_date = $transdate;
				$functions = new TheFunctions;
				
				if($session_date >= $effectivity_date)
				{
					$functions->validateDateWithFormat($effectivity_date) ? $unit_price = $ROW['unit_price_new']:$unit_price = $ROW['unit_price'];
					return $unit_price;
				}
				else{
					return $ROW['unit_price'];
				}
			}
		} else {
			return 0;
		}
	}
	public function detechPostedData($table,$branch,$transdate,$conn)
	{
		$sql = "SELECT * FROM $table WHERE report_date='$transdate' AND branch='$branch' AND posted='No' AND status='Open'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			return 1;
		} 
		else{	  
			return $sql; 
		}
		$conn->close();
	}

	public function analystVal($branch,$transdate,$conn)
	{
		$sql = "SELECT * FROM store_datelock_checker WHERE report_date='$transdate' AND branch='$branch' AND office_execute=1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc())
			{
				return $row['lock_by'];
			}
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
	}

	public function checkUserPermissionAnalyst($branch, $reportDate, $db)
    {
        $sql = "SELECT * FROM store_datelock_checker WHERE report_date='$reportDate AND 'branch='$branch' AND branch_execute=1 AND office_execute=1 AND status=1";
        $result = $db->query($sql);
		if ($result->num_rows > 0) {
			return 1;
		} 
		else
		{	  
			return 0; 
		}
		$db->close();
    }
	public function dateLockCheckerUpdate($branch, $reportDate, $lockBy, $unlockBy, $db)
    {
        $sql = "UPDATE store_datelock_checker SET branch_execute=1, status='1', lock_by='$lockBy', unlock_by='$unlockBy' WHERE report_date='$reportDate' AND branch='$branch' AND branch_execute=0 AND status=0";
        $result = $db->query($sql);

        if ($result) {
            return 1;
            $db->close();
        } else {
            return 0;
            $db->close();
        }
        $db->close();
    }

    public function dateLockCheckerInsert($branch, $reportDate, $lockBy, $unlockBy, $db)
    {
        $sql = "INSERT INTO store_datelock_checker (report_date, branch, branch_execute, office_execute, status, lock_by, unlock_by) VALUES ('$reportDate', '$branch', '1', '1', '1', '$lockBy', '$unlockBy')";
        $result = $db->query($sql);

        if ($result) {
            return 1;
            $db->close();
        } else {
            return 0;
            $db->close();
        }
        $db->close();
    }
    public function dateLockChecker($branch,$reportDate,$db)
	{
		$sql = "SELECT * FROM store_datelock_checker WHERE report_date='$reportDate' AND branch='$branch' AND office_execute=1";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			return 1;
		} 
		else
		{	  
			return 0; 
		}
		$db->close();
	}

	public function getSumfgtsKiloUse($branch,$transdate,$shift,$item_id,$db)
	{
		$sql = "SELECT SUM(kilo_used) AS actual_total FROM store_fgts_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
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

	public function productionDataTotalPerDayandmonthallitems($selectedbranch,$monthNumber,$yearname,$db)
	{
		$sql = "SELECT SUM(kilo_used) AS val FROM store_summary_data WHERE branch='$selectedbranch' AND MONTH(report_date)='$monthNumber' AND YEAR(report_date)='$yearname' AND category='BREADS'";			
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}
	public function productionDataTotalPerDayallitems($selectedbranch,$reportDate,$db)
	{
		$sql = "SELECT SUM(kilo_used) AS val FROM store_summary_data WHERE branch='$selectedbranch' AND report_date='$reportDate' AND category='BREADS'";			
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}
	public function productionDataTotalPerMonth($item_id,$selectedbranch,$monthNumber,$yearname,$db)
	{
		$sql = "SELECT SUM(kilo_used) AS val FROM store_summary_data WHERE branch='$selectedbranch' AND item_id='$item_id' AND MONTH(report_date)='$monthNumber' AND YEAR(report_date)='$yearname'";			
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}
	public function productionDataTotalPerDay($itemid,$selectedbranch,$reportDate,$db)
	{
		$sql = "SELECT SUM(kilo_used) AS val FROM store_summary_data WHERE branch='$selectedbranch' AND item_id='$itemid' AND report_date='$reportDate'";			
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}
	public function boBreakdownTotalPerMonthandDate($category,$selectedbranch,$reportmonth,$yearname,$db)
	{
		$sql = "SELECT SUM(total) AS val FROM store_badorder_data WHERE branch='$selectedbranch' AND category='$category' AND MONTH(report_date)='$reportmonth' AND YEAR(report_date)='$yearname'";				
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}

	public function salesBreakdown2TotalPerMonthandDate($category,$selectedbranch,$reportmonth,$yearname,$db)
	{
		$sql = "SELECT SUM(amount) AS val FROM store_summary_data WHERE branch='$selectedbranch' AND category='$category' AND MONTH(report_date)='$reportmonth' AND YEAR(report_date)='$yearname'";				
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}

	public function boBreakdownTotalPerMonth($selectedbranch,$reportmonth,$yearname,$db)
	{
		$sql = "SELECT SUM(total) AS val FROM store_badorder_data WHERE branch='$selectedbranch' AND MONTH(report_date)='$reportmonth' AND YEAR(report_date)='$yearname'";				
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}
	public function boBreakdownTotalPerDay($selectedbranch,$reportdate,$db)
	{
		$sql = "SELECT SUM(total) AS val FROM store_badorder_data WHERE branch='$selectedbranch' AND report_date='$reportdate'";				
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}

	public function boBreakdown($category,$selectedbranch,$reportdate,$db)
	{
		$sql = "SELECT SUM(total) AS val FROM store_badorder_data WHERE category='$category' AND branch='$selectedbranch' AND report_date='$reportdate'";				
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}
	public function salesBreakdown2TotalPerMonth($selectedbranch,$reportmonth,$yearname,$db)
	{
		$sql = "SELECT SUM(amount) AS val FROM store_summary_data WHERE branch='$selectedbranch' AND MONTH(report_date)='$reportmonth' AND YEAR(report_date)='$yearname'";				
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}
	public function salesBreakdown2TotalPerDay($selectedbranch,$reportdate,$db)
	{
		$sql = "SELECT SUM(amount) AS val FROM store_summary_data WHERE branch='$selectedbranch' AND report_date='$reportdate'";				
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}
	public function salesBreakdown2($category,$selectedbranch,$reportdate,$db)
	{
		$sql = "SELECT SUM(amount) AS val FROM store_summary_data WHERE category='$category' AND branch='$selectedbranch' AND report_date='$reportdate'";				
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {											
				$val = $row['val'];			
			}
			return $val;
		} 
		else{
			return $val;
		}
	}

	public function getPakatiTotal($branch,$report_date,$shift,$db){
		$sql = "SELECT SUM(total_amount) AS val FROM store_pakati_data WHERE branch='$branch' AND report_date='$report_date' AND shift='$shift'";
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
	public function getGrabBreakdown($category,$branch,$transdate,$shift,$db)
	{	
		$sql = "SELECT * FROM store_grab_data WHERE category='$category' AND branch='$branch' AND report_date='$transdate' AND shift='$shift'";				
		$result = $db->query($sql);
		$val = 0;$unitprice = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {			
				$total=0;$transfer_out=0;$should_be=0;$sold;
				
				$item_id = $row['item_id'];	
				$item_name = $row['item_name'];	
				$quantity = $row['quantity'];
				$unitprice = $row['unit_price'];
												
				$amount =  $quantity * $unitprice;				
				$val += $amount;
			}
			return $val;
		} 
		else{
			return $val;
		}		
		
	}
	public function getGrabTotal($branch,$report_date,$shift,$db){
		$sql = "SELECT SUM(total) AS val FROM store_grab_data WHERE branch='$branch' AND report_date='$report_date' AND shift='$shift'";
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

	public function getInvSalesOfVs($transdate,$db)
	{	
		$sql = "SELECT SUM(amount) AS totalAmount FROM store_summary_data WHERE report_date='$transdate'";				
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {			
				$val = $row['totalAmount'];
			}
			return $val;
		} 
		else{
			return $val;
		}		
	}
	public function getGcashBreakdown($category,$branch,$transdate,$shift,$db)
	{	
		$sql = "SELECT * FROM store_gcash_data WHERE category='$category' AND branch='$branch' AND report_date='$transdate' AND shift='$shift'";				
		$result = $db->query($sql);
		$val = 0;$unitprice = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {			
				$total=0;$transfer_out=0;$should_be=0;$sold;
				
				$item_id = $row['item_id'];	
				$item_name = $row['item_name'];	
				$quantity = $row['quantity'];
				$unitprice = $row['unit_price'];
										
				$amount =  $quantity * $unitprice;				
				$val += $amount;
			}
			return $val;
		} 
		else{
			return $val;
		}		
		
	}
	public function getGcashTotal($branch,$report_date,$shift,$db){
		$sql = "SELECT SUM(total) AS val FROM store_gcash_data WHERE branch='$branch' AND report_date='$report_date' AND shift='$shift'";
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

	public function supervisor($table,$rowid,$db)
	{
		$supervisor = '';
		$sql = "SELECT * FROM $table WHERE id='$rowid'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$supervisor = $row['supervisor'];
			}
			return $supervisor;
		}
		return $supervisor;

	}
	public function suppliesInventoryValue($branch,$itemid,$datefrom,$dateto,$column,$db)
	{
	
		$return = 2;
		$sql = "SELECT SUM($column) AS $column FROM store_supplies_summary_data WHERE branch='$branch' AND item_id='$itemid' AND report_date BETWEEN '$datefrom' AND '$dateto'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$val = $row[$column];
			}
			$return = $val;
		}
		return $return;
	}
	public function boInventoryInventoryValue($branch,$itemid,$datefrom,$dateto,$column,$db)
	{
		$return = 2;
		$sql = "SELECT SUM($column) AS $column FROM store_boinventory_summary_data WHERE branch='$branch' AND item_id='$itemid' AND report_date BETWEEN '$datefrom' AND '$dateto'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$val = $row[$column];
			}
			$return = $val;
		}
		return $return;
	}
	public function scrapInventoryInventoryValue($branch,$itemid,$datefrom,$dateto,$column,$db)
	{
		$return = 2;
		$sql = "SELECT SUM($column) AS $column FROM store_scrapinventory_summary_data WHERE branch='$branch' AND item_id='$itemid' AND report_date BETWEEN '$datefrom' AND '$dateto'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$val = $row[$column];
			}
			$return = $val;
		}
		return $return;
	}


	public function CalculateBoInventorySummary($rowid,$sub_total,$grand_total,$difference,$total_amount,$branch_name,$branch_date,$branch_shift,$db)
	{
		$query = "UPDATE store_boinventory_summary_data SET sub_total='$sub_total',total='$grand_total',difference='$difference',amount='$total_amount'
		WHERE branch='$branch_name' AND report_date='$branch_date' AND shift='$branch_shift' AND id='$rowid'";
		if ($db->query($query) === TRUE) { } else { echo $db->error; exit(); }
	}

	public function GetBoInventoryTransferInQtySum($branch,$report_date,$shift,$item_id,$db){
		$quantity = 0;
		$sql = "SELECT * FROM store_boinventory_transfer_data WHERE transfer_to='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id' AND posted='Posted' AND status='Closed'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
			{
			while($row = mysqli_fetch_assoc($result)) {
				$quantity += $row['weight'];
			}
			return $quantity;
		} 
		else{
		  return $quantity;
		}
	}


	public function GetBoInventoryTransferOutQtySum($branch,$report_date,$shift,$item_id,$db){
		$quantity = 0;
		$sql = "SELECT * FROM store_boinventory_transfer_data WHERE branch='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id' AND posted='Posted' AND status='Closed'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
			{
			while($row = mysqli_fetch_assoc($result)) {
				$quantity += $row['weight'];
			}
			return $quantity;
		} 
		else{
		  return $quantity;
		}
	}

	/* ################################ BO INVENTORY REMOTE TRANSFER ################################################## ITEM NAME CHANGED TO ITEM ID */
	public function iTransferOutBoInventory($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn)
	{		
		$query ="SELECT * FROM store_remote_boinventorytransfer WHERE bid='$rowid' AND branch='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id'";  
		$result = mysqli_query($conn, $query);  
		if($result->num_rows === 0)
		{		
			$queryInsert = "INSERT INTO store_remote_boinventorytransfer (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`)";
			$queryInsert .= "VALUES('$rowid','$branch','$report_date','$shift','$time_covered','$person','$encoder','$category','$item_id','$item_name','$weight','$units','$transfer_to','$date_updated')";
			if ($conn->query($queryInsert) === TRUE)
			{		
				echo '<script>console.log("Insert Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		} else {
		
			$update = "
				time_covered='$time_covered',employee_name='$person',item_id='$item_id',item_name='$item_name',weight='$weight',
				units='$units',transfer_to='$transfer_to',date_updated='$date_updated'
			";
			$queryDataUpdate = "UPDATE store_remote_boinventorytransfer SET $update WHERE bid='$rowid' AND branch='$branch' AND report_date='$report_date' AND shift='$shift'";
			if ($conn->query($queryDataUpdate) === TRUE)
			{
				echo '<script>console.log("Update Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		}
		
	}

		
	//////////////////////////////////////////////// SCRAP INVENTORY /////////////////////////////////////////
	

	public function GetScrapInventoryTransferInQtySum($branch,$report_date,$shift,$item_id,$db){
		$quantity = 0;
		$sql = "SELECT * FROM store_scrapinventory_transfer_data WHERE transfer_to='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id' AND posted='Posted' AND status='Closed'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
			{
			while($row = mysqli_fetch_assoc($result)) {
				$quantity += $row['weight'];
			}
			return $quantity;
		} 
		else{
		  return $quantity;
		}
	}


	public function GetScrapInventoryTransferOutQtySum($branch,$report_date,$shift,$item_id,$db){
		$quantity = 0;
		$sql = "SELECT * FROM store_scrapinventory_transfer_data WHERE branch='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id' AND posted='Posted' AND status='Closed'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
			{
			while($row = mysqli_fetch_assoc($result)) {
				$quantity += $row['weight'];
			}
			return $quantity;
		} 
		else{
		  return $quantity;
		}
	}

	/* ################################ SCRAP INVENTORY REMOTE TRANSFER ################################################## ITEM NAME CHANGED TO ITEM ID */
	public function iTransferOutScrapInventory($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn)
	{		
		$query ="SELECT * FROM store_remote_scrapinventorytransfer WHERE bid='$rowid' AND branch='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id'";  
		$result = mysqli_query($conn, $query);  
		if($result->num_rows === 0)
		{		
			$queryInsert = "INSERT INTO store_remote_scrapinventorytransfer (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`)";
			$queryInsert .= "VALUES('$rowid','$branch','$report_date','$shift','$time_covered','$person','$encoder','$category','$item_id','$item_name','$weight','$units','$transfer_to','$date_updated')";
			if ($conn->query($queryInsert) === TRUE)
			{		
				echo '<script>console.log("Insert Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		} else {
		
			$update = "
				time_covered='$time_covered',employee_name='$person',item_id='$item_id',item_name='$item_name',weight='$weight',
				units='$units',transfer_to='$transfer_to',date_updated='$date_updated'
			";
			$queryDataUpdate = "UPDATE store_remote_scrapinventorytransfer SET $update WHERE bid='$rowid' AND branch='$branch' AND report_date='$report_date' AND shift='$shift'";
			if ($conn->query($queryDataUpdate) === TRUE)
			{
				echo '<script>console.log("Update Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		}
		
	}

	public function CalculateScrapInventorySummary($rowid,$sub_total,$grand_total,$difference,$total_amount,$branch_name,$branch_date,$branch_shift,$db)
	{
		$query = "UPDATE store_scrapinventory_summary_data SET sub_total='$sub_total',total='$grand_total',difference='$difference',amount='$total_amount'
		WHERE branch='$branch_name' AND report_date='$branch_date' AND shift='$branch_shift' AND id='$rowid'";
		if ($db->query($query) === TRUE) { } else { echo $db->error; exit(); }
	}
	public function CalculateSuppliesSummary($rowid,$sub_total,$grand_total,$difference,$total_amount,$branch_name,$branch_date,$branch_shift,$db)
	{
		$query = "UPDATE store_supplies_summary_data SET sub_total='$sub_total',total='$grand_total',difference='$difference',amount='$total_amount'
		WHERE branch='$branch_name' AND report_date='$branch_date' AND shift='$branch_shift' AND id='$rowid'";
		if ($db->query($query) === TRUE) { } else { echo $db->error; exit(); }
	}


	public function GetSuppliesTransferInQtySum($branch,$report_date,$shift,$item_id,$db){
		$quantity = 0;
		$sql = "SELECT * FROM store_supplies_transfer_data WHERE transfer_to='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id' AND posted='Posted' AND status='Closed'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
			{
			while($row = mysqli_fetch_assoc($result)) {
				$quantity += $row['weight'];
			}
			return $quantity;
		} 
		else{
		  return $quantity;
		}
	}


	public function GetSuppliesTransferOutQtySum($branch,$report_date,$shift,$item_id,$db){
		$quantity = 0;
		$sql = "SELECT * FROM store_supplies_transfer_data WHERE branch='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id' AND posted='Posted' AND status='Closed'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
			{
			while($row = mysqli_fetch_assoc($result)) {
				$quantity += $row['weight'];
			}
			return $quantity;
		} 
		else{
		  return $quantity;
		}
	}

	/* ################################ SUPPLIES REMOTE TRANSFER ################################################## ITEM NAME CHANGED TO ITEM ID */
	public function iTransferOutSupplies($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn)
	{		
		$query ="SELECT * FROM store_remote_suppliestransfer WHERE bid='$rowid' AND branch='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id'";  
		$result = mysqli_query($conn, $query);  
		if($result->num_rows === 0)
		{		
			$queryInsert = "INSERT INTO store_remote_suppliestransfer (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`)";
			$queryInsert .= "VALUES('$rowid','$branch','$report_date','$shift','$time_covered','$person','$encoder','$category','$item_id','$item_name','$weight','$units','$transfer_to','$date_updated')";
			if ($conn->query($queryInsert) === TRUE)
			{		
				echo '<script>console.log("Insert Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		} else {
		
			$update = "
				time_covered='$time_covered',employee_name='$person',item_id='$item_id',item_name='$item_name',weight='$weight',
				units='$units',transfer_to='$transfer_to',date_updated='$date_updated'
			";
			$queryDataUpdate = "UPDATE store_remote_suppliestransfer SET $update WHERE bid='$rowid' AND branch='$branch' AND report_date='$report_date' AND shift='$shift'";
			if ($conn->query($queryDataUpdate) === TRUE)
			{
				echo '<script>console.log("Update Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		}
		
	}
	public function totalSalesBreakdown($selectedbranch,$dateselectfrom,$dateselectto,$db){
		$query ="SELECT SUM(amount) AS totalAmount FROM store_summary_data WHERE branch='$selectedbranch' AND report_date BETWEEN '$dateselectfrom' AND '$dateselectto'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{			
			while($ROW = mysqli_fetch_array($result))
			{
				return $ROW['totalAmount'];
			}
		} else {
			return 0;
		}
	}
	public function getSalesBreakdown($category,$item_id,$selectedbranch,$dateselectfrom,$dateselectto,$db)
	{	
		$sql = "SELECT * FROM store_summary_data WHERE category='$category' AND branch='$selectedbranch' AND report_date BETWEEN '$dateselectfrom' AND '$dateselectto' AND item_id='$item_id'";				
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {			
												
				$amount =  $row['amount'];			
				$val += $amount;
			}
			return $val;
		} 
		else{
			return $val;
		}		
	}
	public function GetIventoryDiscountType($discountType,$branch_name,$dateselectfrom,$dateselectto,$db)
	{
		$query ="SELECT SUM(discount) AS dscount FROM store_discount_data WHERE branch='$branch_name' AND report_date BETWEEN '$dateselectfrom' AND '$dateselectto' AND posted='Posted' AND discount_type='$discountType'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{			
			while($ROW = mysqli_fetch_array($result))
			{
				return $ROW['dscount'];
			}
		} else {
			return 0;
		}
	}

	public function GetInventoryDiscountType($category,$branch_name,$dateselectfrom,$dateselectto,$db)
	{
		$query ="SELECT SUM(discount) AS dscount FROM store_discount_data WHERE branch='$branch_name' AND report_date BETWEEN '$dateselectfrom' AND '$dateselectto' AND posted='Posted' AND category='$category'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{			
			while($ROW = mysqli_fetch_array($result))
			{
				return number_format($ROW['dscount'],0);
			}
		} else {
			return 0;
		}
	}

	public function GetDiscountTypeCategorBadge($category,$branch_name,$branch_date,$shift,$db)
	{
		$query ="SELECT SUM(discount) AS dscount FROM store_discount_data WHERE branch='$branch_name' AND report_date='$branch_date' AND shift='$shift' AND posted='Posted' AND category='$category'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{			
			while($ROW = mysqli_fetch_array($result))
			{
				if($ROW['dscount'] == 0){
					return '';
				} else {
					return number_format($ROW['dscount'],0);
				}
				
			}
		} else {
			return '';
		}
	}

	public function GetDiscountType($discountType,$branch_name,$branch_date,$shift,$db)
	{
		$query ="SELECT SUM(discount) AS dscount FROM store_discount_data WHERE branch='$branch_name' AND report_date='$branch_date' AND shift='$shift' AND posted='Posted' AND discount_type='$discountType'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{			
			while($ROW = mysqli_fetch_array($result))
			{
				return $ROW['dscount'];
			}
		} else {
			return 0;
		}
	}

	public function GetServer($server)
	{		
		$option = '<option value="">--- SELECT SERVER---</option>';
		$includes = array(
			"Globe Server" => "120.28.196.113",
			"Converge Server" => "161.49.101.171",
		);				
		foreach ( $includes as $key => $value )
		{
			$chosen = '';
			if($value == $server)		
			{
				$chosen = 'selected';
			}
			echo '<option '.$chosen.' value="'.$value.'">'.$key.'</option>';
		}
	}
	public function GetOnlineServerRoot($column)
	{
		if(file_exists("./.file/server.rms"))
		{
			$array = file_get_contents("./.file/server.rms", "r");
			$data = json_decode($array, true);
			$aray = $data;
			for ($t = 0; $t < count($aray); $t++) {	
				return ($aray[$t][$column]);
			}
		}
	}
	public function GetOnlineServer($column)
	{
		if(file_exists("../.file/server.rms"))
		{
			$array = file_get_contents("../.file/server.rms", "r");
			$data = json_decode($array, true);
			$aray = $data;
			for ($t = 0; $t < count($aray); $t++) {	
				return ($aray[$t][$column]);
			}
		} else {
			$data = array();
			array_push($data, [
				'server_name'=>"Globe Server",
				'server_ip'=>"120.28.196.113",
				'remote_url'=>"storeupdate.rosebakeshops.com"
			]);
			$array = json_encode($data);
			$srv = fopen("../.file/server.rms", "w") or die("Unable to open file!");
			$txt = $array;
			fwrite($srv, $txt);
			fclose($srv);
		}
	}
	public function rmDUMValue($branch,$itemid,$datefrom,$dateto,$column,$db)
	{
		$return = 2;
		$sql = "SELECT SUM($column) AS $column FROM store_dum_data WHERE branch='$branch' AND item_id='$itemid' AND report_date BETWEEN '$datefrom' AND '$dateto'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$val = $row[$column];
			}
			$return = $val;
		}
		return $return;
	}
	public function rmInventoryValue($branch,$itemid,$datefrom,$dateto,$column,$db)
	{
		$return = 2;
		$sql = "SELECT SUM($column) AS $column FROM store_rm_summary_data WHERE branch='$branch' AND item_id='$itemid' AND report_date BETWEEN '$datefrom' AND '$dateto'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$val = $row[$column];
			}
			$return = $val;
		}
		return $return;
	}
	public function getInventoryCashCountTotal($selectedbranch,$dateselectfrom,$dateselectto,$db){
		$sql = "SELECT SUM(total_amount) AS val FROM store_cashcount_data WHERE branch='$selectedbranch' AND report_date BETWEEN '$dateselectfrom' AND '$dateselectto'";
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
	public function GetInventoryDiscount($branch_name,$dateselectfrom,$dateselectto,$db)
	{
		$query ="SELECT SUM(discount) AS dscount FROM store_discount_data WHERE branch='$branch_name' AND report_date BETWEEN '$dateselectfrom' AND '$dateselectto' AND posted='Posted'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{			
			while($ROW = mysqli_fetch_array($result))
			{
				return $ROW['dscount'];
			}
		}
		return 0;
	}
	public function getInventoryBreakdown($category,$selectedbranch,$dateselectfrom,$dateselectto,$db)
	{	
		$sql = "SELECT * FROM store_summary_data WHERE category='$category' AND branch='$selectedbranch' AND report_date BETWEEN '$dateselectfrom' AND '$dateselectto'";				
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {			
												
				$amount =  $row['amount'];			
				$val += $amount;
			}
			return $val;
		} 
		else{
			return $val;
		}		
	}
	public function inventoryValue($branch,$itemid,$datefrom,$dateto,$column,$db)
	{
		$return = 2;
		$sql = "SELECT SUM($column) AS $column FROM store_summary_data WHERE branch='$branch' AND item_id='$itemid' AND report_date BETWEEN '$datefrom' AND '$dateto'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$val = $row[$column];
			}
			$return = $val;
		}
		return $return;

	}
	public function validateDateWithFormat($date, $format = 'Y-m-d')
	{
	   $d = DateTime::createFromFormat($format, $date);
	   $errors = DateTime::getLastErrors();
	 
	   return $d && empty($errors['warning_count']) && $d->format($format) == $date;
	      
	}
	public function setUpdatePostingtranRMIN($tbl,$branch,$transdate,$shift,$rowid,$db)
    {
		$queryInvUpdates = "UPDATE $tbl SET posted='Posted',status='Closed' WHERE transfer_to='$branch' AND  report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		if ($db->query($queryInvUpdates) === TRUE) { echo '<script>console.log("Posted A")</script>'; } else { echo $db->error; exit(); }
	}

	public function GetRMTransferOutQtySum($branch,$report_date,$shift,$item_id,$db){
		$quantity = 0;
		$sql = "SELECT * FROM store_rm_transfer_data WHERE branch='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id' AND posted='Posted' AND status='Closed'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
			{
			while($row = mysqli_fetch_assoc($result)) {
				$quantity += $row['weight'];
			}
			return $quantity;
		} 
		else{
		  return $quantity;
		}
	}
	public function CheckRemoteIn($rowid,$tid,$remote_shift,$trans_date,$store_branch,$db,$conn)
	{	
		$sql = "SELECT * FROM store_remote_transfer WHERE id='$tid'";				
		$result = $conn->query($sql);
		if ($result->num_rows === 0)
		{
			$queryDataDelete = "DELETE FROM store_transfer_data WHERE id='$rowid'";
			if ($db->query($queryDataDelete) === TRUE){ } else { return $db->error; }
		}
	}
	public function getBadOrderBreakdown($category,$branch,$transdate,$shift,$db)
	{	
		$sql = "SELECT * FROM store_badorder_data WHERE category='$category' AND branch='$branch' AND report_date='$transdate' AND shift='$shift'";				
		$result = $db->query($sql);
		$val = 0;$unitprice = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {			
				$total=0;$transfer_out=0;$should_be=0;$sold;
				
				$item_id = $row['item_id'];	
				$item_name = $row['item_name'];	
				$quantity = $row['quantity'];
				$unitprice = $row['unit_price'];
								
				$amount =  $quantity * $unitprice;				
				$val += $amount;
			}
			return $val;
		} 
		else{
			return $val;
		}		
		
	}
	public function GetChargesQtySum($branch,$report_date,$shift,$item_id,$db){
		$quantity = 0;
		$sql = "SELECT * FROM store_charges_data WHERE branch='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id' AND posted='Posted' AND status='Closed'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
			{
			while($row = mysqli_fetch_assoc($result)) {
				$quantity += $row['quantity'];
			}
			return $quantity;
		} 
		else{
		  return $quantity;
		}
	}
	public function SaveToDUM($value,$db)
	{				
		$queryDataInsert = "INSERT INTO store_dum_data (`sid`,`item_id`,`branch`,`report_date`,`shift`,`item_name`,`beginning`,`delivery`,`transfer_in`,`transfer_out`,`physical_count`,`price_kg`)
		VALUES ($value)";
		if ($db->query($queryDataInsert) === TRUE) {}else {  echo $db->error;}
	}
	public function GetTransferOutQtySum($branch,$report_date,$shift,$item_id,$db){
		$quantity = 0;
		$sql = "SELECT * FROM store_transfer_data WHERE branch='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id' AND posted='Posted' AND status='Closed'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
			{
			while($row = mysqli_fetch_assoc($result)) {
				$quantity += $row['quantity'];
			}
			return $quantity;
		} 
		else{
		  return $quantity;
		}
	}
	public function GetRMTransferInQtySum($branch,$report_date,$shift,$item_id,$db){
		$quantity = 0;
		$sql = "SELECT * FROM store_rm_transfer_data WHERE transfer_to='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id' AND posted='Posted' AND status='Closed'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
			{
			while($row = mysqli_fetch_assoc($result)) {
				$quantity += $row['weight'];
			}
			return $quantity;
		} 
		else{
		  return $quantity;
		}
	}
	public function GetTransferInQtySum($branch,$report_date,$shift,$item_id,$db){
		$quantity = 0;
		$sql = "SELECT * FROM store_transfer_data WHERE transfer_to='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id' AND posted='Posted' AND status='Closed'";
		$result = mysqli_query($db, $sql);
		if (mysqli_num_rows($result) > 0) 
			{
			while($row = mysqli_fetch_assoc($result)) {
				$quantity += $row['quantity'];
			}
			return $quantity;
		} 
		else{
		  return $quantity;
		}
	}
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
		if($module=='production'){
			$sqlMainEmployee = "SELECT COUNT(id) as mecnt FROM $table WHERE branch='$branch' AND month=MONTH('$transdate')";
		}
		else{
			$sqlMainEmployee = "SELECT COUNT(id) as mecnt FROM $table WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		}				
		
		$MainEmpResult = mysqli_query($conn, $sqlMainEmployee);
		
		if($module == 'transfer' || $module == 'rm_transfer'){
			
			$sqlMainEmployee2 = "SELECT COUNT(id) as mecnt2 FROM $table WHERE report_date='$transdate' AND shift='$shift' AND transfer_to='$branch'";
			$MainEmpResult2 = mysqli_query($conn, $sqlMainEmployee2);

			return mysqli_fetch_assoc($MainEmpResult)['mecnt'] + mysqli_fetch_assoc($MainEmpResult2)['mecnt2'];
		}
		else{
			return mysqli_fetch_assoc($MainEmpResult)['mecnt'];
		}
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
		if($table == 'branch')
		{
			if($type == 'main')
			{
				$sqlMainEmployee = "SELECT COUNT(id) as mecnt FROM tbl_branch";
				$MainEmpResult = mysqli_query($conn, $sqlMainEmployee);
				$mecount = mysqli_fetch_assoc($MainEmpResult)['mecnt'];
				return $mecount;
			}
			if($type == 'branch')
			{
				$sqlBranchEmployee = "SELECT COUNT(id) as becnt FROM tbl_branch";
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
				if ($branchItemsResult->num_rows > 0)
				{
					$mainItemCount = mysqli_fetch_assoc($branchItemsResult)['itemcnt'];
					return $mainItemCount;
				}
				else
				{
					echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
				}
			}
		}			
	}
	public function ProductionDailyTotalAmount($branch,$report_date,$items,$db) // PSA CLASS DAPAT ITEM ID
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
	public function GetSummary($rowid,$store_branch,$trans_date,$store_shift,$db)
	{
		$query ="SELECT * FROM store_dum_data WHERE sid='$rowid' AND branch='$store_branch' AND report_date='$trans_date' AND shift='$store_shift'";
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
	public function GetDiscount($branch_name,$branch_date,$shift,$db)
	{
		$query ="SELECT SUM(discount) AS dscount FROM store_discount_data WHERE branch='$branch_name' AND report_date='$branch_date' AND shift='$shift' AND posted='Posted'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{			
			while($ROW = mysqli_fetch_array($result))
			{
				return $ROW['dscount'];
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
				
				//Check if previous date is 3 shifts
				$query ="SELECT *  FROM $tbl WHERE $kwiri AND shift='THIRD SHIFT' AND to_shift='FIRST SHIFT' AND to_shift_date='$transdate' AND from_shift='$tdate' AND item_id='$item_id'"; 
				$result = mysqli_query($db, $query);  
				if($result->num_rows > 0)
				{
					$q = "AND shift='THIRD SHIFT' AND to_shift='FIRST SHIFT' AND to_shift_date='$transdate' AND from_shift='$tdate'";
				}
				else
				{
					$q = "AND shift='SECOND SHIFT' AND to_shift='FIRST SHIFT' AND to_shift_date='$transdate' AND from_shift='$tdate'";	
				}	
			}
			else if($shift == 'SECOND SHIFT')
			{
				$q = "AND shift='FIRST SHIFT' AND to_shift='SECOND SHIFT' AND to_shift_date='$transdate' AND from_shift='$transdate'";	
			} 
			else {
				$q='';	
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
				$q='';	
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
	 ################################ FGTS REMOTE TRANSFER ################################################## ITEM NAME CHANGED TO ITEM ID */	
	public function iTransferOut($table,$form_type,$rowid,$from_branch,$date,$shift,$timecovered,$person,$encoder,$category,$item_id,$itemname,$quantity,$unit_price,$amount,$to_branch,$time_stamp,$conn)
	{
		$query ="SELECT * FROM store_remote_transfer WHERE bid='$rowid' AND branch='$from_branch' AND report_date='$date' AND item_id='$item_id' AND transfer_type='$form_type'";  
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
			$queryDataUpdate = "UPDATE store_remote_transfer SET $update WHERE bid='$rowid' AND branch='$from_branch' AND report_date='$date' AND item_id='$item_id' AND transfer_type='$form_type'";
			if ($conn->query($queryDataUpdate) === TRUE)
			{
				echo '<script>console.log("Update Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		}
	}
	
	/* ################################ RAWMATS REMOTE TRANSFER ################################################## ITEM NAME CHANGED TO ITEM ID */
	public function iTransferOutRM($rowid,$branch,$report_date,$shift,$time_covered,$person,$encoder,$category,$item_id,$item_name,$weight,$units,$transfer_to,$date_created,$updated_by,$date_updated,$conn)
	{		
		$query ="SELECT * FROM store_remote_rmtransfer WHERE bid='$rowid' AND branch='$branch' AND report_date='$report_date' AND shift='$shift' AND item_id='$item_id'";  
		$result = mysqli_query($conn, $query);  
		if($result->num_rows === 0)
		{		
			$queryInsert = "INSERT INTO store_remote_rmtransfer (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`)";
			$queryInsert .= "VALUES('$rowid','$branch','$report_date','$shift','$time_covered','$person','$encoder','$category','$item_id','$item_name','$weight','$units','$transfer_to','$date_updated')";
			if ($conn->query($queryInsert) === TRUE)
			{		
				echo '<script>console.log("Insert Transfer Out Success")</script>';
			} else {
				echo $conn->error;
			}
		} else {
		
			$update = "
				time_covered='$time_covered',employee_name='$person',item_id='$item_id',item_name='$item_name',weight='$weight',
				units='$units',transfer_to='$transfer_to',date_updated='$date_updated'
			";
			$queryDataUpdate = "UPDATE store_remote_rmtransfer SET $update WHERE bid='$rowid' AND branch='$branch' AND report_date='$report_date' AND shift='$shift'";
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
				
				$unitprice = $row['unit_price'];				    					
				
				$total += ($beginning + $stock + $transfer_in + $frozendough);
				$should_be = ($total - $transfer_out - $charges - $snacks - $bad_order - $damaged - $complimentary);
				
				$sold = $should_be == 0 ? 0: $should_be - $actual_count;
								
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
				$product = $ROW['product_name'];
				$unit_price = $ROW['unit_price'];
				$unit_price_new = $ROW['unit_price_new'];
				$effectivity_date = $ROW['effectivity_date'];
				$session_date = $_SESSION['session_date'];
				$functions = new TheFunctions;
				
				if($session_date >= $effectivity_date)
				{
					$functions->validateDateWithFormat($effectivity_date) ? $unit_price = $ROW['unit_price_new']:$unit_price = $ROW['unit_price'];
					return $unit_price;
				}
				else{
					return $ROW['unit_price'];
				}
			}
		} else {
			return 0;
		}
	}
	public function getSumfgts($branch,$transdate,$shift,$item_id,$db)
	{
		$sql = "SELECT SUM(actual_yield) AS actual_total FROM store_fgts_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$item_id'";
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
	public function GetItemID($itemname,$db)
	{
		$query = "SELECT * FROM store_items WHERE product_name='$itemname'";
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
/* ############################################################################## */
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
	public function CheckTransferData($table,$branch,$report_date,$shift,$data,$db)
	{
		if($table == 'store_transfer_data' && $data == "OUT")
		{
			$q = "branch='$branch'";
		} 
		else if($table == 'store_transfer_data' && $data == "IN")
		{
			$q = "transfer_to='$branch'";
		}
		else
		{
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