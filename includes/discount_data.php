<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$trans_date = $functions->GetSession('branchdate');
$store_branch = $functions->GetSession('branch');
$file_name = $_POST['pagename'];
$title = strtoupper($file_name);
?>
<div class="tableFixHead">
<table style="width: 3000px" class="table table-hover table-striped table-bordered">
	<thead>
		<tr>
			<th style="text-align:center;width:60px;">#</th>
			<th style="width:150px">DISCOUNT DATE</th>
			<th style="width:150px">SHIFT</th>
			<th style="width:150px">DISCOUNT TYPE</th>
			<th>CATEGORY</th>
			<th style="width:150px">ITEM</th>
			<th style="width:150px">TOTAL DISCOUNT</th>
			<th style="width:50%"></th>
			<th style="width:80px">STATUS</th>
			<th style="width:60px;">ACTIONS</th>
		</tr>
	</thead>
<?php
$query ="SELECT * FROM store_discount_data WHERE branch='$store_branch' ORDER BY id DESC LIMIT 31";  
$result = mysqli_query($db, $query);  
if($result->num_rows > 0)
{
	$x=0;
	while($ROWS = mysqli_fetch_array($result))  
	{
		$x++;
		$rowid = $ROWS['id'];
		$item_name = $ROWS['report_date'];
		if($ROWS['posted'] == 'Posted')
		{
			$status = '<strong>Posted <i class="fa-solid fa-check text-success"></i></strong>';
			$tr_command = '';
		} else {
			$status = 'Open';
			$tr_command = 'ondblclick=editItem("edit","'.$file_name.'","'.$title.'","'.$rowid.'")';
		}
?>	
	<tbody>	
		<tr <?php echo $tr_command; ?>>
			<td style="text-align:center"><?php echo $x; ?></td>
			<td style="text-align:center"><?php echo date("F d Y", strtotime($ROWS['report_date'])); ?></td> <!-- ITEM NAME -->
			<td style="text-align:center"><?php echo $ROWS['shift']; ?></td>
			<td style="text-align:center"><?php echo $ROWS['discount_type']?></td> <!-- DISCOUNT TYPE -->
			<td><?php echo $ROWS['category']?></td>

			<td><?php echo $ROWS['item_name']?></td>
			
			<td style="text-align:right; padding-right:30px !important"><?php echo $ROWS['discount']; ?></td> <!-- ITEM NAME -->
			<td></td>
			<td style="text-align:center"><?php echo $status; ?></td>
			<td style="text-align:center; padding:1px !important" class="actions">
				<?php if($status == 'Open') { ?>
				<div>
					<button class="btn btn-success btn-sm" style="width:49%;font-size:11px" onclick="EditUserChecking('edit','<?php echo $file_name; ?>','<?php echo $title; ?>','<?php echo $rowid; ?>')"><i class="fa-duotone fa-pencil"></i></button>
					<button class="btn btn-danger btn-sm" style="width:49%;font-size:11px" onclick="DeleteUserChecking('<?php echo $rowid; ?>','<?php echo $file_name; ?>','<?php echo $item_name; ?>')"><i class="fa-solid fa-trash"></i></button>
				</div>
				<?php }  else {?>
				<div>
					<button class="btn btn-warning btn-sm btn-block" style="font-size:11px;"><i class="fa-solid fa-lock-keyhole pull-left"></i> Locked</button>
				</div>
				<?php } ?>
			</td>
		</tr>
	</tbody>
<?php } } else {?>
	<tbody>
		<tr>
			<td colspan="8" style="text-align:center;font-size:16px;"><i class="fa fa-bell"></i>&nbsp;&nbsp;&nbsp;Records</td>
		</tr>
	</tbody>
<?php } ?>
</table>
<div class="results"></div>
</div>
<script>
function deleteItem(rowid,filename,itemname)
{
	app_confirm("Delete Discount","Are you sure to delete discount for " + itemname + "?","warning",filename,rowid);
	return false;
}
function deleteItemYes(rowid,filename)
{
	rms_reloaderOn('Deleting Data....');
	var mode = 'deleteitem';
	$.post("./actions/actions.php", { mode: mode, rowid: rowid, filename },
	function(data) {
		$('.results').html(data);
		$('#' + sessionStorage.navcount).trigger('click');
		rms_reloaderOff();
	});
}
</script>
