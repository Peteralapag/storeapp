<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
include '../db_config_main.php';
$conn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);
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
if($mode == 'addcolumn')
{
	$table = $_POST['tablename'];
	$value = $_POST['command'];	
	addNewColumn($table,$value,$db);
}
if($mode == 'fixallids')
{
	/* SUMMARY DATA NOT INCLUDED */
	$tables = array(
		"store_badorder_data","store_charges_data","store_complimentary_data","store_damage_data","store_dum_data","store_fgts_data","store_frozendough_data","store_pcount_data","store_production_data",
		"store_receiving_data","store_request_data","store_summary_data","store_rm_badorder_data","store_rm_pcount_data","store_rm_receiving_data","store_rm_transfer_data","store_snacks_data","store_supplies_janitorial_data",
		"store_supplies_office_data","store_supplies_packaging_data","store_supplies_pcount_data","store_transfer_data"
	);
	$table_count = count($tables)-1;
	$n=0;
	for($i=0; $i <= $table_count; $i++)
	{
		$n++;
		addTables($tables[$i],$db);		
		$tblname = $tables[$i];
		$sqlselect = "SELECT * FROM $tblname";
		$resultselect = mysqli_query($db, $sqlselect);
		$cnt = $resultselect->num_rows; 
		if (mysqli_num_rows($resultselect) > 0) {
			while($rowselect = mysqli_fetch_assoc($resultselect))
			{	
				$item_name = $rowselect['item_name'];
				$tableid = $rowselect['id'];		
				$itemid_store_items = itemid_value($item_name,$db);
			
				$sqlupdate = "UPDATE $tblname SET item_id='$itemid_store_items' WHERE id='$tableid'";
				if ($db->query($sqlupdate) === TRUE) 
				{ 
				} 
				else
				{ 
					echo "Error updating record: " . $db->error; 
				}
			}
		} 
		if($table_count == $n)
		{
			navigation($table_count,$db,$conn);
			addClusterToUser($db);	
		}
	}
}

function navigation($table_count,$db,$conn)
{
	$table_name = 'store_navigation';
	$return = '';
    $result = mysqli_query($conn, "SELECT * FROM ".$table_name);
    $num_fields = mysqli_num_fields($result);
    
    $return .= 'DROP TABLE '.$table_name.';';
    $row2 = mysqli_fetch_row(mysqli_query($conn, 'SHOW CREATE TABLE '.$table_name));
    $return .= "\n\n".$row2[1].";\n\n";

    for ($i=0; $i < $num_fields; $i++)
    { 
    	while ($row = mysqli_fetch_row($result))
    	{
            $return .= 'INSERT INTO '.$table_name.' VALUES(';
            	for ($j=0; $j < $num_fields; $j++)
            	{ 
            	    $row[$j] = addslashes($row[$j]);
            	    if (isset($row[$j]))
            	    {
            	        $return .= '"'.$row[$j].'"';} else { $return .= '""';}
            	        if($j<$num_fields-1){ $return .= ',';
            	    }
                }
             $return .= ");\n";
        }
    }
    $return .= "\n\n\n";
	$handle = fopen('../updates/data/'.$table_name.".sql", 'w+');
	fwrite($handle, $return);
	fclose($handle);
	saveTableImport($table_count,$table_name,$db);
}
function saveTableImport($table_count,$table_name,$db)
{
	$query= '';
	$sqlScript = file('../updates/data/'.$table_name.'.sql');
	foreach ($sqlScript as $line)
	{
		$startWith = substr(trim($line), 0 ,2);
		$endWith = substr(trim($line), -1 ,1);
		
		if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
			continue;
		}
			
		$query = $query . $line;
		if ($endWith == ';') {
			mysqli_query($db,$query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
			$query= '';			
		}
		if(file_exists('../updates/data/'.$table_name.'.sql'))
		{
			unlink('../updates/data/'.$table_name.'.sql');
		}
	}
	echo "<hr><p style='margin-top:20px;text-align:center'>".$table_count. " Tables has been updated.<br>".$table_name." has been successfully Imported</p>"; 
	themeColor($db);
}
function themeColor($db)
{
	$sql = "ALTER TABLE store_settings ADD theme_color int(1) not null default 0 AFTER remote_folder";
	$result = $db->query($sql);
/*	if($result === true)
	{ } else { echo $db->error; } */
	addDiscount($db);
}
function addDiscount($db)
{
	if ($result = $db->query("SHOW TABLES LIKE 'store_discount_data'"))
	{
	    if($result->num_rows == 1)
	    {
    	   	$ex = 1;
    	} else {
	    	$ex = 0;
    	}
	} else {
	    echo "Table does not exist";
	    exit();
	}
	
	if($ex == 0)
	{
		$query= '';
		$sqlScript = file('../updates/data/store_discount_data.sql');
		foreach ($sqlScript as $line)
		{
			$startWith = substr(trim($line), 0 ,2);
			$endWith = substr(trim($line), -1 ,1);
			
			if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
				continue;
			}				
			$query = $query . $line;
			if ($endWith == ';') {
				mysqli_query($db,$query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
				$query= '';			
			}
			
		}
	}
}
function addClusterToUser($db)
{
	$sqlcluster = "ALTER TABLE tbl_system_user ADD cluster varchar(80) AFTER branch";
	$resultcluster = $db->query($sqlcluster);
}
function addTables($table,$db)
{
	$sql = "ALTER TABLE $table ADD item_id int(11) AFTER item_name";
	$result = $db->query($sql);	
}
if($mode == 'fixsummary')
{
	echo '<p style="text-align:center"><strong>SUMMARY FIXING PROCESS RESULTS</strong></p>';

	$includes = array(
		"CATEGORY" => "category varchar(80) NOT NULL after time_covered",
		"ITEM ID" => "item_id int(11) after category",
		"SUB TOTAL" => "sub_total double(11,2) DEFAULT '1' after transfer_in",
		"DIFFERENCE" => "difference double(11,2) after actual_count",
		"PRICE KG" => "price_kg double(11,2) after difference",
		"AMOUNT" => "amount double(11,2) after price_kg"
	);
	foreach ( $includes as $key => $value )
	{
		addColumn($value,$db);
	}
}
function itemid_value($item_name,$db){
	$val = 0;
	$sql = "SELECT * FROM store_items WHERE product_name='$item_name'";
	$result = mysqli_query($db, $sql);
	if (mysqli_num_rows($result) > 0) {
	  // output data of each row
	  while($row = mysqli_fetch_assoc($result)) {
	  	$val = $row["id"];
	  }
	  return $val;
	} 
	else{
	  return $val;
	}
}
function addNewColumn($table,$value,$db)
{
	$sql = "ALTER TABLE $table ADD $value";
	$result = $db->query($sql);
	if($result == true)
	{
		echo "Successfuly adding ".$value."<br>";
	} else { 
		echo $value." is Already Exist<br>";
	}
}
function addColumn($value,$db)
{
	$sql = "ALTER TABLE store_rm_summary_data ADD $value";
	$result = $db->query($sql);
	if($result == true)
	{
		echo "Successfuly adding ".$value."<br>";
	} else { 
		echo $value." is Already Exist<br>";
	}
}	

