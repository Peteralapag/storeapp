<?PHP
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
$tables = $_POST['tables'];

echo '<div style="text-align:center;font-weight:bold;font-size:16px;border-bottom:5px solid orange">'.strtoupper($tables).'</div><br>';

if($tables == 'othertools'){
	othertools($db);
}
else{
	echo 'NULL';
}

function othertools($db){
////////////// PCOUNT MODIFY
	$sql = "ALTER TABLE store_pcount_data MODIFY COLUMN to_shift_date DATE NULL";
	$result = $db->query($sql);
	if($result === TRUE) { }
	else { }
/////////////PANABO 6 BRANCH
	if($result === TRUE) 
	{ 	
		$sql2 = "UPDATE tbl_branch SET branch='PANABO 6 STO NINO', branchcode='PANABO 6 STO NINO' WHERE id='104'";
		if ($db->query($sql2) === TRUE) {
			echo 'Other Tools Success';
			mysqli_set_charset($db,"utf8");
		} 
		else { }
	}
	else { }	
}
