<?php
require '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$selectedbranch = $_SESSION['appstore_branch'];
$defaultDate = $_SESSION['session_date'];
$TheFunctions = new TheFunctions;

if(isset($_POST['dateselectfrom']) && isset($_POST['dateselectto']))
{
	$dateselectfrom = $_POST['dateselectfrom'];
	$dateselectto = $_POST['dateselectto'];
	$q = "WHERE branch='$selectedbranch' AND report_date BETWEEN '$dateselectfrom' AND '$dateselectto'";
}
else
{
	$dateselectfrom = $defaultDate;
	$dateselectto = $defaultDate;
	$q = "WHERE branch='$selectedbranch' AND report_date='$defaultDate'";
}
?>
<style>
#tgas,#beg,#fgtsin,#tin,#tout,#del { position:relative; }
.title-box1,.title-box2,.title-box3,.title-box4,.title-box5,.title-box6  {
	position:absolute;display:none;font-size:12px;padding:5px 10px 5px 10px;
	border:#aeaeae;background:#f9f5e8;color:#232323;border-radius:5px; 
	border:1px solid orange;z-index:999;
}
.dc-notifs {
	padding:2px 10px 2px 10px;
	border:1px solid orange;
	background:#f9f5e8;
	font-weight:normal;
	font-style:italic;
	font-size:12px;
	margin-left:100px;
	border-radius:5px
}
</style>
<?php 
?>
<table id="upper" style="width: 100%" class="table table-hover table-striped table-bordered">
	<tr>
		<th style="width:50px;text-align:center">#</th>
		<th style="width:400px">ITEMS</th>
		<th>IN</th>
		<th>T-IN</th>
		<th>T-OUT</th>
		<th>B.O</th>
		<th>C-OUT</th>
		<th>ACTUAL USAGE</th>
		<th>ACTUAL COUNT</th>
		<th>DIFFERENCE</th>
		<th>AMOUNT</th>
		
	</tr>
<?php

	$query ="SELECT * FROM store_rm_summary_data $q GROUP BY item_id"; 	
	$result = mysqli_query($db, $query);  
	if($result->num_rows > 0)
	{
	$i=0;
	$totalamount=0;
	while($ROW = mysqli_fetch_array($result))  
	{	
		$i++;
		$item_id = $ROW['item_id'];	
		$item_name = $ROW['item_name'];	
		$category = $ROW['category'];
		
		$stock = $TheFunctions->rmInventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'stock_in',$db);
		$transfer_in = $TheFunctions->rmInventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'transfer_in',$db);
		$transfer_out = $TheFunctions->rmInventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'transfer_out',$db);
		$bad_order = $TheFunctions->rmInventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'bo',$db);
		$actual_count = $TheFunctions->rmInventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'actual_count',$db);
		$difference = $TheFunctions->rmInventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'difference',$db);
		$amount = $TheFunctions->rmInventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'amount',$db);
		$counter_out = $TheFunctions->rmDUMValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'counter_out',$db);
		$actual_usage = $TheFunctions->rmDUMValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'actual_usage',$db);
		$totalamount += $amount;
?>
	<tr>		
		<td style="text-align:center;"><?php echo $i; ?></td>	
		<td style="text-align:left;white-space:nowrap"><?php echo $item_name?></td>	
		<td><?php echo number_format($stock,2)?></td>
		<td><?php echo number_format($transfer_in,2)?></td>
		<td><?php echo number_format($transfer_out,2)?></td>
		<td><?php echo number_format($bad_order,2)?></td>
		<td><?php echo number_format($counter_out,2)?></td>
		<td><?php echo number_format($actual_usage,2)?></td>
		<td><?php echo number_format($actual_count,2)?></td>
		<td><?php echo number_format($difference,2)?></td>
		<td style="text-align:right"><?php echo number_format($amount,2)?></td>
	</tr>
<?php
	}
?>
	<tr style="font-weight:bold">
		<td colspan="10">TOTAL AMOUNT</td>
		<td style="text-align:right"><?php echo number_format($totalamount,2)?></td>
	</tr>

</table>
<?php 
}
else{

}
?>
<div id="inventoryData"></div>
