<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	

$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');

$query ="SELECT * FROM store_dum_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
$result = mysqli_query($db, $query);  
$rowcount=mysqli_num_rows($result);
if($result->num_rows > 0)
{
	$x=0;$v_amount=0;
	while($ROW = mysqli_fetch_array($result))  
	{
		$x++;
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
		$price_kg = $ROW['price_kg'];
		$variance_amount = $ROW['variance_amount'];
		
		$v_amount = ($price_kg * $variance);		
		$subtotal = ($beginning + $delivery + $transfer_in - $transfer_out - $counter_out);
		$nettotal = ($subtotal - $actual_usage);			
		$variance = ($physical_count - $net_total);
		
		$queryDataUpdate = "UPDATE store_dum_data SET sub_total='$subtotal',net_total='$nettotal',variance='$variance',variance_amount='$v_amount' WHERE id='$rowid'";
		if ($db->query($queryDataUpdate) === TRUE)
		{
		} else {
			echo "ERROR::: ".$db->error;
		}
		if($rowcount == $x)
		{
			print_r('
				<script>
					$("#" + sessionStorage.btncount).trigger("click");
				</script>
			');
		}
	}
}
