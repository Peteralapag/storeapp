<?PHP
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
include '../db_config_main.php';
$mainconn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);
$storebranch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
if(isset($_POST['modulename']))
{
	$mode = $_POST['modulename'];
} else {
	print_r('
		<script>
			app_alert("Warning"," The Mode you are trying to pass does not exist","warning","Ok","","no");
		</script>
	');
	exit();
}

$table = "store_".$mode."_data";
$module_name = strtoupper($mode);

/* #################################################################################### */
if($table=='store_production_data'){
	$sql ="DELETE FROM $table WHERE branch='$storebranch' AND month=MONTH('$transdate')";
}
else
{
	$sql ="DELETE FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
}
if($mainconn->query($sql) === TRUE){
	$cmd = '';
	$cmd .= '
		<script>
//			set_function("Submit To Server","submitserver");		
		</script>
	';
	print_r($cmd);
} 
else{
	$cmd = '';
	$cmd .= '
		<script>
			var aydi = "'.$mode.'";
			$("#" + aydi).html("'.$module_name.' is not deleted or no Data &times");
			$("#" + aydi).addClass("icon-color-red");
		</script>
	';
	print_r($cmd);
}

if($mode=='transfer'){

	$sql_trans ="DELETE FROM $table WHERE report_date='$transdate' AND shift='$shift' AND transfer_to='$storebranch'";
	if($mainconn->query($sql_trans) === TRUE){} else{}
}

?>
