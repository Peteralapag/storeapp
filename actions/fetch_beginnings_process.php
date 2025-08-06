<?php
require '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$function = new TheFunctions;
$branch_name = $functions->AppBranch();
$branch_date = $functions->GetSession('branchdate');
$branch_shift = $functions->GetSession('shift');
$shifting = $functions->GetSession('shifting');
$shift = $functions->GetSession('shift'); 
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
$table = $_POST['table'];
if($table == 'store_rm_pcount_data')
{
	$tbl = 'store_rm_summary_data';
} else {
	$tbl = 'store_summary_data';
}

if($mode == 'fetchbeginnings')
{
	if($shifting == 2)
	{
		if($shift == 'FIRST SHIFT')
		{
			$tdate = date('Y-m-d', strtotime($branch_date. '-1 day'));
			$q = "AND shift='SECOND SHIFT' AND to_shift='FIRST SHIFT' AND to_shift_date='$branch_date' AND from_shift='$tdate'";	
		}
		else if($shift == 'SECOND SHIFT')
		{
			$q = "AND shift='FIRST SHIFT' AND to_shift='SECOND SHIFT' AND to_shift_date='$branch_date' AND from_shift='$branch_date'";	
		} 
		else {
			$q='0';	
		}
	}
	if($shifting == 3)
	{		
		if($shift == 'FIRST SHIFT')
		{
			$tdate = date('Y-m-d', strtotime($branch_date. '-1 day'));
			$q = "AND shift='THIRD SHIFT' AND to_shift='FIRST SHIFT' AND to_shift_date='$branch_date' AND from_shift='$tdate'";	
		}
		else if($shift == 'SECOND SHIFT')
		{
			$q = "AND shift='FIRST SHIFT' AND to_shift='SECOND SHIFT' AND to_shift_date='$branch_date' AND from_shift='$branch_date'";	
		} 
		else if($shift == 'THIRD SHIFT')
		{
			$q = "AND shift='SECOND SHIFT' AND to_shift='THIRD SHIFT' AND to_shift_date='$branch_date'";	
		}
		else {
			$q='0';	
		}
	}
	$query ="SELECT *  FROM $table WHERE branch='$branch_name' $q AND posted='Posted'"; 	
	$result = mysqli_query($db, $query);  
	if($result->num_rows > 0)
	{			
		while($ROW = mysqli_fetch_array($result))
		{
			$item_id = $ROW['item_id'];
			$actual_count = $ROW['actual_count'];
			echo $functions->BeginningToSummary($tbl,$actual_count,$item_id,$branch_name,$branch_date,$shift,$db);
		}
	} else {
		print_r('
			<script>
				app_alert("System Message","You did not create any Physical Count or it is not Posted","success","Ok","","");
				$("#" + sessionStorage.navcount).click();
			</script>
		');
	} 
}
?>
