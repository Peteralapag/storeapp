<?PHP
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
include '../db_config_main.php';
$conn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);
$functions = new TheFunctions;

$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$time_stamp = date("Y-m-d H:i:s");
if($_POST['mode'] == 'checkusers')
{	
	$query ="SELECT * FROM tbl_system_user WHERE branch='$branch'";
	$result = mysqli_query($conn, $query);  
	$x=mysqli_num_rows($result); 
	if($result->num_rows > 0)
	{
		$y=0;
		while($ROW = mysqli_fetch_array($result))  
		{
			$y++;		
			$id = $ROW['id'];
			$idcode = $ROW['idcode'];
			$firstname = $ROW['firstname'];
			$lastname = $ROW['lastname'];
			$username = $ROW['username'];
			$password = $ROW['password'];
			$role = $ROW['role'];
			$level = $ROW['level'];
			$login_status = $ROW['login_status'];
			$void_access = $ROW['void_access'];
			$assign_cluster = $ROW['assign_cluster'];
			$department = $ROW['department'];
			$branch = $ROW['branch'];
			$cluster = $ROW['cluster'];

			$update = "
				idcode='$idcode',firstname='$firstname',lastname='$lastname',username='$username',password='$password',role='$role',level='$level',
				login_status='$login_status',void_access='$void_access',assign_cluster='$assign_cluster',department='$department',branch='$branch',cluster='$cluster'
			";
			$insert = "'$idcode','$firstname','$lastname','$username','$password','$role','$level','$login_status','$void_access','$assign_cluster','$department','$branch','$cluster'";
			updateUsers($update,$insert,$idcode,$db,$conn);			

			if($x == $y)
			{
				updatingPrivilege($branch,$db,$conn);				
			}
		}
	} else {
		$cmd ='';
		$cmd .= '
			<script>
				swal("Notice","No Employess found","warning");
			</script>
		';
		print_r($cmd);
	}	
}
function updatingPrivilege($branch,$db,$conn)
{
	echo importTable('tbl_system_privilege',$db,$conn);
}
function importTable($table_name,$db,$conn)
{
	$table_name = $table_name;
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
	saveTableImport($table_name,$conn,$db);
}
function saveTableImport($table_name,$conn,$db)
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
	
	echo importBranchTable('tbl_branch',$db,$conn);
}
function importBranchTable($table_name,$db,$conn)
{
	$table_name = $table_name;
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
	saveBranchTableImport($table_name,$conn,$db);
}
function saveBranchTableImport($table_name,$conn,$db)
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
	$cmd ='';
	$cmd .= '
		<script>
			swal("Success","User`s Lists And Branches List successfuly updated","success");
		</script>
	';
	print_r($cmd);
}

/* ####################################################################################### */
function updateUsers($update,$insert,$idcode,$db,$conn)
{
	$queryCheck ="SELECT * FROM tbl_system_user WHERE idcode='$idcode'";
	$result = mysqli_query($db, $queryCheck);  
	if($result->num_rows > 0)
	{
		$queryDataUpdate = "UPDATE tbl_system_user SET $update WHERE idcode='$idcode'";
		if ($db->query($queryDataUpdate) === TRUE) {  } else { echo "ERROR UPDATE::: ".$db->error; }
	} else {
		$queryInsert = "INSERT INTO tbl_system_user (`idcode`,`firstname`,`lastname`,`username`,`password`,`role`,`level`,`login_status`,`void_access`,`assign_cluster`,`department`,`branch`,`cluster`)";
		$queryInsert .= "VALUES($insert)";
		if ($db->query($queryInsert) === TRUE) { } else { echo "ERROR INSERT::: ".$db->error; }
	}
}
