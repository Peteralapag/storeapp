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
if($mode == 'calculateboinventorysummary')
{
	$query ="SELECT *  FROM store_boinventory_summary_data WHERE branch='$branch_name' AND shift='$branch_shift' AND report_date='$branch_date'"; 	
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
			$difference = ($grand_total - $actual_count);
			$total_amount = ($difference * $price_kg);			
			echo $functions->CalculateSuppliesSummary($rowid,$sub_total,$grand_total,$difference,$total_amount,$branch_name,$branch_date,$branch_shift,$db);
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
if($mode == 'calculatescrapinventorysummary')
{
	$query ="SELECT *  FROM store_scrapinventory_summary_data WHERE branch='$branch_name' AND shift='$branch_shift' AND report_date='$branch_date'"; 	
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
			$difference = ($grand_total - $actual_count);
			$total_amount = ($difference * $price_kg);			
			echo $functions->CalculateScrapInventorySummary($rowid,$sub_total,$grand_total,$difference,$total_amount,$branch_name,$branch_date,$branch_shift,$db);
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

if($mode == 'calculatesuppliessummary')
{
	$query ="SELECT *  FROM store_supplies_summary_data WHERE branch='$branch_name' AND shift='$branch_shift' AND report_date='$branch_date'"; 	
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
			$difference = ($grand_total - $actual_count);
			$total_amount = ($difference * $price_kg);			
			echo $functions->CalculateSuppliesSummary($rowid,$sub_total,$grand_total,$difference,$total_amount,$branch_name,$branch_date,$branch_shift,$db);
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

if($mode == 'calculateSummary')
{
	$query ="SELECT *  FROM store_summary_data WHERE branch='$branch_name' AND shift='$branch_shift' AND report_date='$branch_date'"; 	
	$result = mysqli_query($db, $query);  
	if($result->num_rows > 0)
	{
		$amounttotal=0;			
		while($ROW = mysqli_fetch_array($result))
		{
			$total=0;
			$breadsAmount =0;
			$total=0;$transfer_out=0;$should_be=0;$sold;
			$rowid = $ROW['id'];
			$shift = $ROW['shift'];
			$store_branch = $ROW['branch'];
			$summary_date = $ROW['report_date'];
			$time_covered =  $ROW['time_covered'];			
			$beginning = $ROW['beginning'];
			$category = $ROW['category'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			$stock = $ROW['stock_in'];
			$transfer_in = $ROW['t_in'];
			$transfer_out = $ROW['t_out'];
			$charges = $ROW['charges'];
			$snacks = $ROW['snacks'];
			$bad_order = $ROW['bo'];
			$damaged = $ROW['damaged'];
			$unit_price = $ROW['unit_price'];
			$complimentary = $ROW['complimentary'];
			$actual_count = $ROW['actual_count'];
			$frozendough = $ROW['frozendough'];	
			$posted = $ROW['posted'];
			
			$total += ($beginning + $stock + $transfer_in + $frozendough);
			$should_be = ($total - $transfer_out - $charges - $snacks - $bad_order - $damaged - $complimentary);
			$sold = ($should_be - $actual_count);
		
			$amount =  $sold * $function->getItemPrice($item_id,$db);
		//	$amount =  $sold * $unit_price;
			echo $functions->CalculateSummary($rowid,$total,$should_be,$sold,$amount,$branch_name,$branch_date,$branch_shift,$db);
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
			$difference = ($grand_total - $actual_count);
			$total_amount = ($difference * $price_kg);			
			echo $functions->CalculateRMSummary($rowid,$sub_total,$grand_total,$difference,$total_amount,$branch_name,$branch_date,$branch_shift,$db);
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