<?PHP
include '../init.php';
if(!isset($_SESSION['appstore_userlevel']) || !$_SESSION['appstore_userlevel']) {	
	session_destroy();
	header("Location: ../log_awt.php");
}
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
include '../db_config_main.php';
if($_SESSION['OFFLINE_MODE'] == 0)
{
	$conn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);
}
$functions = new TheFunctions;
$modyul = new SystemTools;
if(isset($_POST['mode']))
{
	$mode = $_POST['mode'];
} else {
	print_r('
		<script>
			app_alert("Warning"," The Mode you are trying to pass does not exist","warning","Ok","","no");
		</script>
	');
	exit();
}
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$time_stamp = date("Y-m-d H:i:s");


if($mode == 'employeenameauto'){
	$employee = $_POST['employee'];
	
	$query = "SELECT idcode FROM tbl_employees WHERE idcode = ?";
	$stmt = $db->prepare($query);
	$stmt->bind_param("s", $employee);
	$stmt->execute();
	$stmt->bind_result($idcode);
	$stmt->fetch();
	$stmt->close();
	
	echo $idcode;
}

if($mode == 'savechargesemployeeauto_new')
{
	$itemname = $_POST['itemname'];
    $category = $functions->GetItemCategory($itemname,$db);
    $qty = 0;
    $itemid = $_POST['itemid'];
    $branch = $_POST['branch'];
    $transdate = $_POST['reportdate'];
    $shift = $_POST['shift'];

    $idcode = $_POST['idcode'];
    $employeename = $functions->getEmployeeAcctname($idcode,$branch,$db);
    
    $supervisor = $_SESSION['appstore_appnameuser'];
    
    $datecreated = date("Y-m-d H:i:s");
    $unitprice = $functions->itemItemsPriceGet($itemid, $transdate, $db);
    
	if($employeename == ''){
		echo '<script>app_alert("System Message","The employee name must be provided and cannot be left empty","warning");</script>';
		exit();
	}
	if($idcode == ''){
		echo '<script>app_alert("System Message","The IDCODE must be provided and cannot be left empty","warning");</script>';
		exit();
	}

    
    $check_query = "SELECT * FROM store_charges_data WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	$result = mysqli_query($db, $check_query);
	if (mysqli_num_rows($result) > 0) 
	{
		$updateQuery = "UPDATE store_charges_data SET idcode='$idcode', employee_name='$employeename', date_updated='$datecreated', updated_by='$supervisor' WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	    if(mysqli_query($db, $updateQuery)) {
	        echo '<script>
            		set_function("Inventory Record","inventory_record");
            		app_alert("System Message", "Record updated successfully", "success");
            	</script>';

	    } else {
	        echo '<script>app_alert("System Message", "Error updating record", "error");</script>';
	    }
	}
	else
	{
        $insertQuery = "INSERT INTO store_charges_data (branch,report_date,shift,idcode,employee_name,supervisor,category,item_name,item_id,quantity,unit_price,total,date_created) VALUES ('$branch', '$transdate', '$shift', '$idcode', '$employeename', '$supervisor', '$category', '$itemname', '$itemid', '$qty', '$unitprice', '$amount', '$datecreated')";
        if (mysqli_query($db, $insertQuery)) {
        	
            echo '<script>
            		set_function("Inventory Record","inventory_record");
            		app_alert("System Message", "Record inserted successfully", "success");
            	</script>';
        } else {
            echo '<script>app_alert("System Message", "Error inserting record", "error");</script>';
        }
	}
}
if($mode == 'savechargesemployeemanual_new')
{
	$itemname = $_POST['itemname'];
    $category = $functions->GetItemCategory($itemname,$db);
    $qty = 0;
    $itemid = $_POST['itemid'];
    $branch = $_POST['branch'];
    $transdate = $_POST['reportdate'];
    $shift = $_POST['shift'];

    $employeename = $_POST['employeename'];
    $idcode = $_POST['idcode'];
    
    $supervisor = $_SESSION['appstore_appnameuser'];
    
    $datecreated = date("Y-m-d H:i:s");
    $unitprice = $functions->itemItemsPriceGet($itemid, $transdate, $db);
    
	if($employeename == ''){
		echo '<script>app_alert("System Message","The employee name must be provided and cannot be left empty","warning");</script>';
		exit();
	}
	if($idcode == ''){
		echo '<script>app_alert("System Message","The IDCODE must be provided and cannot be left empty","warning");</script>';
		exit();
	}

    
    $check_query = "SELECT * FROM store_charges_data WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	$result = mysqli_query($db, $check_query);
	if (mysqli_num_rows($result) > 0) 
	{
		$updateQuery = "UPDATE store_charges_data SET idcode='$idcode', employee_name='$employeename', date_updated='$datecreated', updated_by='$supervisor' WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	    if(mysqli_query($db, $updateQuery)) {
	        echo '<script>
            		set_function("Inventory Record","inventory_record");
            		app_alert("System Message", "Record updated successfully", "success");
            	</script>';

	    } else {
	        echo '<script>app_alert("System Message", "Error inserting record", "error");</script>';	    }
	}
	else
	{
		$insertQuery = "INSERT INTO store_charges_data (branch,report_date,shift,idcode,employee_name,supervisor,category,item_name,item_id,quantity,unit_price,total,date_created) VALUES ('$branch', '$transdate', '$shift', '$idcode', '$employeename', '$supervisor', '$category', '$itemname', '$itemid', '$qty', '$unitprice', '$amount', '$datecreated')";
        if (mysqli_query($db, $insertQuery)) {
        	
            echo '<script>
            		set_function("Inventory Record","inventory_record");
            		app_alert("System Message", "Record inserted successfully", "success");
            	</script>';
        } else {
            echo '<script>app_alert("System Message", "Error inserting record", "error");</script>';
        }

	}
}



if($mode == 'additemtoinventory') {
    $itemname = $_POST['itemname'];
    $category = $_POST['category'];
    $itemid = $_POST['itemid'];
    $appstore_appnameuser = $_SESSION['appstore_appnameuser'];

    $branch = $functions->AppBranch();
    $transdate = $functions->GetSession('branchdate');
    $shift = $functions->GetSession('shift');
    $time_stamp = date('Y-m-d H:i:s'); 
    
    $checkQuery = "SELECT * FROM store_inventory_record_data WHERE item_name = ? AND category = ? AND item_id = ? AND report_date = ? AND shift = ?";
    $stmt_check = $db->prepare($checkQuery);
    
    if ($stmt_check) {
        $stmt_check->bind_param("sssss", $itemname, $category, $itemid, $transdate, $shift);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        
        if ($result->num_rows > 0) {
            echo '<script>
                    set_function("Inventory Record","inventory_record");
                    app_alert("System Message","Item already exists!","warning","Ok","","");
                </script>';

            $stmt_check->close();
            exit;
        }
        
        $stmt_check->close();
    } else {
        echo "Error preparing check statement: " . $db->error;
        exit;
    }
    
    $stmt_insert = $db->prepare("INSERT INTO store_inventory_record_data (branch,report_date,shift,employee_name,category,item_name,item_id,date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt_insert) {
        $stmt_insert->bind_param("ssssssss", $branch, $transdate, $shift, $appstore_appnameuser, $category, $itemname, $itemid, $time_stamp);
        
        if ($stmt_insert->execute()) {
            echo '<script>
                    set_function("Inventory Record","inventory_record");
                    app_alert("System Message","Transfer Report Successfully Saved.","success","Ok","","");
                </script>';
        } else {
            echo "Error: " . $stmt_insert->error;
        }
        
        $stmt_insert->close();
    } else {
        echo "Error preparing insert statement: " . $db->error;
    }
}


if($mode == 'getValItemRequest'){
	 $itemname = $_POST['itemname'];
    
    $query ="SELECT * FROM store_items WHERE product_name=?";  
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $itemname);
    $stmt->execute();
    $result = $stmt->get_result();  
    
    if($ROWS = $result->fetch_assoc())  
    {
        $item = $ROWS['product_name'];
        $category_name = $ROWS['category_name'];
        $item_id = $ROWS['id'];
        
        echo '
            <script>

                var c = "'.$category_name.'";
                var i = "'.$item_id.'";
                $("#mycategory").val(c);
                $("#myitemid").val(i);
            </script>';
    }
    else {
        echo "Item not found";
    }
    $stmt->close();
}


if($mode == 'saveactualcount_new')
{
	$itemname = $_POST['itemname'];
    $category = $functions->GetItemCategory($itemname,$db);
    $qty = $_POST['qty'];
    $itemid = $_POST['itemid'];
    $branch = $_POST['branch'];
    $transdate = $_POST['transdate'];
    $shift = $_POST['shift'];

    $employeename = $_SESSION['appstore_appnameuser'];
    $datecreated = date("Y-m-d H:i:s");
    $unitprice = $functions->itemItemsPriceGet($itemid, $transdate, $db);
    
    $amount = $qty * $unitprice;
    
    
    $check_query = "SELECT * FROM store_pcount_data WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	$result = mysqli_query($db, $check_query);
	if (mysqli_num_rows($result) > 0) 
	{
		$updateQuery = "UPDATE store_pcount_data SET actual_count='$qty', date_updated='$datecreated', updated_by='$employeename' WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	    if(mysqli_query($db, $updateQuery)) {
	        echo "Quantity updated successfully";
	    } else {
	        echo "Error updating quantity";
	    }
	}
	else
	{
		$insertQuery = "INSERT INTO store_pcount_data (branch,report_date,shift,employee_name,category,item_name,item_id,actual_count,date_created) VALUES ('$branch', '$transdate', '$shift', '$employeename', '$category', '$itemname', '$itemid', '$qty', '$datecreated')";
        mysqli_query($db, $insertQuery);
	}
}

if($mode == 'savedamage_new')
{
	$itemname = $_POST['itemname'];
    $category = $functions->GetItemCategory($itemname,$db);
    $qty = $_POST['qty'];
    $itemid = $_POST['itemid'];
    $branch = $_POST['branch'];
    $transdate = $_POST['transdate'];
    $shift = $_POST['shift'];

    $employeename = $_SESSION['appstore_appnameuser'];
    $datecreated = date("Y-m-d H:i:s");
    $unitprice = $functions->itemItemsPriceGet($itemid, $transdate, $db);
    
    $amount = $qty * $unitprice;
    
    
    $check_query = "SELECT * FROM store_damage_data WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	$result = mysqli_query($db, $check_query);
	if (mysqli_num_rows($result) > 0) 
	{
		$updateQuery = "UPDATE store_damage_data SET quantity='$qty', amount = '$amount',date_updated='$datecreated', updated_by='$employeename' WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	    if(mysqli_query($db, $updateQuery)) {
	        echo "Quantity updated successfully";
	    } else {
	        echo "Error updating quantity";
	    }
	}
	else
	{
		$insertQuery = "INSERT INTO store_damage_data (branch,report_date,shift,employee_name,category,item_name,item_id,quantity,unit_price,amount,date_created) VALUES ('$branch', '$transdate', '$shift', '$employeename', '$category', '$itemname', '$itemid', '$qty', '$unitprice', '$amount', '$datecreated')";
        mysqli_query($db, $insertQuery);
	}
}

if($mode == 'savebadorder_new')
{
	$itemname = $_POST['itemname'];
    $category = $functions->GetItemCategory($itemname,$db);
    $qty = $_POST['qty'];
    $itemid = $_POST['itemid'];
    $branch = $_POST['branch'];
    $transdate = $_POST['transdate'];
    $shift = $_POST['shift'];

    $employeename = $_SESSION['appstore_appnameuser'];
    $datecreated = date("Y-m-d H:i:s");
    $unitprice = $functions->itemItemsPriceGet($itemid, $transdate, $db);
    
    $amount = $qty * $unitprice;
    
    
    $check_query = "SELECT * FROM store_badorder_data WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	$result = mysqli_query($db, $check_query);
	if (mysqli_num_rows($result) > 0) 
	{
		$updateQuery = "UPDATE store_badorder_data SET quantity='$qty', total = '$amount',date_updated='$datecreated', updated_by='$employeename' WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	    if(mysqli_query($db, $updateQuery)) {
	        echo "Quantity updated successfully";
	    } else {
	        echo "Error updating quantity";
	    }
	}
	else
	{
		$insertQuery = "INSERT INTO store_badorder_data (branch,report_date,shift,employee_name,category,item_name,item_id,quantity,unit_price,total,date_created) VALUES ('$branch', '$transdate', '$shift', '$employeename', '$category', '$itemname', '$itemid', '$qty', '$unitprice', '$amount', '$datecreated')";
        mysqli_query($db, $insertQuery);
	}
}

if($mode == 'savecharges_new')
{
	$itemname = $_POST['itemname'];
    $category = $functions->GetItemCategory($itemname,$db);
    $qty = $_POST['qty'];
    $itemid = $_POST['itemid'];
    $branch = $_POST['branch'];
    $transdate = $_POST['transdate'];
    $shift = $_POST['shift'];

    $employeename = $_SESSION['appstore_appnameuser'];
    $datecreated = date("Y-m-d H:i:s");
    $unitprice = $functions->itemItemsPriceGet($itemid, $transdate, $db);
    
    $amount = $qty * $unitprice;
    
    
    $check_query = "SELECT * FROM store_charges_data WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	$result = mysqli_query($db, $check_query);
	if (mysqli_num_rows($result) > 0) 
	{
		$updateQuery = "UPDATE store_charges_data SET quantity='$qty', total = '$amount',date_updated='$datecreated', updated_by='$employeename' WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	    if(mysqli_query($db, $updateQuery)) {
	        echo "Quantity updated successfully";
	    } else {
	        echo "Error updating quantity";
	    }
	}
	else
	{
		$insertQuery = "INSERT INTO store_charges_data (branch,report_date,shift,employee_name,category,item_name,item_id,quantity,unit_price,total,date_created) VALUES ('$branch', '$transdate', '$shift', '$employeename', '$category', '$itemname', '$itemid', '$qty', '$unitprice', '$amount', '$datecreated')";
        mysqli_query($db, $insertQuery);
	}
}

if($mode == 'savetransferin_new')
{
	$itemname = $_POST['itemname'];
    $category = $functions->GetItemCategory($itemname,$db);
    $qty = $_POST['qty'];
    $itemid = $_POST['itemid'];
    $branch = $_POST['branch'];
    $transdate = $_POST['transdate'];
    $shift = $_POST['shift'];

    $employeename = $_SESSION['appstore_appnameuser'];
    $datecreated = date("Y-m-d H:i:s");
    $unitprice = $functions->itemItemsPriceGet($itemid, $transdate, $db);
    
    $amount = $qty * $unitprice;
    
    
    $check_query = "SELECT * FROM store_transfer_data WHERE item_name = '$itemname' AND transfer_to = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	$result = mysqli_query($db, $check_query);
	if (mysqli_num_rows($result) > 0) 
	{
		$updateQuery = "UPDATE store_transfer_data SET quantity='$qty', amount = '$amount',date_updated='$datecreated', updated_by='$employeename' WHERE item_name = '$itemname' AND transfer_to = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	    if(mysqli_query($db, $updateQuery)) {
	        echo "Quantity updated successfully";
	    } else {
	        echo "Error updating quantity";
	    }
	}
	else
	{
		$insertQuery = "INSERT INTO store_transfer_data (report_date,shift,employee_name,category,item_name,item_id,quantity,unit_price,amount,transfer_to,date_created) VALUES ('$transdate', '$shift', '$employeename', '$category', '$itemname', '$itemid', '$qty', '$unitprice', '$amount', '$branch', '$datecreated')";
        mysqli_query($db, $insertQuery);
	}
}

if($mode=="savefrozendough_new")
{
    $itemname = $_POST['itemname'];
    $category = $functions->GetItemCategory($itemname,$db);
    $qty = $_POST['qty'];
    $itemid = $_POST['itemid'];
    $branch = $_POST['branch'];
    $transdate = $_POST['transdate'];
    $shift = $_POST['shift'];

    $employeename = $_SESSION['appstore_appnameuser'];
    $datecreated = date("Y-m-d H:i:s");
    $unitprice = $functions->itemItemsPriceGet($itemid, $transdate, $db);
	
	$check_query = "SELECT * FROM store_frozendough_data WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	$result = mysqli_query($db, $check_query);
	if (mysqli_num_rows($result) > 0) 
	{
		$updateQuery = "UPDATE store_frozendough_data SET actual_yield='$qty', date_updated='$datecreated', updated_by='$employeename' WHERE item_name = '$itemname' AND branch = '$branch' AND report_date = '$transdate' AND shift = '$shift'";
	    if(mysqli_query($db, $updateQuery)) {
	        echo "Quantity updated successfully";
	    } else {
	        echo "Error updating quantity";
	    }
	}
	else
	{
		$insertQuery = "INSERT INTO store_frozendough_data (branch,report_date,shift,employee_name,category,item_name,item_id,actual_yield,unit_price,date_created) VALUES ('$branch', '$transdate', '$shift', '$employeename', '$category', '$itemname', '$itemid', '$qty', '$unitprice', '$datecreated')";
        mysqli_query($db, $insertQuery);
	}
}

if($mode=='updatecashcount_new')
{
	$rowId = isset($_POST['rowId']) ? $_POST['rowId'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
    $totalamount = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : 0;

    $updateQuery = "UPDATE store_cashcount_data SET quantity = '$quantity', total_amount='$totalamount' WHERE id = '$rowId'";
    if(mysqli_query($db, $updateQuery)) {
        echo "Quantity updated successfully";
    } else {
        echo "Error updating quantity";
    }
}

if($mode == 'savecashcount_new') {
	    
    $data = $_POST['data'];
    $store_branch = $functions->AppBranch();
    $trans_date = $functions->GetSession('branchdate');
    $employeename = $_SESSION['appstore_appnameuser'];
    $datecreated = date("Y-m-d H:i:s");

    foreach($data as $row) {
        $denomination = $row['denomination'];
        $quantity = $row['quantity'];
        $totalAmount = $row['totalAmount'];

        $insertQuery = "INSERT INTO store_cashcount_data (branch, report_date, shift, employee_name, denomination, quantity, total_amount, date_created) VALUES ('$store_branch', '$trans_date', '$shift', '$employeename', '$denomination', '$quantity', '$totalAmount', '$datecreated')";
        mysqli_query($db, $insertQuery);
    }

    echo "Data saved successfully.";
}


if($mode == 'deletedatelocker')
{
	$queryDataDelete = "DELETE FROM store_datelock_checker WHERE branch='$branch' AND report_date='$transdate'";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				app_alert("System Message","Are you allowed to submit again on this date","success");
			</script>
		');
	} else {
		echo $db->error;
	}
}

if($mode=='updatepakati')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$quantity = $_POST['quantity'];
	$denomination = $_POST['dinomination'];
	$totalamount = $_POST['totalamount'];

	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		quantity='$quantity',
		denomination='$denomination',
		total_amount='$totalamount',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";
	$queryDataUpdate = "UPDATE store_pakati_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("pakati");
				app_alert("System Message","PAKATI Report Successfuly Updated.","success");
				editItem("edit","pakati","PAKATI","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}	
}
if($mode=='savepakati')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$quantity = $_POST['quantity'];
	$denomination = $_POST['dinomination'];
	$totalamount = $_POST['totalamount'];

	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$denomination','$quantity','$totalamount','$time_stamp')";
	$query = "INSERT INTO store_pakati_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,denomination,quantity,total_amount,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>				
				reload_data("pakati");
				app_alert("System Message","PAKATI Report Successfuly Save.","success");
				addItem("new","pakati","PAKATI");
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}	
}
if($mode=='updategrab')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['amount'];
	$remarks = $_POST['remarks'];
	$unit_price = $_POST['unit_price'];
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		category='$category',
		item_name='$itemname',
		quantity='$quantity',
		unit_price='$unit_price',
		total='$total',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";
	$queryDataUpdate = "UPDATE store_grab_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("badorder");
				app_alert("System Message","Grab Report Successfuly Updated.","success");
				editItem("edit","grab","GRAB","'.$rowid.'");
				
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	}
}

if($mode=='savegrab')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['amount'];
	$remarks = $_POST['remarks'];
	$unit_price = $_POST['unit_price'];	
	$time = '';
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$table = "store_gcash_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","grab","GRAB");</script>');
		exit();
	}
	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category','$item_id','$itemname','$quantity','$unit_price','$total','$time_stamp')";
	$query = "INSERT INTO store_grab_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,unit_price,total,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>		
				reload_data("grab");
				app_alert("System Message","GRAB Report Successfuly Save.","success");
				addItem("new","grab","GRAB");
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}
if($mode=='updategcash')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['amount'];
	$remarks = $_POST['remarks'];
	$unit_price = $_POST['unit_price'];
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		category='$category',
		item_name='$itemname',
		quantity='$quantity',
		unit_price='$unit_price',
		total='$total',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";
	$queryDataUpdate = "UPDATE store_gcash_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("badorder");
				app_alert("System Message","Gcash Report Successfuly Updated.","success");
				editItem("edit","gcash","GCASH","'.$rowid.'");
				
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	}
}

if($mode=='savegcash')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['amount'];
	$remarks = $_POST['remarks'];
	$unit_price = $_POST['unit_price'];	
	$time = '';
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$table = "store_gcash_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","gcash","GCASH");</script>');
		exit();
	}
	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category','$item_id','$itemname','$quantity','$unit_price','$total','$time_stamp')";
	$query = "INSERT INTO store_gcash_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,unit_price,total,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>		
				reload_data("badorder");
				app_alert("System Message","GCASH Report Successfuly Save.","success");
				addItem("new","gcash","GCASH");
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}
if($mode == 'confirmUserEncoder')
{
	$sendFrom = $_POST['sendFrom'];
	$rowid = $_POST['rowid'];
	$file_name = $_POST['file_name'];
	$table = 'store_'.$file_name.'_data';
	$encoder = $functions->GetSession('encoder');
	$supervisor = $functions->supervisor($table,$rowid,$db);
	$userLevel = $functions->GetSession('userlevel');
	
	if($sendFrom=='edit'){
		$transmode = $_POST['transmode'];
		$file_name = $_POST['file_name'];
		$title = $_POST['title'];
	}
	elseif($sendFrom=='delete'){
		$itemname = $_POST['itemname'];
	}
	else{
		echo '<script>app_alert("Deny Permission","This particular item is created by '.$supervisor.'.","warning","Ok","","");</script>';
	}

	if($userLevel > '11' && $userLevel < '100'){
		$extensionQuery = "";
	} else {
		$extensionQuery = "AND supervisor='$encoder'";
	}
	
	
	$sql = "SELECT * FROM $table WHERE id='$rowid' $extensionQuery";
	$result = mysqli_query($db, $sql);
	if (mysqli_num_rows($result) > 0) 
	{
		if($sendFrom=='edit'){
			print_r('
				<script>
					var transmode = "'.$transmode.'";
					var file_name = "'.$file_name.'";
					var title = "'.$title.'";
					var rowid = "'.$rowid.'";
					editItem(transmode,file_name,title,rowid);
				</script>
			');
		}
		elseif($sendFrom=='delete'){
			print_r('
				<script>
					var itemname  = "'.$itemname .'";
					var filename = "'.$file_name.'";
					var rowid = "'.$rowid.'";
					deleteItem(rowid,filename,itemname);
				</script>
			');
		}
		else{
			echo '<script>app_alert("Deny Permission","This particular item is created by '.$supervisor.'.","warning","Ok","","");</script>';
		}
	}
	else
	{
		echo '<script>app_alert("Deny Permission","This particular item is created by '.$supervisor.'.","warning","Ok","","");</script>';
	}

}

if($mode == 'deleteboinventorysumitem')
{
	$rowid = $_POST['rowid'];
	$queryDataDelete = "DELETE FROM store_boinventory_summary_data WHERE id='$rowid'";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				$("#" + sessionStorage.navcount).trigger("click");
			</script>
		');
	} else {
		echo $db->error;
	}
}

if($mode=='deleteboinventorysummari')
{
	$report_date = $_POST['report_date'];
	$branch = $_POST['branch'];	
	$shift = $_POST['shift'];
	
	$queryDataDelete = "DELETE FROM store_boinventory_summary_data WHERE report_date='$report_date' AND branch='$branch' AND shift='$shift' ";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				$("#" + sessionStorage.navcount).trigger("click");
			</script>
		');
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}

if($mode=='updateboinventorypcount')
{
	$tbl = 'store_boinventory_pcount_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$actual_count = $_POST['actual_count'];
	$units = $_POST['units'];
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	$update="time_covered='$timecovered',employee_name='$person',supervisor='$encoder',category='$category',item_id='$item_id',item_name='$itemname',actual_count='$actual_count',units='$units',updated_by='$updated_by',date_updated='$time_stamp'";	
	$queryDataUpdate = "UPDATE $tbl SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("boinventory_pcount");
				app_alert("System Message","B.O Inventory Physical Count Report Successfuly Updated.","success","Ok","","");
				editItem("edit","boinventory_pcount","B.O INVENTORY PHYSICAL COUNT","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
/* --------------------------------------------------------------------- */
if($mode=='saveboinventorypcount')
{
	$tbl = 'store_boinventory_pcount_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$actual_count = $_POST['actual_count'];
	$units  = $_POST['units'];

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	if($_SESSION['appstore_shifting'] == '2')
	{
		if($shift == 'FIRST SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'SECOND SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}
	}
	if($_SESSION['appstore_shifting'] == '3')
	{
		if($shift == 'FIRST SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'SECOND SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'THIRD SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'THIRD SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}
	}

	if($functions->CheckExistingItem($tbl,'',$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","boinventory_pcount","B.O INVENTORY PHYSICAL COUNT");</script>');
		exit();
	}
	
	$pcountdata[] = "('$item_id','$branch','$date','$shift','$timecovered', '$person','$encoder','$category','$itemname','$actual_count','$time_stamp','$from_shift','$to_shift','$to_shift_date','$units')";
	$query = "INSERT INTO $tbl (item_id,branch,report_date,shift,time_covered,employee_name,supervisor,category,item_name,actual_count,date_created,from_shift,to_shift,to_shift_date,units)";
	$query .= "VALUES ".implode(', ', $pcountdata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("boinventory_pcount");
				app_alert("System Message","B.O Inventory Physical Count Report Successfuly Save.","success","Ok","","");
				addItem("new","boinventory_pcount","B.O INVENTORY PHYSICAL COUNT");
			</script>
		';
		print_r($cmd);
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}
}

if($mode=='updateboinventorybadorder')
{
	$table = 'store_boinventory_badorder_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$timecovered = $_POST['timecovered'];
	$time = '';
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $_SESSION['appstore_appnameuser']; //$_POST['supervisor'];
	$update = "
		branch='$branch',report_date='$date',shift='$shift',time_covered='$timecovered', employee_name='$person',category='$category',item_id='$item_id',
		item_name='$itemname',actual_count='$weight',units='$units',date_updated='$time_stamp',updated_by='$updated_by'
	";
	$queryDataUpdate = "UPDATE store_boinventory_badorder_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				app_alert("System Message","Bad Order Report Successfuly Updated.","success","Ok","","");
				editItem("edit","boinventory_badorder","SCRAP INVENTORY BAD ORDER","'.$rowid.'");
			</script>
		';
		print_r($cmd);
	} else {
		print_r('
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","","");
			</script>
		');
	}
}

/* ----------------------------------------------------------------------- */
if($mode=='saveboinventorybadorder')
{
	$table = 'store_boinventory_badorder_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$timecovered = $_POST['timecovered'];
	$time = '';
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","badorder","BAD ORDER");</script>');
		exit();
	}

	$data[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$time_stamp')";
	$query = "INSERT INTO store_boinventory_badorder_data (branch,report_date,shift,time_covered,employee_name,supervisor,category,item_id,item_name,actual_count,units,date_created)";
	$query .= "VALUES ".implode(', ', $data);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("boinventory_badorder");
				app_alert("System Message","B.O Inventory Bad Order Report Successfuly Save.","success","Ok","","");
				addItem("new","boinventory_badorder","B.O INVENTORY BAD ORDER");
			</script>
		';
		print_r($cmd);
		
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}	
}

if($mode == 'updateboinventorytransfer')
{
	$table = 'store_boinventory_transfer_data';

	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$to_branch = $_POST['to_branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$transfermode = $_POST['transfermode'];
	$time = '';
	$updated_by = $functions->GetSession('encoder');
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
		
	$update = "branch='$branch',report_date='$date',shift='$shift',time_covered ='$timecovered', employee_name='$person',supervisor='$encoder',item_id='$item_id',
	item_name='$itemname',weight='$weight',units='$units',transfer_to='$to_branch',date_updated ='$time_stamp',updated_by ='$updated_by'";

	$queryDataUpdate = "UPDATE store_boinventory_transfer_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		if($_SESSION["OFFLINE_MODE"] == 0)
		{
			$remQueryDataUpdate = "UPDATE store_remote_boinventory_transfer SET $update WHERE bid='$rowid'";
			if($conn->query($remQueryDataUpdate)=== TRUE){} else {}
		}
		$cmd = '';
		$cmd .= '
			<script>
				app_alert("System Message","Transfer Report Successfuly Updated.","success","Ok","","");
				editItem("edit","boinventory_transfer","BO INVENTORY TRANSFER","'.$rowid.'");
			</script>
		';
		print_r($cmd);
	} else {
		print_r('
			<script>
				var dberror = "'.$db->error.'";
				app_alert("Error Message",dberror,"success","Ok","","");
			</script>
		');
	}
}

if($mode == 'saveboinventorytransfer')
{
	$table = 'store_boinventory_transfer_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$to_branch = $_POST['to_branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$transfermode = $_POST['transfermode'];	
	$time = '';
	
	if($transfermode == 'TRANSFER IN')
	{
		$from_branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
		{
			echo $functions->GetMessageExist($itemname.' is already exists on this shift');
			exit();
		} else {
			$transferdata[] = "('$to_branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$from_branch','$time_stamp')";
		}
	}
	if($transfermode == 'TRANSFER OUT')
	{
		$from_branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
		{
			echo $functions->GetMessageExist($itemname.' is already exists on this shift');
			exit();
		} else {
			$transferdata[] = "('$from_branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$to_branch','$time_stamp')";
		}
	}
		
	$query = "INSERT INTO store_boinventory_transfer_data (branch,report_date,shift,time_covered,employee_name,supervisor,category,item_id,item_name,weight,units,transfer_to,date_created)";
	$query .= "VALUES ".implode(', ', $transferdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>
				reload_data("boinventory_transfer");
				app_alert("System Message","Transfer Report Successfuly Save.","success","Ok","","");
				addItem("new","boinventory_transfer","B.O INVENTORY TRANSFER");
			</script>
		';
		print_r($cmd);	
		if($transfermode == 'TRANSFER OUT')
		{
			$from_branch = $_POST['branch'];	
			$to_branch = $_POST['to_branch'];
			$table = "store_remote_transfer";
			$form_type = "BO INVENTORY";
			if($_SESSION["OFFLINE_MODE"] == 0)
			{
				 $functions->iTransferOutScrapInventory($rowid,$branch,$date,$shift,$timecovered,$person,$encoder,$category,$item_id,$itemname,$weight,$units,$to_branch,$time_stamp,$person,$time_stamp,$conn);
			}
		}		
	} else {
		print_r('
			<script>
				var dberror = "'.$db->error.'";
				app_alert("Error Message",dberror,"warning","Ok","","");
			</script>  
		');
	}	
}

/* ############################################### AUTOMATIC TRANSFER DETECTION - BO INVENTORY ################################################# */
if($mode=='boinventory_checktransfer')
{
	if($_SESSION["OFFLINE_MODE"] == 0)
	{
		$curr_date = $_SESSION['session_date'];
		$dataQuery ="SELECT * FROM store_remote_boinventorytransfer WHERE transfer_to='$branch' AND shift='$shift' AND report_date='$curr_date'";
		$dataResult = mysqli_query($conn, $dataQuery);  
		$cnt = $dataResult->num_rows; 
		if($dataResult->num_rows > 0)
		{
			$ronan=0;
			while($ROW = mysqli_fetch_array($dataResult))  
			{
				$ronan++;
				$id=$ROW['id'];$branch=$ROW['branch'];$report_date=$ROW['report_date'];$shift=$ROW['shift'];$time_covered=$ROW['time_covered'];
				$employee_name=$ROW['employee_name'];$supervisor=$ROW['supervisor'];$category=$ROW['category'];$item_name=$ROW['item_name'];
				$quantity=$ROW['weight'];$units=$ROW['units'];$amount=$ROW['amount'];$transfer_to=$ROW['transfer_to'];
				$date_created=$ROW['date_created'];$date_updated=$ROW['date_updated'];$updated_by=$ROW['updated_by'];$item_id=$ROW['item_id'];
				
				$insert = "'$id','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$item_id','$item_name','$quantity','$units','$transfer_to','$date_created'";
				
				$query ="SELECT * FROM store_boinventory_transfer_data WHERE tid='$id'";  
				$result = mysqli_query($db, $query);  
				if($result->num_rows === 0)
				{
					$column = "`tid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`";
	
					$queryInsert = "INSERT INTO store_boinventory_transfer_data ($column)";
					$queryInsert .= "VALUES($insert)";
					if ($db->query($queryInsert) === TRUE)
					{
						if($ronan==$cnt)
						{
							print_r('
								<script>
									swal("Success", "There are '.$cnt.' items Transfered to '.$branch.'");
									$("#" + sessionStorage.navcount).trigger("click");
									rms_reloaderOff();
								</script>
							');
						}
					} else {
						echo '<script>console.log("'.$db->error.'")</script>';;
					}		
				} else {
				}
			}
		} 
	} else {
		swal("Check Failed", "Check is not possible when offline");
	}
}
/* ############################################### AUTOMATIC TRANSFER DETECTION - SCRAP INVENTORY ################################################# */
if($mode=='scrapinventory_checktransfer')
{
	if($_SESSION["OFFLINE_MODE"] == 0)
	{
		$curr_date = $_SESSION['session_date'];
		$dataQuery ="SELECT * FROM store_remote_scrapinventorytransfer WHERE transfer_to='$branch' AND shift='$shift' AND report_date='$curr_date'";
		$dataResult = mysqli_query($conn, $dataQuery);  
		$cnt = $dataResult->num_rows; 
		if($dataResult->num_rows > 0)
		{
			$ronan=0;
			while($ROW = mysqli_fetch_array($dataResult))  
			{
				$ronan++;
				$id=$ROW['id'];$branch=$ROW['branch'];$report_date=$ROW['report_date'];$shift=$ROW['shift'];$time_covered=$ROW['time_covered'];
				$employee_name=$ROW['employee_name'];$supervisor=$ROW['supervisor'];$category=$ROW['category'];$item_name=$ROW['item_name'];
				$quantity=$ROW['weight'];$units=$ROW['units'];$amount=$ROW['amount'];$transfer_to=$ROW['transfer_to'];
				$date_created=$ROW['date_created'];$date_updated=$ROW['date_updated'];$updated_by=$ROW['updated_by'];$item_id=$ROW['item_id'];
				
				$insert = "'$id','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$item_id','$item_name','$quantity','$units','$transfer_to','$date_created'";
				
				$query ="SELECT * FROM store_scrapinventory_transfer_data WHERE tid='$id'";  
				$result = mysqli_query($db, $query);  
				if($result->num_rows === 0)
				{
					$column = "`tid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`";
	
					$queryInsert = "INSERT INTO store_scrapinventory_transfer_data ($column)";
					$queryInsert .= "VALUES($insert)";
					if ($db->query($queryInsert) === TRUE)
					{
						if($ronan==$cnt)
						{
							print_r('
								<script>
									swal("Success", "There are '.$cnt.' items Transfered to '.$branch.'");
									$("#" + sessionStorage.navcount).trigger("click");
									rms_reloaderOff();
								</script>
							');
						}
					} else {
						echo '<script>console.log("'.$db->error.'")</script>';;
					}		
				} else {
				}
			}
		} 
	} else {
		swal("Check Failed", "Check is not possible when offline");
	}
}

if($mode=='updateboinventoryreceiving')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];
	$prefix = $_POST['suppprefix'];
	$supplier = $_POST['supplier'];
	$invdr = $_POST['invdr'];
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		category='$category',
		item_name='$itemname',
		quantity='$quantity',
		units='$units',
		supplier='$supplier',
		invdr_no='$invdr',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";

	$queryDataUpdate = "UPDATE store_boinventory_receiving_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("boinventory_receiving");
				app_alert("System Message","B.O Inventory Receiving Report Successfuly Updated.","success");
				editItem("edit","boinventory_receiving","B.O Inventory RECEIVING","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}
}

if($mode=='saveboinventoryreceiving')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];
	$prefix = $_POST['suppprefix'];
	$supplier = $_POST['supplier'];
	$invdr = $_POST['invdr'];
	$time = '';

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
		
	$table = "store_boinventory_receiving_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","receiving","RECEIVING");</script>');
		exit();
	}

	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category','$item_id','$itemname','$quantity','$units','$prefix','$supplier','$invdr','$time_stamp')";
	$query = "INSERT INTO store_boinventory_receiving_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,units,supp_prefix,supplier,invdr_no,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>
				reload_data("boinventory_receiving");
				app_alert("System Message","B.O Inventory Receiving Report Successfuly Save.","success");
				addItem("new","scrapinventory_receiving","B.O INVENTORY RECEIVING");									
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}
if($mode == 'deletescrapinventorysumitem')
{
	$rowid = $_POST['rowid'];
	$queryDataDelete = "DELETE FROM store_scrapinventory_summary_data WHERE id='$rowid'";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				$("#" + sessionStorage.navcount).trigger("click");
			</script>
		');
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}

if($mode=='deletescrapinventorysummari')
{
	$report_date = $_POST['report_date'];
	$branch = $_POST['branch'];	
	$shift = $_POST['shift'];
	
	$queryDataDelete = "DELETE FROM store_scrapinventory_summary_data WHERE report_date='$report_date' AND branch='$branch' AND shift='$shift' ";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				$("#" + sessionStorage.navcount).trigger("click");
			</script>
		');
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}

if($mode=='updatescrapinventorypcount')
{
	$tbl = 'store_scrapinventory_pcount_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$actual_count = $_POST['actual_count'];
	$units = $_POST['units'];
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	$update="time_covered='$timecovered',employee_name='$person',supervisor='$encoder',category='$category',item_id='$item_id',item_name='$itemname',actual_count='$actual_count',units='$units',updated_by='$updated_by',date_updated='$time_stamp'";	
	$queryDataUpdate = "UPDATE $tbl SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("scrapinventory_pcount");
				app_alert("System Message","Scrap Inventory Physical Count Report Successfuly Updated.","success","Ok","","");
				editItem("edit","scrapinventory_pcount","SCRAP INVENTORY PHYSICAL COUNT","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
/* --------------------------------------------------------------------- */
if($mode=='savescrapinventorypcount')
{
	$tbl = 'store_scrapinventory_pcount_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$actual_count = $_POST['actual_count'];
	$units  = $_POST['units'];

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	if($_SESSION['appstore_shifting'] == '2')
	{
		if($shift == 'FIRST SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'SECOND SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}
	}
	if($_SESSION['appstore_shifting'] == '3')
	{
		if($shift == 'FIRST SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'SECOND SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'THIRD SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'THIRD SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}
	}

	if($functions->CheckExistingItem($tbl,'',$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","scrapinventory_pcount","SCRAP INVENTORY PHYSICAL COUNT");</script>');
		exit();
	}
	
	$pcountdata[] = "('$item_id','$branch','$date','$shift','$timecovered', '$person','$encoder','$category','$itemname','$actual_count','$time_stamp','$from_shift','$to_shift','$to_shift_date','$units')";
	$query = "INSERT INTO $tbl (item_id,branch,report_date,shift,time_covered,employee_name,supervisor,category,item_name,actual_count,date_created,from_shift,to_shift,to_shift_date,units)";
	$query .= "VALUES ".implode(', ', $pcountdata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("scrapinventory_pcount");
				app_alert("System Message","Scrap Inventory Physical Count Report Successfuly Save.","success","Ok","","");
				addItem("new","scrapinventory_pcount","SCRAP INVENTORY PHYSICAL COUNT");
			</script>
		';
		print_r($cmd);
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}
}

if($mode=='updatescrapinventorybadorder')
{
	$table = 'store_scrapinventory_badorder_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$timecovered = $_POST['timecovered'];
	$time = '';
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $_SESSION['appstore_appnameuser']; //$_POST['supervisor'];
	$update = "
		branch='$branch',report_date='$date',shift='$shift',time_covered='$timecovered', employee_name='$person',category='$category',item_id='$item_id',
		item_name='$itemname',actual_count='$weight',units='$units',date_updated='$time_stamp',updated_by='$updated_by'
	";
	$queryDataUpdate = "UPDATE store_scrapinventory_badorder_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				app_alert("System Message","Bad Order Report Successfuly Updated.","success","Ok","","");
				editItem("edit","scrapinventory_badorder","SCRAP INVENTORY BAD ORDER","'.$rowid.'");
			</script>
		';
		print_r($cmd);
	} else {
		print_r('
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","","");
			</script>
		');
	}
}

/* ----------------------------------------------------------------------- */
if($mode=='savescrapinventorybadorder')
{
	$table = 'store_scrapinventory_badorder_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$timecovered = $_POST['timecovered'];
	$time = '';
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","badorder","BAD ORDER");</script>');
		exit();
	}

	$data[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$time_stamp')";
	$query = "INSERT INTO store_scrapinventory_badorder_data (branch,report_date,shift,time_covered,employee_name,supervisor,category,item_id,item_name,actual_count,units,date_created)";
	$query .= "VALUES ".implode(', ', $data);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("rm_badorder");
				app_alert("System Message","Scrap Inventory Bad Order Report Successfuly Save.","success","Ok","","");
				addItem("new","scrapinventory_badorder","SCRAP INVENTORY BAD ORDER");
			</script>
		';
		print_r($cmd);
		
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}	
}

if($mode=='deleteprod')
{
	$report_date = $_POST['report_date'];
	$branch = $_POST['branch'];	
	$shift = $_POST['shift'];
	
	$mOnth = $_POST['mOnth'];
	$yEar = $_POST['yEar'];

	$queryDataDelete = "DELETE FROM store_production_data WHERE year='$yEar' AND branch='$branch' AND month='$mOnth' ";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				$("#" + sessionStorage.navcount).trigger("click");
			</script>
		');
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}

if($mode == 'updatescrapinventorytransfer')
{
	$table = 'store_scrapinventory_transfer_data';

	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$to_branch = $_POST['to_branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$transfermode = $_POST['transfermode'];
	$time = '';
	$updated_by = $functions->GetSession('encoder');
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
		
	$update = "branch='$branch',report_date='$date',shift='$shift',time_covered ='$timecovered', employee_name='$person',supervisor='$encoder',item_id='$item_id',
	item_name='$itemname',weight='$weight',units='$units',transfer_to='$to_branch',date_updated ='$time_stamp',updated_by ='$updated_by'";

	$queryDataUpdate = "UPDATE store_scrapinventory_transfer_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		if($_SESSION["OFFLINE_MODE"] == 0)
		{
			$remQueryDataUpdate = "UPDATE store_remote_scrapinventory_transfer SET $update WHERE bid='$rowid'";
			if($conn->query($remQueryDataUpdate)=== TRUE){} else {}
		}
		$cmd = '';
		$cmd .= '
			<script>
				app_alert("System Message","Transfer Report Successfuly Updated.","success","Ok","","");
				editItem("edit","scrapinventory_transfer","SCRAP INVENTORY TRANSFER","'.$rowid.'");
			</script>
		';
		print_r($cmd);
	} else {
		print_r('
			<script>
				var dberror = "'.$db->error.'";
				app_alert("Error Message",dberror,"success","Ok","","");
			</script>
		');
	}
}

if($mode == 'savescrapinventorytransfer')
{
	$table = 'store_scrapinventory_transfer_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$to_branch = $_POST['to_branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$transfermode = $_POST['transfermode'];	
	$time = '';
	
	if($transfermode == 'TRANSFER IN')
	{
		$from_branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
		{
			echo $functions->GetMessageExist($itemname.' is already exists on this shift');
			exit();
		} else {
			$transferdata[] = "('$to_branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$from_branch','$time_stamp')";
		}
	}
	if($transfermode == 'TRANSFER OUT')
	{
		$from_branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
		{
			echo $functions->GetMessageExist($itemname.' is already exists on this shift');
			exit();
		} else {
			$transferdata[] = "('$from_branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$to_branch','$time_stamp')";
		}
	}
	
	echo $transfermode.' ==============================';
	
	$query = "INSERT INTO store_scrapinventory_transfer_data (branch,report_date,shift,time_covered,employee_name,supervisor,category,item_id,item_name,weight,units,transfer_to,date_created)";
	$query .= "VALUES ".implode(', ', $transferdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>
				reload_data("scrapinventory_transfer");
				app_alert("System Message","Transfer Report Successfuly Save.","success","Ok","","");
				addItem("new","scrapinventory_transfer","SCRAP INVENTORY TRANSFER");
			</script>
		';
		print_r($cmd);	
		if($transfermode == 'TRANSFER OUT')
		{
			$from_branch = $_POST['branch'];	
			$to_branch = $_POST['to_branch'];
			$table = "store_remote_transfer";
			$form_type = "RAWMATS";
			if($_SESSION["OFFLINE_MODE"] == 0)
			{
				 $functions->iTransferOutScrapInventory($rowid,$branch,$date,$shift,$timecovered,$person,$encoder,$category,$item_id,$itemname,$weight,$units,$to_branch,$time_stamp,$person,$time_stamp,$conn);
			}
		}		
	} else {
		print_r('
			<script>
				var dberror = "'.$db->error.'";
				app_alert("Error Message",dberror,"warning","Ok","","");
			</script>  
		');
	}	
}
if($mode=='updatescrapinventoryreceiving')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];
	$prefix = $_POST['suppprefix'];
	$supplier = $_POST['supplier'];
	$invdr = $_POST['invdr'];
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		category='$category',
		item_name='$itemname',
		quantity='$quantity',
		units='$units',
		supplier='$supplier',
		invdr_no='$invdr',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";

	$queryDataUpdate = "UPDATE store_scrapinventory_receiving_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("scrapinventory_receiving");
				app_alert("System Message","Scrap Inventory Receiving Report Successfuly Updated.","success");
				editItem("edit","scrapinventory_receiving","Scrap Inventory RECEIVING","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}
}

if($mode=='savescrapinventoryreceiving')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];
	$prefix = $_POST['suppprefix'];
	$supplier = $_POST['supplier'];
	$invdr = $_POST['invdr'];
	$time = '';

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
		
	$table = "store_scrapinventory_receiving_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","receiving","RECEIVING");</script>');
		exit();
	}

	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category','$item_id','$itemname','$quantity','$units','$prefix','$supplier','$invdr','$time_stamp')";
	$query = "INSERT INTO store_scrapinventory_receiving_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,units,supp_prefix,supplier,invdr_no,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>
				reload_data("scrapinventory_receiving");
				app_alert("System Message","Scrap Inventory Receiving Report Successfuly Save.","success");
				addItem("new","scrapinventory_receiving","SCRAP INVENTORY RECEIVING");									
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}

if($mode=='deletesuppliessummari')
{
	$report_date = $_POST['report_date'];
	$branch = $_POST['branch'];	
	$shift = $_POST['shift'];
	
	$queryDataDelete = "DELETE FROM store_supplies_summary_data WHERE report_date='$report_date' AND branch='$branch' AND shift='$shift' ";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				$("#" + sessionStorage.navcount).trigger("click");
			</script>
		');
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}

if($mode == 'deletesuppliessumitem')
{
	$rowid = $_POST['rowid'];
	$queryDataDelete = "DELETE FROM store_supplies_summary_data WHERE id='$rowid'";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				$("#" + sessionStorage.navcount).trigger("click");
			</script>
		');
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}

/* #############################################  SUPPLIES BAD ORDER ##################################### */
if($mode=='updatesuppliesbadorder')
{
	$table = 'store_supplies_badorder_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$timecovered = $_POST['timecovered'];
	$time = '';
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $_SESSION['appstore_appnameuser']; //$_POST['supervisor'];
	$update = "
		branch='$branch',report_date='$date',shift='$shift',time_covered='$timecovered', employee_name='$person',category='$category',item_id='$item_id',
		item_name='$itemname',actual_count='$weight',units='$units',date_updated='$time_stamp',updated_by='$updated_by'
	";
	$queryDataUpdate = "UPDATE store_supplies_badorder_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				app_alert("System Message","Bad Order Report Successfuly Updated.","success","Ok","","");
				editItem("edit","supplies_badorder","SUPPLIES BAD ORDER","'.$rowid.'");
			</script>
		';
		print_r($cmd);
	} else {
		print_r('
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","","");
			</script>
		');
	}
}
if($mode=='savesuppliesbadorder')
{
	$table = 'store_supplies_badorder_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$timecovered = $_POST['timecovered'];
	$time = '';
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","badorder","BAD ORDER");</script>');
		exit();
	}

	$data[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$time_stamp')";
	$query = "INSERT INTO store_supplies_badorder_data (branch,report_date,shift,time_covered,employee_name,supervisor,category,item_id,item_name,actual_count,units,date_created)";
	$query .= "VALUES ".implode(', ', $data);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("supplies_badorder");
				app_alert("System Message","Supplies Bad Order Report Successfuly Save.","success","Ok","","");
				addItem("new","rm_badorder","SUPPLIES BAD ORDER");
			</script>
		';
		print_r($cmd);
		
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}	
}

/* ############################################### SUPLIES PHYSICAL COUNT ################################################# */
if($mode=='updatesuppliespcount')
{
	$tbl = 'store_supplies_pcount_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$actual_count = $_POST['actual_count'];
	$units = $_POST['units'];
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	$update="time_covered='$timecovered',employee_name='$person',supervisor='$encoder',category='$category',item_id='$item_id',item_name='$itemname',actual_count='$actual_count',units='$units',updated_by='$updated_by',date_updated='$time_stamp'";	
	$queryDataUpdate = "UPDATE $tbl SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("rm_pcount");
				app_alert("System Message","Supplies Physical Count Report Successfuly Updated.","success","Ok","","");
				editItem("edit","supplies_pcount","SUPPLIES PHYSICAL COUNT","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
/* --------------------------------------------------------------------- */
if($mode=='savesuppliespcount')
{
	$tbl = 'store_supplies_pcount_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$actual_count = $_POST['actual_count'];
	$units  = $_POST['units'];

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	if($_SESSION['appstore_shifting'] == '2')
	{
		if($shift == 'FIRST SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'SECOND SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}
	}
	if($_SESSION['appstore_shifting'] == '3')
	{
		if($shift == 'FIRST SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'SECOND SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'THIRD SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'THIRD SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}
	}

	if($functions->CheckExistingItem($tbl,'',$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","supplies_pcount","SUPPLIES PHYSICAL COUNT");</script>');
		exit();
	}
	
	$pcountdata[] = "('$item_id','$branch','$date','$shift','$timecovered', '$person','$encoder','$category','$itemname','$actual_count','$time_stamp','$from_shift','$to_shift','$to_shift_date','$units')";
	$query = "INSERT INTO $tbl (item_id,branch,report_date,shift,time_covered,employee_name,supervisor,category,item_name,actual_count,date_created,from_shift,to_shift,to_shift_date,units)";
	$query .= "VALUES ".implode(', ', $pcountdata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("rm_pcount");
				app_alert("System Message","Supplies Physical Count Report Successfuly Save.","success","Ok","","");
				addItem("new","supplies_pcount","SUPPLIES PHYSICAL COUNT");
			</script>
		';
		print_r($cmd);
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}
}

/* ############################################### AUTOMATIC TRANSFER DETECTION - SUPPLIES ################################################# */
if($mode=='supplies_checktransfer')
{
	if($_SESSION["OFFLINE_MODE"] == 0)
	{
		$curr_date = $_SESSION['session_date'];
		$dataQuery ="SELECT * FROM store_remote_suppliestransfer WHERE transfer_to='$branch' AND shift='$shift' AND report_date='$curr_date'";
		$dataResult = mysqli_query($conn, $dataQuery);  
		$cnt = $dataResult->num_rows; 
		if($dataResult->num_rows > 0)
		{
			$ronan=0;
			while($ROW = mysqli_fetch_array($dataResult))  
			{
				$ronan++;
				$id=$ROW['id'];$branch=$ROW['branch'];$report_date=$ROW['report_date'];$shift=$ROW['shift'];$time_covered=$ROW['time_covered'];
				$employee_name=$ROW['employee_name'];$supervisor=$ROW['supervisor'];$category=$ROW['category'];$item_name=$ROW['item_name'];
				$quantity=$ROW['weight'];$units=$ROW['units'];$amount=$ROW['amount'];$transfer_to=$ROW['transfer_to'];
				$date_created=$ROW['date_created'];$date_updated=$ROW['date_updated'];$updated_by=$ROW['updated_by'];$item_id=$ROW['item_id'];
				
				$insert = "'$id','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$item_id','$item_name','$quantity','$units','$transfer_to','$date_created'";
				
				$query ="SELECT * FROM store_supplies_transfer_data WHERE tid='$id'";  
				$result = mysqli_query($db, $query);  
				if($result->num_rows === 0)
				{
					$column = "`tid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`";
	
					$queryInsert = "INSERT INTO store_supplies_transfer_data ($column)";
					$queryInsert .= "VALUES($insert)";
					if ($db->query($queryInsert) === TRUE)
					{
						if($ronan==$cnt)
						{
							print_r('
								<script>
									swal("Success", "There are '.$cnt.' items Transfered to '.$branch.'");
									$("#" + sessionStorage.navcount).trigger("click");
									rms_reloaderOff();
								</script>
							');
						}
					} else {
						echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
					}		
				} else {
				}
			}
		} 
	} else {
		swal("Check Failed", "Check is not possible when offline");
	}
}
/* #############################################  SUPPLIES TRANSFER ##################################### */
if($mode == 'suppliestransfertobranch')
{
	if($_SESSION["OFFLINE_MODE"] == 0)
	{
		$table = 'store_transfer_data';
		$rowid = $_POST['rowid'];
		$branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		$date = $_POST['date'];
		$shift = $_POST['shift'];
		$person = $_POST['person'];
		$encoder = $_POST['encoder'];
		$category = $_POST['category'];
		$item_id = $_POST['item_id'];
		$itemname = $_POST['itemname'];
		$quantity = $_POST['quantity'];
		$unit_price = $_POST['unit_price'];
		$amount = $_POST['amount'];
		$timecovered = $_POST['timecovered'];
		$transfermode = $_POST['transfermode'];
		$updated_by = $functions->GetSession('encoder');

		if($item_id == '')
		{
			$item_id = $functions->GetItemID($itemname,$db);
		}
	
		$from_branch = $_POST['branch'];	
		$to_branch = $_POST['to_branch'];
		$table = "store_remote_suppliestransfer";
		$form_type = "SUPPLIES";
		$functions->iTransferOutSupplies($table,$form_type,$rowid,$from_branch,$date,$shift,$timecovered,$person,$encoder,$category,$item_id,$itemname,$quantity,$unit_price,$amount,$to_branch,$time_stamp,$conn);
		print_r('
			<script>
				editItem("edit","transfer","TRANSFER","'.$rowid.'");
			</script>	
		');
	} else {
		swal("Transfer Failed","Transfer to branch is not possible when offline", "warning");
	}
}
/* ############################## Supplies transfer save ################################# */
if($mode == 'savesuppliestransfer')
{
	$table = 'store_supplies_transfer_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$to_branch = $_POST['to_branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$transfermode = $_POST['transfermode'];	
	$time = '';
	
	
	if($transfermode == 'TRANSFER IN')
	{
		$from_branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
		{
			echo $functions->GetMessageExist($itemname.' is already exists on this shift');
			exit();
		} else {
			$transferdata[] = "('$to_branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$from_branch','$time_stamp')";
		}
	}
	if($transfermode == 'TRANSFER OUT')
	{
		$from_branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
		{
			echo $functions->GetMessageExist($itemname.' is already exists on this shift');
			exit();
		} else {
			$transferdata[] = "('$from_branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$to_branch','$time_stamp')";
		}
	}
	$query = "INSERT INTO store_supplies_transfer_data (branch,report_date,shift,time_covered,employee_name,supervisor,category,item_id,item_name,weight,units,transfer_to,date_created)";
	$query .= "VALUES ".implode(', ', $transferdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>
				reload_data("supplies_transfer");
				app_alert("System Message","Transfer Report Successfuly Save.","success","Ok","","");
				addItem("new","supplies_transfer","SUPPLIES TRANSFER");
			</script>
		';
		print_r($cmd);	
		if($transfermode == 'TRANSFER OUT')
		{
			$from_branch = $_POST['branch'];	
			$to_branch = $_POST['to_branch'];
			$table = "store_remote_transfer";
			$form_type = "SUPPLIES";
			if($_SESSION["OFFLINE_MODE"] == 0)
			{
				 $functions->iTransferOutSupplies($rowid,$branch,$date,$shift,$timecovered,$person,$encoder,$category,$item_id,$itemname,$weight,$units,$to_branch,$time_stamp,$person,$time_stamp,$conn);
			}
		}		
	} else {
		print_r('
			<script>
				var dberror = "'.$db->error.'";
				app_alert("Error Message",dberror,"warning","Ok","","");
			</script>  
		');
	}	
}

/* ############################## Supplies receiving update ################################# */
if($mode=='updatesuppreceiving')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];
	$prefix = $_POST['suppprefix'];
	$supplier = $_POST['supplier'];
	$invdr = $_POST['invdr'];
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		category='$category',
		item_name='$itemname',
		quantity='$quantity',
		units='$units',
		supplier='$supplier',
		invdr_no='$invdr',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";

	$queryDataUpdate = "UPDATE store_supplies_receiving_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("receiving");
				app_alert("System Message","Receiving Report Successfuly Updated.","success");
				editItem("edit","receiving","RECEIVING","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}
}

/* ############################## Supplies receiving save ################################# */

if($mode=='savesuppreceiving')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];
	$prefix = $_POST['suppprefix'];
	$supplier = $_POST['supplier'];
	$invdr = $_POST['invdr'];
	$time = '';
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$table = "store_supplies_receiving_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","receiving","RECEIVING");</script>');
		exit();
	}

	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category','$item_id','$itemname','$quantity','$units','$prefix','$supplier','$invdr','$time_stamp')";
	$query = "INSERT INTO store_supplies_receiving_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,units,supp_prefix,supplier,invdr_no,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>
				reload_data("receiving");
				app_alert("System Message","Receiving Report Successfuly Save.","success");
				addItem("new","receiving","RECEIVING");									
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}

/* ############################################### DISCOUNT Reload ################################# */
if($mode=='discountDropdownReload')
{
	$dropdown = new DropDowns;
	
	// Get the selected discountType from the AJAX request
	$selectedDiscountType = $_POST['discountType'];
	
	// Generate the updated "CATEGORY" dropdown options based on the selected discountType
	$categoryDropdownOptions = $dropdown->DiscountItemCategory($selectedDiscountType);
	
	// Return the generated options as a response to the AJAX request
	echo $categoryDropdownOptions;

}

/* ############################################### SYSTEM TOOLS ################################# */
if($mode=='deleteDumitemIndiv')
{
	$rowid = $_POST['rowid'];
	$queryDataDelete = "DELETE FROM store_dum_data WHERE id='$rowid' ";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				set_function("Daily Usage Report","dum");
			</script>
		');
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
if($mode=='deletermsummari')
{
	$report_date = $_POST['report_date'];
	$branch = $_POST['branch'];	
	$shift = $_POST['shift'];
	
	$queryDataDelete = "DELETE FROM store_rm_summary_data WHERE report_date='$report_date' AND branch='$branch' AND shift='$shift' ";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				$("#" + sessionStorage.navcount).trigger("click");
			</script>
		');
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
if($mode=='deletesummari')
{
	$report_date = $_POST['report_date'];
	$branch = $_POST['branch'];	
	$shift = $_POST['shift'];
	
	$queryDataDelete = "DELETE FROM store_summary_data WHERE report_date='$report_date' AND branch='$branch' AND shift='$shift' ";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				$("#" + sessionStorage.navcount).trigger("click");
			</script>
		');
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}
/* ############################################### SYSTEM TOOLS ################################# */
if($mode=='getmoduleitems')
{
	$items = $_POST['items'];
	$table = $_POST['items'];
	$dataQuery ="
		UPDATE $table moditems
        INNER JOIN store_items items 
        ON moditems.item_name = items.product_name
		SET moditems.item_id = items.id
	";
	if ($db->query($dataQuery) === TRUE)
	{
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
if($mode=='getmodule')
{
	$module = $_POST['module'];
	echo $modyul->GetModuleItems($module);
	
}
/* ############################################### AUTOMATIC TRANSFER DETECTION -FGTS ################################################# */
if($mode=='checktransfer')
{
	if($_SESSION["OFFLINE_MODE"] == 0)
	{
		$curr_date = $_SESSION['session_date'];
		$dataQuery ="SELECT * FROM store_remote_transfer WHERE transfer_to='$branch' AND shift='$shift' AND report_date='$curr_date' AND transfer_type='FGTS'";
		$dataResult = mysqli_query($conn, $dataQuery);  
		$cnt = $dataResult->num_rows; 
		if($dataResult->num_rows > 0)
		{
			$ronan=0;
			while($ROW = mysqli_fetch_array($dataResult))  
			{
				$ronan++;
				$id=$ROW['id'];	$branch=$ROW['branch'];$report_date=$ROW['report_date'];$shift=$ROW['shift'];$time_covered=$ROW['time_covered'];
				$employee_name=$ROW['employee_name'];$supervisor=$ROW['supervisor'];$category=$ROW['category'];$item_id=$ROW['item_id'];$item_name=$ROW['item_name'];
				$quantity=$ROW['quantity'];$unit_price=$ROW['unit_price'];$amount=$ROW['amount'];$transfer_to=$ROW['transfer_to'];
				$date_created=$ROW['date_created'];$date_updated=$ROW['date_updated'];$updated_by=$ROW['updated_by'];$form_no=$ROW['form_no'];			
	
				$insert = "'$id','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$category','$item_id','$item_name','$quantity','$unit_price','$amount','$transfer_to','$date_created','$date_updated','$updated_by'";			
				$query ="SELECT * FROM store_transfer_data WHERE tid='$id'";  
				$result = mysqli_query($db, $query);  
				if($result->num_rows === 0)
				{
					$column = "`tid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`quantity`,`unit_price`,`amount`,`transfer_to`,`date_created`,`date_updated`,`updated_by`";
					$queryInsert = "INSERT INTO store_transfer_data ($column)";
					$queryInsert .= "VALUES($insert)";
					if ($db->query($queryInsert) === TRUE)
					{
						if($ronan==$cnt)
						{
							print_r('
								<script>
									swal("Success", "There are '.$cnt.' items Transfered to '.$branch.'");
									$("#" + sessionStorage.navcount).trigger("click");
									rms_reloaderOff();								
								</script>
							');
						}
					} else {
						echo $db->error;
					}		
				} else {
					echo "No Data";
				}
			}
		} 
	} else {
		swal("Check Failed", "Checking is not possible when Offline.");
	}
}
/* ############################################### AUTOMATIC TRANSFER DETECTION - RAMATS ################################################# */
if($mode=='rm_checktransfer')
{
	if($_SESSION["OFFLINE_MODE"] == 0)
	{
		$curr_date = $_SESSION['session_date'];
		$dataQuery ="SELECT * FROM store_remote_rmtransfer WHERE transfer_to='$branch' AND shift='$shift' AND report_date='$curr_date'";
		$dataResult = mysqli_query($conn, $dataQuery);  
		$cnt = $dataResult->num_rows; 
		if($dataResult->num_rows > 0)
		{
			$ronan=0;
			while($ROW = mysqli_fetch_array($dataResult))  
			{
				$ronan++;
				$id=$ROW['id'];$branch=$ROW['branch'];$report_date=$ROW['report_date'];$shift=$ROW['shift'];$time_covered=$ROW['time_covered'];
				$employee_name=$ROW['employee_name'];$supervisor=$ROW['supervisor'];$category=$ROW['category'];$item_name=$ROW['item_name'];
				$quantity=$ROW['weight'];$units=$ROW['units'];$amount=$ROW['amount'];$transfer_to=$ROW['transfer_to'];
				$date_created=$ROW['date_created'];$date_updated=$ROW['date_updated'];$updated_by=$ROW['updated_by'];$item_id=$ROW['item_id'];
				
				$insert = "'$id','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$item_id','$item_name','$quantity','$units','$transfer_to','$date_created'";
				
				$query ="SELECT * FROM store_rm_transfer_data WHERE tid='$id'";  
				$result = mysqli_query($db, $query);  
				if($result->num_rows === 0)
				{
					$column = "`tid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`";
	
					$queryInsert = "INSERT INTO store_rm_transfer_data ($column)";
					$queryInsert .= "VALUES($insert)";
					if ($db->query($queryInsert) === TRUE)
					{
						if($ronan==$cnt)
						{
							print_r('
								<script>
									swal("Success", "There are '.$cnt.' items Transfered to '.$branch.'");
									$("#" + sessionStorage.navcount).trigger("click");
									rms_reloaderOff();
								</script>
							');
						}
					} else {
						echo '<script>console.log("'.$db->error.'")</script>';;
					}		
				} else {
				}
			}
		} 
	} else {
		swal("Check Failed", "Check is not possible when offline");
	}
}
/* ############################################### DUM REPORT ################################################# */
if($mode=='calculatedum')
{
	$rowid = $_POST['rowid'];
	$values = $_POST['values'];
	$column = $_POST['column'];
	
	$queryDataUpdate = "UPDATE store_dum_data SET $column='$values' WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		calculte_row($rowid,$db);
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
function calculte_row($rowid,$db)
{
	$query ="SELECT * FROM store_dum_data WHERE id='$rowid'";
	$result = mysqli_query($db, $query);  
	if($result->num_rows > 0)
	{
		while($ROW = mysqli_fetch_array($result))  
		{
			$rowid = $ROW['id'];
			$branch = $ROW['branch'];
			$report_date = $ROW['report_date'];
			$shift = $ROW['shift'];
			$item_name = $ROW['item_name'];
			$beginning = $ROW['beginning'];
			$delivery = $ROW['delivery'];
			$transfer_in = $ROW['transfer_in'];
			$transfer_out = $ROW['transfer_out'];
			$counter_out = $ROW['counter_out'];
			$sub_total = $ROW['sub_total'];
			$actual_usage = $ROW['actual_usage'];
			$net_total = $ROW['net_total'];
			$physical_count = $ROW['physical_count'];
			$variance = $ROW['variance'];
			
			$subtotal = ($beginning + $delivery + $transfer_in - $transfer_out - $counter_out);
			$nettotal = ($subtotal - $actual_usage);			
			$variance = ($physical_count - $net_total);
			
			$queryDataUpdate = "UPDATE store_dum_data SET sub_total='$subtotal',net_total='$nettotal',variance='$variance' WHERE id='$rowid'";
			if ($db->query($queryDataUpdate) === TRUE)
			{
				print_r('
					<script>
						$("#" + sessionStorage.navcount).trigger("click");
					</script>
				');
			} else {
				echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
			}		
		}
	}
}
/* ############################################### RAWMATS PHYSICAL COUNT ################################################# */
if($mode=='updatermpcount')
{
	$tbl = 'store_rm_pcount_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$actual_count = $_POST['actual_count'];
	$units = $_POST['units'];
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	$update="time_covered='$timecovered',employee_name='$person',supervisor='$encoder',category='$category',item_id='$item_id',item_name='$itemname',actual_count='$actual_count',units='$units',updated_by='$updated_by',date_updated='$time_stamp'";	
	$queryDataUpdate = "UPDATE $tbl SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("rm_pcount");
				app_alert("System Message","Rawmats Physical Count Report Successfuly Updated.","success","Ok","","");
				editItem("edit","rm_pcount","RAWNATS PHYSICAL COUNT","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
/* --------------------------------------------------------------------- */
if($mode=='savermpcount')
{
	$tbl = 'store_rm_pcount_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$actual_count = $_POST['actual_count'];
	$units  = $_POST['units'];

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	if($_SESSION['appstore_shifting'] == '2')
	{
		if($shift == 'FIRST SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'SECOND SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}
	}
	if($_SESSION['appstore_shifting'] == '3')
	{
		if($shift == 'FIRST SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'SECOND SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'THIRD SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'THIRD SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}
	}

	if($functions->CheckExistingItem($tbl,'',$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","rm_pcount","RAWMATS PHYSICAL COUNT");</script>');
		exit();
	}
	
	$pcountdata[] = "('$item_id','$branch','$date','$shift','$timecovered', '$person','$encoder','$category','$itemname','$actual_count','$time_stamp','$from_shift','$to_shift','$to_shift_date','$units')";
	$query = "INSERT INTO $tbl (item_id,branch,report_date,shift,time_covered,employee_name,supervisor,category,item_name,actual_count,date_created,from_shift,to_shift,to_shift_date,units)";
	$query .= "VALUES ".implode(', ', $pcountdata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("rm_pcount");
				app_alert("System Message","Rawmats Physical Count Report Successfuly Save.","success","Ok","","");
				addItem("new","rm_pcount","RAWMATS PHYSICAL COUNT");
			</script>
		';
		print_r($cmd);
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}
}
/* #############################################  RAWMATS BAD ORDER ##################################### */
if($mode=='updatermbadorder')
{
	$table = 'store_rm_badorder_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$timecovered = $_POST['timecovered'];
	$time = '';
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $_SESSION['appstore_appnameuser']; //$_POST['supervisor'];
	$update = "
		branch='$branch',report_date='$date',shift='$shift',time_covered='$timecovered', employee_name='$person',category='$category',item_id='$item_id',
		item_name='$itemname',actual_count='$weight',units='$units',date_updated='$time_stamp',updated_by='$updated_by'
	";
	$queryDataUpdate = "UPDATE store_rm_badorder_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				app_alert("System Message","Bad Order Report Successfuly Updated.","success","Ok","","");
				editItem("edit","rm_badorder","RAWMATS BAD ORDER","'.$rowid.'");
			</script>
		';
		print_r($cmd);
	} else {
		print_r('
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","","");
			</script>
		');
	}
}

/* ----------------------------------------------------------------------- */
if($mode=='savermbadorder')
{
	$table = 'store_rm_badorder_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$timecovered = $_POST['timecovered'];
	$time = '';
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","badorder","BAD ORDER");</script>');
		exit();
	}

	$data[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$time_stamp')";
	$query = "INSERT INTO store_rm_badorder_data (branch,report_date,shift,time_covered,employee_name,supervisor,category,item_id,item_name,actual_count,units,date_created)";
	$query .= "VALUES ".implode(', ', $data);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("rm_badorder");
				app_alert("System Message","Rawmats Bad Order Report Successfuly Save.","success","Ok","","");
				addItem("new","rm_badorder","RAWMATS BAD ORDER");
			</script>
		';
		print_r($cmd);
		
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}	
}
/* #############################################  RAWMATS TRANSFER ##################################### */
if($mode == 'rmtransfertobranch')
{
	if($_SESSION["OFFLINE_MODE"] == 0)
	{
		$table = 'store_transfer_data';
		$rowid = $_POST['rowid'];
		$branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		$date = $_POST['date'];
		$shift = $_POST['shift'];
		$person = $_POST['person'];
		$encoder = $_POST['encoder'];
		$category = $_POST['category'];
		$item_id = $_POST['item_id'];
		$itemname = $_POST['itemname'];
		$quantity = $_POST['quantity'];
		$unit_price = $_POST['unit_price'];
		$amount = $_POST['amount'];
		$timecovered = $_POST['timecovered'];
		$transfermode = $_POST['transfermode'];
		$updated_by = $functions->GetSession('encoder');

		if($item_id == '')
		{
			$item_id = $functions->GetItemID($itemname,$db);
		}
	
		$from_branch = $_POST['branch'];	
		$to_branch = $_POST['to_branch'];
		$table = "store_remote_transfer";
		$form_type = "RAWMATS";
		$functions->iTransferOutRM($table,$form_type,$rowid,$from_branch,$date,$shift,$timecovered,$person,$encoder,$category,$item_id,$itemname,$quantity,$unit_price,$amount,$to_branch,$time_stamp,$conn);
		print_r('
			<script>
				editItem("edit","transfer","TRANSFER","'.$rowid.'");
			</script>	
		');
	} else {
		swal("Transfer Failed","Transfer to branch is not possible when offline", "warning");
	}
}
/* ------------------------------------------------------------------------------------------- */
if($mode == 'updatermtransfer')
{
	$table = 'store_rm_transfer_data';

	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$to_branch = $_POST['to_branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$transfermode = $_POST['transfermode'];
	$time = '';
	$updated_by = $functions->GetSession('encoder');
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
		
	$update = "branch='$branch',report_date='$date',shift='$shift',time_covered ='$timecovered', employee_name='$person',supervisor='$encoder',item_id='$item_id',
	item_name='$itemname',weight='$weight',units='$units',transfer_to='$to_branch',date_updated ='$time_stamp',updated_by ='$updated_by'";

	$queryDataUpdate = "UPDATE store_rm_transfer_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		if($_SESSION["OFFLINE_MODE"] == 0)
		{
			$remQueryDataUpdate = "UPDATE store_remote_rm_transfer SET $update WHERE bid='$rowid'";
			if($conn->query($remQueryDataUpdate)=== TRUE){} else {}
		}
		$cmd = '';
		$cmd .= '
			<script>
				app_alert("System Message","Transfer Report Successfuly Updated.","success","Ok","","");
				editItem("edit","rm_transfer","RAWMATS TRANSFER","'.$rowid.'");
			</script>
		';
		print_r($cmd);
	} else {
		print_r('
			<script>
				var dberror = "'.$db->error.'";
				app_alert("Error Message",dberror,"success","Ok","","");
			</script>
		');
	}
}
/* ------------------------------------------------------------------------------------------- */
if($mode == 'savermtransfer')
{
	$table = 'store_rm_transfer_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$to_branch = $_POST['to_branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$weight = $_POST['weight'];
	$units = $_POST['units'];
	$transfermode = $_POST['transfermode'];	
	$time = '';
	
	
	if($transfermode == 'TRANSFER IN')
	{
		$from_branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
		{
			echo $functions->GetMessageExist($itemname.' is already exists on this shift');
			exit();
		} else {
			$transferdata[] = "('$to_branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$from_branch','$time_stamp')";
		}
	}
	if($transfermode == 'TRANSFER OUT')
	{
		$from_branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
		{
			echo $functions->GetMessageExist($itemname.' is already exists on this shift');
			exit();
		} else {
			$transferdata[] = "('$from_branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname','$weight','$units','$to_branch','$time_stamp')";
		}
	}
	$query = "INSERT INTO store_rm_transfer_data (branch,report_date,shift,time_covered,employee_name,supervisor,category,item_id,item_name,weight,units,transfer_to,date_created)";
	$query .= "VALUES ".implode(', ', $transferdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>
				reload_data("rm_transfer");
				app_alert("System Message","Transfer Report Successfuly Save.","success","Ok","","");
				addItem("new","rm_transfer","RAWMATS TRANSFER");
			</script>
		';
		print_r($cmd);	
		if($transfermode == 'TRANSFER OUT')
		{
			$from_branch = $_POST['branch'];	
			$to_branch = $_POST['to_branch'];
			$table = "store_remote_transfer";
			$form_type = "RAWMATS";
			if($_SESSION["OFFLINE_MODE"] == 0)
			{
				 $functions->iTransferOutRM($rowid,$branch,$date,$shift,$timecovered,$person,$encoder,$category,$item_id,$itemname,$weight,$units,$to_branch,$time_stamp,$person,$time_stamp,$conn);
			}
		}		
	} else {
		print_r('
			<script>
				var dberror = "'.$db->error.'";
				app_alert("Error Message",dberror,"warning","Ok","","");
			</script>  
		');
	}	
}
/* ############################################## UPDATE RAWMATS RECEIVING ############################################# */
if($mode=='updatermreceiving')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];
	$prefix = $_POST['suppprefix'];
	$supplier = $_POST['supplier'];
	$invdr = $_POST['invdr'];
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		category='$category',
		item_name='$itemname',
		quantity='$quantity',
		units='$units',
		supplier='$supplier',
		invdr_no='$invdr',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";

	$queryDataUpdate = "UPDATE store_rm_receiving_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("rm_receiving");
				app_alert("System Message","Rawmats Receiving Report Successfuly Updated.","success");
				editItem("edit","rm_receiving","RAWMATS RECEIVING","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}
}
/* ------------------------------------------------------------------------------------- */
if($mode=='savermreceiving')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];
	$prefix = $_POST['suppprefix'];
	$supplier = $_POST['supplier'];
	$invdr = $_POST['invdr'];
	$time = '';

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
		
	$table = "store_rm_receiving_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","receiving","RECEIVING");</script>');
		exit();
	}

	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category','$item_id','$itemname','$quantity','$units','$prefix','$supplier','$invdr','$time_stamp')";
	$query = "INSERT INTO store_rm_receiving_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,units,supp_prefix,supplier,invdr_no,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>
				reload_data("rm_receiving");
				app_alert("System Message","Rawmats Receiving Report Successfuly Save.","success");
				addItem("new","rm_receiving","RAWMATS RECEIVING");									
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}
/* ################################################################################################################ */
/* ################################################################################################################ */
/* ################################################################################################################ */
/* ############################################## DELETE SUMMARY ITEM ############################################# */
if($mode == 'deletesumitem')
{
	$rowid = $_POST['rowid'];
	$queryDataDelete = "DELETE FROM store_summary_data WHERE id='$rowid'";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				$("#" + sessionStorage.navcount).trigger("click");
			</script>
		');
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
if($mode == 'deletermsumitem')
{
	$rowid = $_POST['rowid'];
	$queryDataDelete = "DELETE FROM store_rm_summary_data WHERE id='$rowid'";
	if ($db->query($queryDataDelete) === TRUE)
	{ 
		print_r('
			<script>
				$("#" + sessionStorage.navcount).trigger("click");
			</script>
		');
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
/* ################################################## DISCOUNT #################################################### */
if($mode=='updatediscount')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$encoder = $_POST['encoder'];
	$discountType = $_POST['discountType'];
	$category = $_POST['category'];
	$item_name = $_POST['item_name'];
	$discount = $_POST['discount'];
	
		
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	$update = "branch='$branch',report_date='$date',shift='$shift',discount_type='$discountType',category='$category',item_name='$item_name',discount='$discount',date_updated='$time_stamp',updated_by='$updated_by'";
	$queryDataUpdate = "UPDATE store_discount_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("discount");
				app_alert("System Message","Discount Report Successfuly Updated.","success","Ok","","");
				editItem("edit","discount","DISCOUNT","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}
}
/* ----------------------------------------------------------------------------------------- */
if($mode=="savediscount")
{
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$encoder = $_POST['encoder'];
	$discountType = $_POST['discountType'];
	$category = $_POST['category'];
	$item_name = $_POST['item_name'];
	$discount = $_POST['discount'];
	
	
	$fgtsdata[] = "('$branch','$date','$shift','$discountType','$category','$item_name','$encoder','$discount','$time_stamp')";
	$query = "INSERT INTO store_discount_data (branch,report_date,shift,discount_type,category,item_name,supervisor,discount,date_created)";
	$query .= "VALUES ".implode(', ', $fgtsdata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("discount");
				app_alert("System Message","Discount Report Successfuly Save.","success","Ok","","");
				addItem("new","discount","DISCOUNT");
			</script>
		';
		print_r($cmd);
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}
}
/* ############################################### PHYSICAL COUNT ################################################# */
if($mode=='updatepcount')
{
	$tbl = 'store_pcount_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$actual_count = $_POST['actual_count'];
	$updated_by = $functions->GetSession('encoder');
	$to_shift = $_POST['to_shift'];
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				editItem("edit","pcount","PHYSICAL COUNT","'.$rowid.'");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	if($_SESSION['appstore_shifting'] == 2)
	{
		if($shift == 'FIRST SHIFT')		
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		if($shift == 'SECOND SHIFT')		
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}
	}
	
	if($_SESSION['appstore_shifting'] == 3)
	{
		if($shift == 'FIRST SHIFT')		
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		if($shift == 'SECOND SHIFT')		
		{
			$from_shift = $date;
			$to_shift = 'THIRD SHIFT';
			$to_shift_date = $date;
		}
		if($shift == 'THIRD SHIFT')		
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}			
	}		
	$update="time_covered='$timecovered',employee_name='$person',supervisor='$encoder',category='$category',item_id='$item_id',item_name='$itemname',actual_count='$actual_count',from_shift='$from_shift',to_shift='$to_shift',to_shift_date='$to_shift_date',updated_by='$updated_by',date_updated='$time_stamp'";

	$queryDataUpdate = "UPDATE $tbl SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("pcount");
				app_alert("System Message","Physical Count Report Successfuly Updated.","success","Ok","","");
				editItem("edit","pcount","PHYSICAL COUNT","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
/* --------------------------------------------------------------------- */
if($mode=='savepcount')
{
	$tbl = 'store_pcount_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$actual_count = $_POST['actual_count'];
	


	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				addItem("new","pcount","PHYSICAL COUNT");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}



		
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	if($_SESSION['appstore_shifting'] == '2')
	{
		if($shift == 'FIRST SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'SECOND SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}
	}
	if($_SESSION['appstore_shifting'] == '3')
	{
		if($shift == 'FIRST SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'SECOND SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'SECOND SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'THIRD SHIFT';
			$to_shift_date = $date;
		}
		else if($shift == 'THIRD SHIFT')
		{
			$from_shift = $date;
			$to_shift = 'FIRST SHIFT';
			$v_date = new DateTime('+1 day');
			$to_shift_date  = date('Y-m-d', strtotime($date. '+1 day'));
		}
	}
	
	if($functions->CheckExistingItem($tbl,'',$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","pcount","PHYSICAL COUNT");</script>');
		exit();
	}

	if($to_shift == '' || $to_shift_date == '')
	{
		print_r('
			<script>
				app_alert("Warning!","Your session has been Expired. System will go back to login page","warning","Ok","","sessionexpired");
			</script>		
		');
		exit();
	}
	
	$pcountdata[] = "('$item_id','$branch','$date','$shift','$timecovered', '$person','$encoder','$category','$itemname','$actual_count','$time_stamp','$from_shift','$to_shift','$to_shift_date')";
	$query = "INSERT INTO $tbl (item_id,branch,report_date,shift,time_covered,employee_name,supervisor,category,item_name,actual_count,date_created,from_shift,to_shift,to_shift_date)";
	$query .= "VALUES ".implode(', ', $pcountdata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				$("#posttosummarybtn").attr("disabled", false);
				reload_data("pcount");
				app_alert("System Message","Physical Count Report Successfuly Save.","success","Ok","","");
				addItem("new","pcount","PHYSICAL COUNT");
			</script>
		';
		print_r($cmd);
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}
}
/* ############################################### FROZEN DOUGH ################################################### */
if($mode=='updatefrozendough')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$time = $_POST['time'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$standard_yield = $_POST['standard_yield'];
	$actual_yield = $_POST['actual_yield'];
	$unit_price = $_POST['unit_price'];
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				editItem("edit","frozendough","FROZEN DOUGH","'.$rowid.'");	
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	$update = "
		branch='$branch',report_date='$date',shift='$shift',time_covered='$timecovered',employee_name='$person',category='$category',item_id='$item_id',
		item_name='$itemname',standard_yield='$standard_yield',actual_yield='$actual_yield',unit_price='$unit_price',
		slip_no='$slipno',date_updated='$time_stamp',updated_by='$updated_by'
	";
	$queryDataUpdate = "UPDATE store_frozendough_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("frozendough");
				app_alert("System Message","Frozen Dough Report Successfuly Updated.","success","Ok","","");
				editItem("edit","frozendough","FROZEN DOUGH","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
	} else {
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("System Message","'.$db->error.'","warning","Ok","slipno","no");
			</script>
		';
		print_r($cmd);
	}
}
/* ----------------------------------------------------------------------------------------- */
if($mode=="savefrozendough")
{
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$time = $_POST['time'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$kilo_used = $_POST['kilo_used'];
	$standard_yield = $_POST['standard_yield'];
	$actual_yield = $_POST['actual_yield'];
	$unit_price = $_POST['unit_price'];	
	$table = "store_fgts_data";

	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				addItem("new","frozendough","FROZENDOUGH");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","frozendough","FROZENDOUGH");</script>');
		exit();
	}

	$fgtsdata[] = "(
		'$branch','$date','$shift','$timecovered','$person','$encoder','$time','$category','$item_id','$itemname',
		'$standard_yield','$kilo_used','$actual_yield','$unit_price','$slipno','$time_stamp'
	)";
	
	$query = "INSERT INTO store_frozendough_data (branch,report_date,shift,time_covered,employee_name,supervisor,inputtime,category,item_id,item_name,standard_yield,kilo_used,actual_yield,unit_price,slip_no,date_created)";
	$query .= "VALUES ".implode(', ', $fgtsdata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("frozendough");
				app_alert("System Message","FROZEN DOUGh Report Successfuly Save.","success","Ok","","");
				addItem("new","frozendough","FROZENDOUGH");
			</script>
		';
		print_r($cmd);
	} else {
		$db_msg = $db->error;
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("Saving Error","'.$db_msg.'","warning","Ok","","");
			</script>
		';
		print_r($cmd);
	}
}
/* ############################################### CASH COUNT ################################################### */
if($mode=='updatecashcount')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$quantity = $_POST['quantity'];
	$denomination = $_POST['dinomination'];
	$totalamount = $_POST['totalamount'];

	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		quantity='$quantity',
		denomination='$denomination',
		total_amount='$totalamount',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";
	$queryDataUpdate = "UPDATE store_cashcount_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("cashcount");
				app_alert("System Message","Cash Count Report Successfuly Updated.","success");
				editItem("edit","cashcount","CASH COUNT","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}	
}
if($mode=='savecashcount')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$quantity = $_POST['quantity'];
	$denomination = $_POST['dinomination'];
	$totalamount = $_POST['totalamount'];

	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$denomination','$quantity','$totalamount','$time_stamp')";
	$query = "INSERT INTO store_cashcount_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,denomination,quantity,total_amount,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>				
				reload_data("cashcount");
				app_alert("System Message","Cash Count Report Successfuly Save.","success");
				addItem("new","cashcount","CASH COUNT");
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}	
}
/* ############################################### RECEIVING ################################################### */
if($mode=='updatereceiving')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];
	$prefix = $_POST['suppprefix'];
	$supplier = $_POST['supplier'];
	$invdr = $_POST['invdr'];
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				editItem("edit","receiving","RECEIVING","'.$rowid.'");	
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		category='$category',
		item_name='$itemname',
		quantity='$quantity',
		units='$units',
		supplier='$supplier',
		invdr_no='$invdr',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";

	$queryDataUpdate = "UPDATE store_receiving_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("receiving");
				app_alert("System Message","Receiving Report Successfuly Updated.","success");
				editItem("edit","receiving","RECEIVING","'.$rowid.'");	
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}
}
/* ------------------------------------------------------------------------------------- */
if($mode=='savereceiving')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];
	$prefix = $_POST['suppprefix'];
	$supplier = $_POST['supplier'];
	$invdr = $_POST['invdr'];
	$time = '';
	
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				addItem("new","receiving","RECEIVING");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$table = "store_receiving_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","receiving","RECEIVING");</script>');
		exit();
	}

	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category','$item_id','$itemname','$quantity','$units','$prefix','$supplier','$invdr','$time_stamp')";
	$query = "INSERT INTO store_receiving_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,units,supp_prefix,supplier,invdr_no,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>
				reload_data("receiving");
				app_alert("System Message","Receiving Report Successfuly Save.","success");
				addItem("new","receiving","RECEIVING");									
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}
/* ############################################### COMPLIMENTARY ################################################### */
if($mode=='updaterequest')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];	
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				editItem("edit","request","REQUEST","'.$rowid.'");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		category='$category',
		item_name='$searchitem',
		quantity='$quantity',
		units='$units',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";

	$queryDataUpdate = "UPDATE store_request_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("request");
				app_alert("System Message","Request Report Successfuly Updated.","success");
				editItem("edit","request","REQUEST","'.$rowid.'");				
			</script>
		';
		// print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}
}
/* -------------------------------------------------------------------------- */
if($mode=='saverequest')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$units = $_POST['uom'];
	$time = '';
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				addItem("new","request","REQUEST");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$table = "store_request_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","request","REQUEST");</script>');
		exit();
	}

	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category',$item_id,'$itemname','$quantity','$units','$time_stamp')";
	$query = "INSERT INTO store_request_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,units,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("damage");
				app_alert("System Message","Request Report Successfuly Save.","success");
				addItem("new","request","REQUEST");
			</script>
		';
		print_r($cmd);		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}
/* ############################################### COMPLIMENTARY ################################################### */
if($mode=='updatecomplimentary')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$amount = $_POST['amount'];
	$remarks = $_POST['remarks'];
	$unit_price = $_POST['unit_price'];
	
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				editItem("edit","complimentary","COMPLIMENTARY","'.$rowid.'");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];		
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		item_id='$item_id',
		item_name='$itemname',
		quantity='$quantity',
		unit_price='$unit_price',
		amount='$amount',
		remarks='$remarks',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";

	$queryDataUpdate = "UPDATE store_complimentary_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("complimentary");
				app_alert("System Message","Complimentary Report Successfuly Updated.","success");
				editItem("edit","complimentary","COMPLIMENTARY","'.$rowid.'")
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
/* ----------------------------------------------------------------------------------------- */
if($mode=='savecomplimentary')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['amount'];
	$unit_price = $_POST['unit_price'];
	$time = '';
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				addItem("new","complimentary","COMPLIMENTARY");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	$table = "store_complimentary_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","complimentary","COMPLIMENTARY");</script>');
		exit();
	}

	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category','$item_id','$itemname','$quantity','$unit_price','$amount','$remarks','$time_stamp')";
	$query = "INSERT INTO store_complimentary_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,unit_price,amount,remarks,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("complimentary");
				app_alert("System Message","Complimentary Report Successfuly Save.","success");
				addItem("new","complimentary","COMPLIMENTARY");
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}
/* ############################################### DAMAGE ################################################### */
if($mode=='updatedamage')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['amount'];
	$unit_price = $_POST['unit_price'];

	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				editItem("edit","damage","DAMAGE","'.$rowid.'");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];	
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		category='$category',
		item_id='$item_id',
		item_name='$itemname',
		quantity='$quantity',
		unit_price='$unit_price',
		amount='$total'
	";
	$queryDataUpdate = "UPDATE store_damage_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>				
				reload_data("damage");
				app_alert("System Message","Damaged Report Successfuly Updated.","success");
				editItem("edit","damage","DAMAGE","'.$rowid.'");
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
if($mode=='savedamage')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['amount'];
	$unit_price = $_POST['unit_price'];
	$time = '';
	
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				addItem("new","damage","DAMAGE");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$table = "store_damage_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","damage","DAGAME");</script>');
		exit();
	}
	
	$damagedata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category','$item_id','$itemname','$quantity','$unit_price','$total','$time_stamp')";
	$query = "INSERT INTO store_damage_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,unit_price,amount,date_created)";
	$query .= "VALUES ".implode(', ', $damagedata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("damage");
				app_alert("System Message","Damaged Report Successfuly Save.","success");
				addItem("new","damage","DAMAGE");
			</script>
		';
		print_r($cmd);
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}
/* ############################################### BAD ORDER ################################################### */
if($mode=='updatebadorder')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['amount'];
	$remarks = $_POST['remarks'];
	$unit_price = $_POST['unit_price'];
	

	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				editItem("edit","badorder","BADORDER","'.$rowid.'");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		category='$category',
		item_name='$itemname',
		quantity='$quantity',
		unit_price='$unit_price',
		total='$total',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";
	$queryDataUpdate = "UPDATE store_badorder_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("badorder");
				app_alert("System Message","Bad Order Report Successfuly Updated.","success");
				editItem("edit","badorder","BADORDER","'.$rowid.'");
				
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	}
}
/* ----------------------------------------------------------------------------------------- */
if($mode=='savebadorder')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['amount'];
	$remarks = $_POST['remarks'];
	$unit_price = $_POST['unit_price'];	
	$time = '';
	

	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				addItem("new","badorder","BADORDER");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$table = "store_badorder_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","badorder","BADORDER");</script>');
		exit();
	}
	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category','$item_id','$itemname','$quantity','$unit_price','$total','$time_stamp')";
	$query = "INSERT INTO store_badorder_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,unit_price,total,date_created)";
	$query .= "VALUES ".implode(', ', $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>		
				reload_data("badorder");
				app_alert("System Message","Bad Order Report Successfuly Save.","success");
				addItem("new","badorder","BADORDER");
			</script>
		';
		print_r($cmd);
		
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}
/* ############################################# SNACKS ##################################### */
if($mode=='updatesnacks')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['amount'];
	$remarks = $_POST['remarks'];
	$unit_price = $_POST['unit_price'];	
	
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				editItem("edit","snacks","SNACKS","'.$rowid.'");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];
	$update = "
		branch='$branch',
		report_date='$date',
		shift='$shift',
		time_covered='$timecovered',
		employee_name='$person',
		supervisor='$encoder',
		slip_no='$slipno',
		category='$category',
		item_id='$item_id',
		item_name='$itemname',
		quantity='$quantity',
		unit_price='$unit_price',
		total='$total',
		updated_by ='$updated_by',
		date_updated ='$time_stamp'
	";
	$queryDataUpdate = "UPDATE store_snacks_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>				
				reload_data("snacks");
				app_alert("System Message","Snacks Report Successfuly Updated.","success");
				editItem("edit","snacks","SNACKS","'.$rowid.'");
			</script>
		';
		print_r($cmd);
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}
}
/* ----------------------------------------------------------------------------------------- */
if($mode=='savesnacks')
{
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['total'];
	$remarks = $_POST['remarks'];
	$unit_price = $_POST['unit_price'];	
	$time = '';
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				addItem("new","snacks","SNACKS");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$table = "store_snacks_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","snacks","SNACKS");</script>');
		exit();
	}
	
	$chargesdata[] = "('$branch','$date','$shift','$timecovered','$person','$encoder','$slipno','$category','$item_id','$itemname','$quantity','$unit_price','$total','$date')";
	$query = "INSERT INTO store_snacks_data (branch,report_date,shift,time_covered,employee_name,supervisor,slip_no,category,item_id,item_name,quantity,unit_price,total,date_created)";
	$query .= "VALUES ".implode(', ', (array) $chargesdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>
				var rowid = '.$rowid.';				
				reload_data("snacks");
				app_alert("System Message","Snacks Report Successfuly Save.","success");
				addItem("new","snacks","SNACKS");
			</script>
		';
		print_r($cmd);
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';
	}	
}
/* ############################################# CHARGES ##################################### */
if($mode == 'updatecharges')
{
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['total'];
	$remarks = $_POST['remarks'];
	$unit_price = $_POST['unit_price'];	


	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				editItem("edit","charges","CHARGES","'.$rowid.'");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	$updated_by = $functions->GetSession('encoder'); //$_POST['supervisor'];
	$update = "
		time_covered='$timecovered', employee_name='$person',category='$category',item_id='$item_id',item_name='$itemname',quantity='$quantity',total='$total',
		remarks='$remarks',unit_price='$unit_price',slip_no='$slipno',date_updated='$time_stamp',updated_by='$updated_by'
	";
	$queryDataUpdate = "UPDATE store_charges_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("charges");
				app_alert("System Message","Charges Report Successfuly Updated.","success","Ok","","");
				editItem("edit","charges","CHARGES","'.$rowid.'");
			</script>
		';
		print_r($cmd);
	} else {
		print_r('
			<script>
				app_alert("System Message","Somethng is wrong ","warning","Ok","","");
			</script>
		');
	}	
}
/* ------------------------------------------------------------------------------------------- */
if($mode == 'savecharges')
{
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$total = $_POST['total'];
	$remarks = $_POST['remarks'];
	$unit_price = $_POST['unit_price'];	
	$time = '';


	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				addItem("new","charges","CHARGES");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}

	$table = "store_charges_data";
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","charges","CHARGES");</script>');
		exit();
	}

	$fgtsdata[] = "(
		'$branch','$date','$shift','$timecovered','$person','$encoder','$category','$item_id','$itemname',
		'$quantity','$total','$remarks','$unit_price','$slipno','$time_stamp'
	)";

	$query = "INSERT INTO store_charges_data (branch,report_date,shift,time_covered,employee_name,supervisor,category,item_id,item_name,quantity,total,remarks,unit_price,slip_no,date_created)";
	$query .= "VALUES ".implode(', ', $fgtsdata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("charges");
				app_alert("System Message","Charges Report Successfuly Save.","success","Ok","","");
				addItem("new","charges","CHARGES");
			</script>
		';
		print_r($cmd);
	} else {
		$db_msg = $db->error;
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("Saving Error","'.$db_msg.'","warning","Ok","","");
			</script>
		';
		print_r($cmd);
	}
}
/* ############################################# TRANSFER ##################################### */
if($mode == 'transfertobranch')
{
	if($_SESSION["OFFLINE_MODE"] == 0)
	{
		$table = 'store_transfer_data';
		$rowid = $_POST['rowid'];
		$branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		$date = $_POST['date'];
		$shift = $_POST['shift'];
		$person = $_POST['person'];
		$encoder = $_POST['encoder'];
		$category = $_POST['category'];
		$item_id = $_POST['item_id'];
		$itemname = $_POST['itemname'];
		$quantity = $_POST['quantity'];
		$unit_price = $_POST['unit_price'];
		$amount = $_POST['amount'];
		$timecovered = $_POST['timecovered'];
		$transfermode = $_POST['transfermode'];
		$updated_by = $functions->GetSession('encoder');

		if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
			echo '
				<script>
					var itemname = "'.$itemname.'";
					editItem("edit","transfer","TRANSFER","'.$rowid.'");
					app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
				</script>';
			exit();
		}

		if($item_id == '')
		{
			$item_id = $functions->GetItemID($itemname,$db);
		}
				
		$from_branch = $_POST['branch'];	
		$to_branch = $_POST['to_branch'];
		$table = "store_remote_transfer";
		$form_type = "FGTS";
		$functions->iTransferOut($table,$form_type,$rowid,$from_branch,$date,$shift,$timecovered,$person,$encoder,$category,$item_id,$itemname,$quantity,$unit_price,$amount,$to_branch,$time_stamp,$conn);
		print_r('
			<script>
				editItem("edit","transfer","TRANSFER","'.$rowid.'");
			</script>	
		');
	} else {
		swal("Transfer Failed", "Transfer is not possible when offline", "warning");
	}
}
/* ------------------------------------------------------------------------------------------- */
if($mode == 'updatetransfer')
{
	$table = 'store_transfer_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$to_branch = $_POST['to_branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$unit_price = $_POST['unit_price'];
	$amount = $_POST['amount'];
	$timecovered = $_POST['timecovered'];
	$transfermode = $_POST['transfermode'];
	$updated_by = $functions->GetSession('encoder');
	
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				editItem("edit","transfer","TRANSFER","'.$rowid.'");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	$update = "branch='$branch',report_date='$date',shift='$shift',time_covered ='$timecovered', employee_name='$person',supervisor='$encoder',item_id='$item_id',item_name='$itemname',quantity='$quantity',
	unit_price='$unit_price',amount='$amount',transfer_to='$to_branch',date_updated ='$time_stamp',updated_by ='$updated_by'";

	$queryDataUpdate = "UPDATE store_transfer_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		if($_SESSION["OFFLINE_MODE"] == 0)
		{	
			$remQueryDataUpdate = "UPDATE store_remote_transfer SET $update WHERE bid='$rowid'";
			if($conn->query($remQueryDataUpdate)=== TRUE){} else {}
		}
		$cmd = '';
		$cmd .= '
			<script>
				app_alert("System Message","Transfer Report Successfuly Updated.","success","Ok","","");
				editItem("edit","transfer","TRANSFER","'.$rowid.'");
			</script>
		';
		print_r($cmd);
	} else {
		print_r('
			<script>
				var dberror = "'.$db->error.'";
				app_alert("Error Message",dberror,"success","Ok","","");
			</script>
		');
	}
}
/* ------------------------------------------------------------------------------------------- */
if($mode == 'savetransfer')
{
	$table = 'store_transfer_data';
	$rowid = $_POST['rowid'];
	$branch = $_POST['branch'];
	$to_branch = $_POST['to_branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$category = $_POST['category'];
	$item_id = $_POST['item_id'];
	$itemname = $_POST['itemname'];
	$quantity = $_POST['quantity'];
	$unit_price = $_POST['unit_price'];
	$amount = $_POST['amount'];
	$timecovered = $_POST['timecovered'];
	$transfermode = $_POST['transfermode'];
	$itemid = $functions->GetPid($item_id,$db); /* GET ITEM ID */
	$time = '';	
	
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				addItem("new","transfer","TRANSFER");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	
	if($transfermode == 'TRANSFER IN')
	{
		$from_branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
		{
			echo $functions->GetMessageExist($itemname.' is already exists on this shift');
			exit();
		} else {
			$transferdata[] = "('$to_branch','$date','$shift','$timecovered','$person','$encoder','$category','$itemid','$itemname','$quantity','$unit_price','$amount','$from_branch','$time_stamp')";
		}
	}
	if($transfermode == 'TRANSFER OUT')
	{
		$from_branch = $_POST['branch'];
		$to_branch = $_POST['to_branch'];
		if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
		{
			echo $functions->GetMessageExist($itemname.' is already exists on this shift');
			exit();
		} else {
			$transferdata[] = "('$from_branch','$date','$shift','$timecovered','$person','$encoder','$category','$itemid','$itemname','$quantity','$unit_price','$amount','$to_branch','$time_stamp')";
		}
	}
	
	$query = "INSERT INTO store_transfer_data (branch,report_date,shift,time_covered,employee_name,supervisor,category,item_id,item_name,quantity,unit_price,amount,transfer_to,date_created)";
	$query .= "VALUES ".implode(', ', $transferdata);
	if ($db->query($query) === TRUE)
	{
		$rowid = $db->insert_id;
		$cmd = '';
		$cmd .='
			<script>
				$("#posttosummarybtn").attr("disabled", false);
				reload_data("transfer");
				app_alert("System Message","Transfer Report Successfuly Save.","success","Ok","","");
				addItem("new","transfer","TRANSFER");
			</script>
		';
		print_r($cmd);
		if($transfermode == 'TRANSFER OUT')
		{
			$from_branch = $_POST['branch'];	
			$to_branch = $_POST['to_branch'];
			$table = "store_remote_transfer";
			$form_type = "FGTS";	
			if($_SESSION["OFFLINE_MODE"] == 0)
			{
//				$functions->iTransferOut($table,$form_type,$rowid,$from_branch,$date,$shift,$timecovered,$person,$encoder,$category,$item_id,$itemname,$quantity,$unit_price,$amount,$to_branch,$time_stamp,$conn);
			}
		}		
	} else {
		print_r('
			<script>
				var dberror = "'.$db->error.'";
				app_alert("Error Message",dberror,"success","Ok","","");
			</script>
		');
	}	
}
/* ############################################# FGTS ##################################### */
if($mode == 'updatefgts')
{
	$rowid = $_POST['rowid'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$time = $_POST['time'];
	$category = $_POST['category'];
	$itemname  = $_POST['itemname'];
	$kilo_used = $_POST['kilo_used'];
	$standard_yield = $_POST['standard_yield'];
	$actual_yield = $_POST['actual_yield'];
	$unit_price = $_POST['unit_price'];	

	$item_id = $functions->GetItemID($itemname,$db);
	
	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				editItem("edit","fgts","FGTS","'.$rowid.'");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName");
			</script>';
		exit();
	}

	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}


	$updated_by = $functions->GetSession('encoder');
	$update = "
		time_covered='$timecovered', employee_name='$person',category='$category',item_id='$item_id',item_name='$itemname',standard_yield='$standard_yield',kilo_used='$kilo_used',
		actual_yield='$actual_yield',unit_price='$unit_price',slip_no='$slipno',inputtime='$time',date_updated='$time_stamp',updated_by='$updated_by'
	";
	$queryDataUpdate = "UPDATE store_fgts_data SET $update WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .= '
			<script>
				reload_data("fgts");
				app_alert("System Message","FGTS Report Successfuly Updated.","success","Ok","","");
				editItem("edit","fgts","FGTS","'.$rowid.'");
			</script>
		';
		print_r($cmd);
	} else {
		print_r('
			<script>
				app_alert("System Message","Somethng is wrong ","warning","Ok","","");
			</script>
		');
	}	
}
/* ------------------------------------------------------------------------------------------- */
if($mode == 'savefgts')
{
	$branch = $_POST['branch'];
	$date = $_POST['date'];
	$shift = $_POST['shift'];
	$timecovered = $_POST['timecovered'];
	$person = $_POST['person'];
	$encoder = $_POST['encoder'];
	$slipno = $_POST['slipno'];
	$time = $_POST['time'];
	$category = $_POST['category'];
	$item_id  = $_POST['item_id'];
	$itemname  = $_POST['itemname'];
	$kilo_used = $_POST['kilo_used'];
	$standard_yield = $_POST['standard_yield'];
	$actual_yield = $_POST['actual_yield'];
	$unit_price = $_POST['unit_price'];	
	$table = "store_fgts_data";

	if($functions->itemsItemsCheckingMatchingData($item_id,$itemname,$db) == 0){
		echo '
			<script>
				var itemname = "'.$itemname.'";
				addItem("new","fgts","FGTS");
				app_alert("System Message","The "+itemname+" Item Name and Item ID do not match according to the records.","warning","ok","itemname","errorItemName")
			</script>';
		exit();
	}
	
	if($item_id == '')
	{
		$item_id = $functions->GetItemID($itemname,$db);
	}
	if($functions->CheckExistingItem($table,$time,$branch,$date,$shift,$item_id,$db) == 1)
	{
		echo $functions->GetMessageExist($itemname.' is already exists on this shift');
		print_r('<script>addItem("new","fgts","FGTS");</script>');
		exit();
	}

	$fgtsdata[] = "(
		'$branch','$date','$shift','$timecovered','$person','$encoder','$time','$category','$item_id','$itemname',
		'$standard_yield','$kilo_used','$actual_yield','$unit_price','$slipno','$time_stamp'
	)";
	
	$query = "INSERT INTO store_fgts_data (branch,report_date,shift,time_covered,employee_name,supervisor,inputtime,category,item_id,item_name,standard_yield,kilo_used,actual_yield,unit_price,slip_no,date_created)";
	$query .= "VALUES ".implode(', ', $fgtsdata);
	if ($db->query($query) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				reload_data("fgts");
				app_alert("System Message","FGTS Report Successfuly Save.","success","Ok","","");
				addItem("new","fgts","FGTS");
			</script>
		';
		print_r($cmd);
	} else {
		$db_msg = $db->error;
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("Saving Error","'.$db_msg.'","warning","Ok","","");
			</script>
		';
		print_r($cmd);
	}
}
/* ############################################# UNLOCK POST ##################################### */
if($mode == 'unlockpost')
{
	$table = "store_".$_POST['database']."_data";
	if($_POST['database'] == 'transfer' || $_POST['database'] == 'rm_transfer')
	{	
		$q = "report_date='$transdate' AND branch='$branch' || transfer_to='$branch' AND shift='$shift'";
	} else {
		$q = "report_date='$transdate' AND branch='$branch' AND shift='$shift'";
	}
	$update = "Posted='No', status='Open'";
	$queryDataUpdate = "UPDATE $table SET $update WHERE $q ";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				app_alert("System Message","The Posts has been successfuly Unlocked.","success","Ok","","");
				$("#" + sessionStorage.navcount).click();
			</script>
		';
		print_r($cmd);	
	
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}
}
/* ############################################# DELETE ITEM ##################################### */
if($mode == 'deleteitem')
{
	$rowid = $_POST['rowid'];
	$file_name = $_POST['filename'];	
	$table = "store_".$file_name."_data";
	$q = $table;
	$deleteQuery = "DELETE FROM $table WHERE id='$rowid'";	
	
	if($file_name=='rm_transfer'){
		$q = 'store_remote_rmtransfer';
	}
	else if($file_name=='transfer'){
		$q = 'store_remote_transfer';
	}
	else if($file_name=='supplies_transfer')
	{
		$q = 'store_remote_suppliestransfer';
	}
	else if($file_name=='scrapinventory_transfer')
	{
		$q = 'store_remote_scrapinventorytransfer';
	}
	
	if ($db->query($deleteQuery) === TRUE)
	{
		// TRANSFER REMOTE DELETE DATA
		$queryRemote ="SELECT * FROM $q WHERE bid='$rowid' AND branch='$branch' AND report_date='$transdate' AND shift='$shift'";
		$remoteResult = mysqli_query($conn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{ 
			$queryDataDelete = "DELETE FROM $q WHERE bid='$rowid' AND branch='$branch' AND report_date='$transdate' AND shift='$shift'";
			if ($conn->query($queryDataDelete) === TRUE){ } else { echo $conn->error; }			
		}
		// TRANSFER REMOTE DELETE DATA
		
		$cmd = '';
		$cmd .='
			<script>
				reload_data("'.$file_name.'");
				app_alert("System Message","The file has been successfuly deleted.","success","Ok","","");
			</script>
		';
		print_r($cmd);		
	} else {
		$db_msg = $db->error;
		echo $db_message;
		$cmd = '';
		$cmd .='			
			<script>
				app_alert("Delete Error","'.$db_msg.'","warning","Ok","","");
			</script>
		';
		print_r($cmd);
	}
}
/* ############################################# ITEM INFO ##################################### */
if($mode == 'changethemes')
{
	$theme = $_POST['val'];
	$queryDataUpdate = "UPDATE store_settings SET theme_color='$theme' WHERE id=1 ";
	if ($db->query($queryDataUpdate) === TRUE)
	{
		$cmd = '';
		$cmd .='
			<script>
				app_alert("System Message","Theme successfuly Applied.","success","Ok","","settheme");
			</script>
		';
		print_r($cmd);	
	
	} else {
		echo '<script>app_alert("System Message","'.$db->error.'","warning");</script>';	
	}
}
if($mode == 'getiteminfo')
{
	$itemname = $_POST['itemname'];
	$query ="SELECT id,product_name,unit_price,unit_price_new,effectivity_date,yield_per_kilo FROM store_items WHERE product_name='$itemname'";  
	$result = mysqli_query($db, $query); 
	if ($result->num_rows > 0) {
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
				$functions->validateDateWithFormat($effectivity_date) ? $unit_price = $ROWS['unit_price_new']:$unit_price = $ROWS['unit_price'];
			}
			else{
				$unit_price = $ROWS['unit_price'];
			}
			print_r('
				<script>
					$("#itemid").val("'.$rowid.'");
					$("#unit_price").val("'.$unit_price.'");
					$("#standard_yield").val("'.$standard_yield.'");
				</script>		
			');
		}
	}
	else
	{
		echo $db->error;
	}
}
if($mode == 'getitems')
{
	$category = $_POST['category']; 
	$itemname = $_POST['itemname'];
	$branch = $functions->AppBranch();
	
	$branchLocation = $functions->branchCheckingIfVisayasArea($branch,$db);
	
	$query ="SELECT * FROM store_items WHERE (location='$branchLocation' OR location='GENERAL' OR location='' OR location IS NULL) AND (branch_only='$branch' OR branch_only='' OR branch_only IS NULL) AND category_name='$category' AND status='ACTIVE' AND product_name LIKE '%$itemname%' ORDER BY product_name ASC LIMIT 50";  
	$result = mysqli_query($db, $query); 
	 if ($result->num_rows > 0)
	 {
		while($ROWS = mysqli_fetch_array($result))  
		{
			$item = $ROWS['product_name'];		
			echo'<option value="'.$item.'".>';
		}
	}
	else
	{
		echo $db->error;
	}
}
