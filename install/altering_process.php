<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$tables = '*';
//Call the core function
backup_tables($dbhost, $dbuser, $dbpass, $dbname, $tables);

//Core function
function backup_tables($host, $user, $pass, $dbname, $tables = '*') {
    $link = mysqli_connect($host,$user,$pass, $dbname);

    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    mysqli_query($link, "SET NAMES 'utf8'");

    //get all of the tables
    if($tables == '*')
    {
        $tables = array();
        $result = mysqli_query($link, 'SHOW TABLES');
        while($row = mysqli_fetch_row($result))
        {
            $tables[] = $row[0];
        }
    }
    else
    {
        $tables = is_array($tables) ? $tables : explode(',',$tables);
    }

    $return = '';
    //cycle through
    foreach($tables as $table)
    {
        $result = mysqli_query($link, 'SELECT * FROM '.$table);
        $num_fields = mysqli_num_fields($result);
        $num_rows = mysqli_num_rows($result);

        $return.= 'DROP TABLE IF EXISTS '.$table.';';
        $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";
        $counter = 1;

        //Over tables
        for ($i = 0; $i < $num_fields; $i++) 
        {   //Over rows
            while($row = mysqli_fetch_row($result))
            {   
                if($counter == 1){
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                } else{
                    $return.= '(';
                }

                //Over fields
                for($j=0; $j<$num_fields; $j++) 
                {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n","\\n",$row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j<($num_fields-1)) { $return.= ','; }
                }

                if($num_rows == $counter){
                    $return.= ");\n";
                } else{
                    $return.= "),\n";
                }
                ++$counter;
            }
        }
        $return.="\n\n\n";
    }
	
	//craete folder if not exist
    
    if (!file_exists('D:/SQL BACK UP')) {
    	mkdir('D:/SQL BACK UP', 0777, true);
	}
    
    //save file
    
    $fileName = 'D:/SQL BACK UP/rosebakeshop_data-'.time().'-'.(md5(implode(',',$tables))).'.sql';
    $handle = fopen($fileName,'w+');
    fwrite($handle,$return);
    
    
    
    if(fclose($handle)){
        echo "Done, the file name is: ".$fileName."<br>";
         
    }
}

$alltable = array("store_badorder_data",
				"store_charges_data",
				"store_complimentary_data",
				"store_damage_data",
				"store_dum_data",
				"store_fgts_data",
				"store_frozendough_data",
				"store_pcount_data",
				"store_production_data",
				"store_receiving_data",
				"store_request_data",
				"store_rm_badorder_data",
				"store_rm_pcount_data",
				"store_rm_receiving_data",
				"store_rm_summary_data",
				"store_rm_transfer_data",
				"store_snacks_data",
				"store_summary_data",
				"store_supplies_janitorial_data",
				"store_supplies_office_data",
				"store_supplies_packaging_data",
				"store_supplies_pcount_data",
				"store_supplies_summary_data",
				"store_transfer_data");

$psa = count($alltable)-1;

$sql1 = "ALTER TABLE store_settings ADD theme_color int(1) not null default 0 AFTER remote_folder";
$result = $db->query($sql1);

for($i=0; $i <= $psa; $i++){
	echo $alltable[$i].' ADDED item_id column! <br>';
	addme($alltable[$i],$db);
	
	//////////////// SELECT DATA ////////////////////

	$tblname = $alltable[$i];
	$sqlselect = "SELECT * FROM $tblname";
	$resultselect = mysqli_query($db, $sqlselect);
	if (mysqli_num_rows($resultselect) > 0) {
	  // output data of each row
	  while($rowselect = mysqli_fetch_assoc($resultselect)) {
	  	
	  	$item_name = $rowselect['item_name'];
	  	$tableid = $rowselect['id'];
	  
	  	$itemid_store_items = itemid_value($item_name,$db);
	  	
	  	$sqlupdate = "UPDATE $tblname SET item_id='$itemid_store_items' WHERE id='$tableid'";
		if ($db->query($sqlupdate) === TRUE) {} else { echo "Error updating record: " . $db->error; }
	  }
	} 
	else {
	  echo "0 results";
	}	
}

////////////////////////////////NAVIGATION///////////////////////////////////////////


navigation($db);
$querynav = '';
$sqlScript = file('../.file/store_navigation.sql');
foreach ($sqlScript as $line)	{
	
	$startWith = substr(trim($line), 0 ,2);
	$endWith = substr(trim($line), -1 ,1);
	
	if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
		continue;
	}
		
	$querynav = $querynav. $line;
	if ($endWith == ';') {
		mysqli_query($db,$querynav) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $querynav. '</b></div>');
		$querynav= '';		
	}
}

$querydiscount = '';
$sqlScriptdiscount = file('../.file/store_discount_data.sql');
foreach ($sqlScriptdiscount as $linediscount)	{
	
	$startWith = substr(trim($linediscount), 0 ,2);
	$endWith = substr(trim($linediscount), -1 ,1);
	
	if (empty($linediscount) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
		continue;
	}
		
	$querydiscount = $querydiscount. $linediscount;
	if ($endWith == ';') {
		mysqli_query($db,$querydiscount) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $querydiscount. '</b></div>');
		$querydiscount= '';		
	}
}


echo '<div class="success-response sql-import-response">SQL file imported successfully</div>';


/////////////////////////// USERS CLUSTERS ////////////////////////////////////////

$sqlcluster = "ALTER TABLE tbl_system_user ADD cluster varchar(80)AFTER branch";
$resultcluster = $db->query($sqlcluster);


////////////////////// ALL FUNCTIONS /////////////////////////////////////////

function navigation($db){

	$sqldelete = "DELETE * FROM store_navigation";
	if ($db->query($sqldelete) === TRUE) {} else { echo "Error deleting record: " . $db->error; }
}
function addnavigationdata(){
	
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
function addme($table,$db)
{
	$sql = "ALTER TABLE $table ADD item_id int(11) AFTER id";
	$result = $db->query($sql);
}	
?>