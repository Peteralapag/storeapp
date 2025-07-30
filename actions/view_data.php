<?php
require '../init.php';
$functions = new TheFunctions;
include '../db_config_main.php';
$conn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);

$trans_date = $functions->GetSession('branchdate');
$store_branch = $functions->AppBranch();
$branch_shift = $functions->GetSession('shift');



if(isset($_POST['tablename'])){
	$tablename = $_POST['tablename'];
	$tablename = 'store_'.$tablename.'_data';
}
else{
	$tablename = '';
}
?>
<table id="upper" style="width: 100%" class="table table-hover table-striped table-bordered">
	<thead>
		<tr>
			<th style="width:50px">#</th>
			<?php if($tablename=='store_cashcount_data'){ ?>
					<th>DENOMINATION</th>
					<th>QUANTITY</th>
					<th>TOTAL</th>
			<?php }
			else if($tablename=='store_discount_data'){ ?>
					<th>DISCOUNT TYPE</th>
					<th>ITEM NAME</th>
			<?php }
			else{ ?>
					<th>ITEM NAME</th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		
		<?php
			if($tablename=='store_transfer_data' || $tablename=='store_rm_transfer_data' || $tablename=='store_supplies_transfer_data' || $tablename=='store_scrapinventory_transfer_data' || $tablename=='store_boinventory_transfer_data')
			{
				$sql = "SELECT item_name FROM $tablename WHERE report_date='$trans_date' AND shift='$branch_shift' AND (branch='$store_branch' OR transfer_to='$store_branch')";
			}
			elseif($tablename=='store_cashcount_data')
			{
				$sql = "SELECT denomination, quantity, total_amount FROM $tablename WHERE branch='$store_branch' AND report_date='$trans_date' AND shift='$branch_shift'";
			}
			elseif($tablename=='store_production_data')
			{
				$sql = "SELECT item_name FROM $tablename WHERE branch='$store_branch' AND `month`= MONTH('$trans_date') AND `year` = YEAR('$trans_date')";
			}
			elseif($tablename=='store_discount_data')
			{
				$sql = "SELECT item_name, discount_type FROM $tablename WHERE branch='$store_branch' AND report_date='$trans_date' AND shift='$branch_shift'";
			}
			else
			{
				$sql = "SELECT item_name FROM $tablename WHERE branch='$store_branch' AND report_date='$trans_date' AND shift='$branch_shift'";
			}
			$result = $conn->query($sql);
			$i=0;
			if ($result->num_rows > 0){
				while($row = $result->fetch_assoc()) {
					$i++;
					if($tablename=='store_cashcount_data')
					{
						echo '<tr><td>'.$i.'</td>';
						echo '<td>'.$row["denomination"].'</td>';
						echo '<td>'.$row["quantity"].'</td>';
						echo '<td>'.$row["total_amount"].'</td></tr>';
					}
					else if($tablename=='store_discount_data'){
						echo '<tr><td>'.$i.'</td>';
						echo '<td>'.$row["discount_type"].'</td>';
						echo '<td>'.$row["item_name"].'</td></tr>';
					}
					else
					{
						echo '<tr><td>'.$i.'</td>';
						echo '<td>'.$row["item_name"].'</td></tr>';
					}
				}
			}
			else{
				echo "<tr><td></td><td>No results</td></tr>";
			}
			
		?>
		
		
	</tbody>
</table>