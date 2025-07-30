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
	$tablename = 'store_brrr_'.$tablename.'_data';
}
else{
	$tablename = '';
}
?>

<table id="upper" style="width: 100%" class="table table-hover table-striped table-bordered">
	<thead>
		<tr>
			<th style="width:50px">#</th>

			<?php if($tablename=='store_brrr_overhead_data'){ ?>
					<th>BRANCH</th>
					<th>REPORT DATE</th>
					<th>EMPLOYEES</th>
					<th>POSITION</th>
					<th>ENCODED BY</th>
			<?php }
			else if($tablename=='store_brrr_expense_data'){ ?>
					<th>CATEGORY</th>
					<th>ACTUAL AMOUNT</th>
					<th>REMARKS</th>
			<?php }
			else if($tablename=='store_brrr_summary_data'){ ?>
					<th>CATEGORY</th>
					<th>ACTUAL AMOUNT</th>
					<th>TOTAL</th>
			<?php }

			
			
			
			
			
			else{ ?>
					<th>ITEM NAME</th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		
		<?php
			$sql = "SELECT * FROM $tablename WHERE branch='$store_branch' AND report_date='$trans_date'";
			$result = $conn->query($sql);
			$i=0;
			if ($result->num_rows > 0){
				while($row = $result->fetch_assoc()) {
					$i++;
					if($tablename=='store_brrr_overhead_data')
					{
						echo '<tr><td>'.$i.'</td>';
						echo '<td>'.$row["branch"].'</td>';
						echo '<td>'.$row["report_date"].'</td>';
						echo '<td>'.$row["acctname"].'</td>';
						echo '<td>'.$row["position"].'</td>';
						echo '<td>'.$row["created_by"].'</td></tr>';
					}
					else if($tablename=='store_brrr_expense_data'){
						echo '<tr><td>'.$i.'</td>';
						echo '<td>'.$row["category"].'</td>';
						echo '<td>'.$row["actual_amount"].'</td>';
						echo '<td>'.$row["remarks"].'</td></tr>';
					}
					else if($tablename=='store_brrr_summary_data'){
						echo '<tr><td>'.$i.'</td>';
						echo '<td>'.$row["category_json"].'</td>';
						echo '<td>'.$row["amount_json"].'</td>';
						echo '<td>'.$row["actual_amount_total"].'</td></tr>';
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