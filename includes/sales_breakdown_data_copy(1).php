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
	if(isset($_POST['search'])){
		$search = $_POST['search'];
		$q .= " AND item_name LIKE '%$search%'";
	}
}
else
{
	$dateselectfrom = $defaultDate;
	$dateselectto = $defaultDate;
	$q = "WHERE branch='$selectedbranch' AND report_date='$defaultDate'";
}


$breadsTotal=0; 
$specialsTotal=0;
$cakesTotal=0;

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

.table-container {
  display: flex;
}

.table-wrapper {
  margin-right: 20px; /* Add some space between tables, adjust as needed */
}

.inline-table {
  border-collapse: collapse;
  width: 100%; /* Table 1 takes 100% width of its container */
}

/* Style the table, rows, and cells as desired */
.inline-table, .inline-table th, .inline-table td {
  border: 1px solid black;
  padding: 8px;
  text-align: center;
}
</style>
<?php 
?>
<div id="totalresult"></div>
<div id="upper">
	<div class="table-container">
		<div class="table-wrapper">
		
			<table id="table1" style="width: 100%" class="table table-hover table-striped table-bordered inline-table">
				<tr>
					<th colspan="3" style="width:50px;text-align:center">BRADS</th>
				</tr>
				<tr>
					<th style="width:50px;text-align:center">#</th>
					<th>ITEMS</th>
					<th style="width:150px">AMOUNT</th>
					
				</tr>
			<?php
				
				$query ="SELECT * FROM store_summary_data $q AND category='BREADS' GROUP BY item_id"; 	
				$result = mysqli_query($db, $query);  
				if($result->num_rows > 0)
				{
					$breadsAmount =0;
					$i=0;
					while($ROW = mysqli_fetch_array($result))  
					{
						$i++;
						$item_id = $ROW['item_id'];	
						$item_name = $ROW['item_name'];	
						$category = $ROW['category'];
						$breadsAmount =  $TheFunctions->getSalesBreakdown('BREADS',$item_id,$selectedbranch,$dateselectfrom,$dateselectto,$db);
						$breadsTotal += $breadsAmount;
						?>
						<tr>
							<td><?php echo $i?></td>
							<td style="text-align:left"><?php echo $item_name?></td>
							<td style="text-align:right"><?php echo number_format($breadsAmount,2)?></td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td colspan="2">TOTAL:</td>
						<td style="text-align:right; font-weight:bold"><?php echo number_format($breadsTotal,2)?></td>
					</tr>
					<?php
				}
			?>	
			</table>
		</div>
	
		<div class="table-wrapper">
			<table id="table2" style="width: 100%" class="table table-hover table-striped table-bordered inline-table">
				<tr>
					<th colspan="3" style="width:50px;text-align:center">SPECIALS</th>
				</tr>
				<tr>
					<th style="width:50px;text-align:center">#</th>
					<th>ITEMS</th>
					<th style="width:150px">AMOUNT</th>
					
				</tr>
			<?php
				$query ="SELECT * FROM store_summary_data $q AND category='SPECIALS' GROUP BY item_id"; 	
				$result = mysqli_query($db, $query);  
				if($result->num_rows > 0)
				{
					$breadsAmount =0;
					$i=0;
					while($ROW = mysqli_fetch_array($result))  
					{
						$i++;
						$item_id = $ROW['item_id'];	
						$item_name = $ROW['item_name'];	
						$category = $ROW['category'];
						$specialsAmount =  $TheFunctions->getSalesBreakdown('SPECIALS',$item_id,$selectedbranch,$dateselectfrom,$dateselectto,$db);
						$specialsTotal += $specialsAmount;
						?>
						<tr>
							<td><?php echo $i?></td>
							<td style="text-align:left"><?php echo $item_name?></td>
							<td style="text-align:right"><?php echo number_format($specialsAmount,2)?></td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td colspan="2">TOTAL:</td>
						<td style="text-align:right; font-weight:bold"><?php echo number_format($specialsTotal,2) ?></td>
					</tr>
					<?php
				}
			?>
			</table>
		</div>
		
		<div class="table-wrapper">
			<table id="table3" style="width: 100%" class="table table-hover table-striped table-bordered inline-table">
				<tr>
					<th colspan="3" style="width:50px;text-align:center">CAKES</th>
				</tr>
				<tr>
					<th style="width:50px;text-align:center">#</th>
					<th>ITEMS</th>
					<th style="width:150px">AMOUNT</th>
					
				</tr>
			<?php
				$query ="SELECT * FROM store_summary_data $q AND category='CAKES' GROUP BY item_id"; 	
				$result = mysqli_query($db, $query);  
				if($result->num_rows > 0)
				{
					$cakesAmount =0;
					$i=0;
					while($ROW = mysqli_fetch_array($result))  
					{
						$i++;
						$item_id = $ROW['item_id'];	
						$item_name = $ROW['item_name'];	
						$category = $ROW['category'];
						$cakesAmount =  $TheFunctions->getSalesBreakdown('CAKES',$item_id,$selectedbranch,$dateselectfrom,$dateselectto,$db);
						$cakesTotal += $specialsAmount;
						?>
						<tr>
							<td><?php echo $i?></td>
							<td style="text-align:left"><?php echo $item_name?></td>
							<td style="text-align:right"><?php echo number_format($cakesAmount,2)?></td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td colspan="2">TOTAL:</td>
						<td style="text-align:right; font-weight:bold"><?php echo number_format($cakesTotal,2) ?></td>
					</tr>
					<?php
				}
			?>
		
			</table>
		</div>
		
	</div>

</div>
<script>
$(document).ready(function(){
	var breads = <?php echo (float)$breadsTotal?>;
	var specials = <?php echo (float)$specialsTotal?>;
	var cakes = <?php echo (float)$cakesTotal?>;
	var result = parseInt(breads + specials + cakes);
	$('#totalresult').html('<b style="color:red">TOTAL:&nbsp;'+result.toLocaleString()+'</b>');	
});
</script>
