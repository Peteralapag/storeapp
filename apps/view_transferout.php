<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$function = new TheFunctions;

$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');


$itemname = $_POST['itemname'];
$itemid = $_POST['itemid'];
$params = $_POST['params'];


?>
<table class="table">
	<tr>
		<th>#</th>
		<th>ITEM NAME</th>
		<th>BRANCH OUT</th>
		<th>QTY</th>
	</tr>

	<?php
		
			$QUERY = "SELECT * FROM store_transfer_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_id='$itemid'";
			$RESULTS = mysqli_query($db, $QUERY);

			if ( $RESULTS->num_rows > 0 ) 
			{
				$i=$total=0;
				while($ROW = mysqli_fetch_array($RESULTS))  
				{
					$i++;
					$branch = $ROW['branch'];
					$transferto = $ROW['transfer_to'];
					$quantity = $ROW['quantity'];
					$total += $quantity;
					?>
					
						<tr>
							<td style="text-align:center"><?php echo $i?></td>
							<td style="text-align:center"><?php echo $itemname?></td>
							<td style="text-align:center"><?php echo $transferto?></td>
							<td style="text-align:center"><?php echo $quantity?></td>
						</tr>
					
					<?php
				}
				
				?>
				
					<tr>
						<td colspan="3">TOTAL:</td>
						<td style="text-align:center"><?php echo number_format($total,2)?></td>
					</tr>
				
				<?php
				
			} else {

			}
		?>

	
	
	
	
</table>
