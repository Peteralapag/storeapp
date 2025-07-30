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
if($mode == 'calculatermsummary')
{
	$query ="SELECT *  FROM store_rm_summary_data WHERE branch='$branch_name' AND shift='$branch_shift' AND report_date='$branch_date'"; 	
	$result = mysqli_query($db, $query);  
	if($result->num_rows > 0)
	{			
		while($ROW = mysqli_fetch_array($result))
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$beginning = $ROW['beginning'];
			$stock_in = $ROW['stock_in'];
			$transfer_in = $ROW['transfer_in'];
			$transfer_out = $ROW['transfer_out'];
			$bad_order = $ROW['bo'];
			$actual_count = $ROW['actual_count'];
			$price_kg = $ROW['price_kg'];
			
			$sub_total = ($beginning + $stock_in + $transfer_in);
			$grand_total = ($sub_total - $transfer_out - $bad_order);
//			$difference = ($actual_count - $grand_total); 
			$difference = ($grand_total - $actual_count);
			$total_amount = ($difference * $price_kg);			
			echo $functions->CalculateSummary($rowid,$sub_total,$grand_total,$difference,$total_amount,$branch_name,$branch_date,$branch_shift,$db);
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