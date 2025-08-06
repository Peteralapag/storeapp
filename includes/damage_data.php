<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$trans_date = $functions->GetSession('branchdate');
$store_branch = $functions->AppBranch();
$file_name = $_POST['pagename'];
$title = strtoupper($file_name);

if(isset($_POST['search']))
{
	$item_name = $_POST['search'];
	$shift = $_SESSION['session_shift'];
	$q = "WHERE report_date='$trans_date' AND shift='$shift' AND branch='$store_branch' AND item_name LIKE '%$item_name%'";
} 
else
{
	if(isset($_SESSION['session_shift'])) 
	{
		$shift = $_SESSION['session_shift'];
		$q = "WHERE report_date='$trans_date' AND shift='$shift' AND branch='$store_branch'";
	} else {
		$shift = '';
		$q = "WHERE report_date='$trans_date' AND branch='$store_branch'";
	}
}
?>
<div class="tableFixHead">
<table style="width: 3000px" class="table table-hover table-striped table-bordered">
	<thead>
		<tr>
			<th style="text-align:center">#</th>
			<th>ITEM NAME</th>
			<th>QUANTITY</th>
			<th>UNIT PRICE</th>
			<th>AMOUNT</th>
			<th>STATUS</th>
			<th>ACTIONS</th>
		</tr>
	</thead>
<?php
$query ="SELECT * FROM store_damage_data $q ORDER BY status,id DESC";  
$result = mysqli_query($db, $query);  
if($result->num_rows > 0)
{
	$x=0;
	while($ROWS = mysqli_fetch_array($result))  
	{
		$x++;
		$rowid = $ROWS['id'];
		$item_name = $ROWS['item_name'];
		if($ROWS['item_id'] == '' || $ROWS['item_id'] == NULL || $ROWS['item_id'] == Null || $ROWS['item_id'] == 0)
		{
			$noid_class = 'class="noid"';
			$noid_text = '<i class="fa-solid fa-triangle-exclamation icon-color-orange pull-right" onclick="showError()"></i>';
		} else {
			$noid_class = '';
			$noid_text = '';
		}
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
		<tr <?php echo $tr_command; ?> <?php echo $noid_class; ?>>
			<td style="text-align:center"><?php echo $x; ?></td>
			<td><?php echo $ROWS['item_name']." ".$noid_text; ?></td> <!-- ITEM NAME -->
			<td style="text-align:right; padding-right:30px !important"><?php echo $ROWS['quantity']; ?></td> <!-- ITEM NAME -->
			<td style="text-align:right; padding-right:30px !important"><?php echo $ROWS['unit_price']; ?></td> <!-- ACTUAL YIELD -->
			<td class="al-right" style=" padding-right:30px !important"><?php echo $ROWS['amount']; ?></td> <!-- SHIFT -->
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
	console.log(filename);
	app_confirm("Delete Item","Are you sure to delete " + itemname + "?","warning",filename,rowid);
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
