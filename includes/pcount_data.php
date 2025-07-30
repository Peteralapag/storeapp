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
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    The PCount module has been updated such that if you post it directly, there will already be a pre-existing data in the summary for the next shift.
</div>

<div class="tableFixHead">
<table style="width: 3000px" class="table table-hover table-striped table-bordered">
	<thead>
		<tr>
			<th style="text-align:center;width:50px">#</th>
			<th style="width:250px">CASHIER</th>
			<th style="width:250px">ITEM NAME</th>
			<th style="width:150px;">ACTUAL COUNT</th>
			<!--th style="width:30%"></th>
			<th style="width:100px;">FROM SHIFT DATE</th>
			<th style="width:100px;">TO SHIFT</th>
			<th style="width:100px;">TO SHIFT DATE</th-->
			<th style="width:100px">STATUS</th>
			<th style="width:100px">ACTIONS</th>
		</tr>
	</thead>
<?php
$query ="SELECT * FROM store_pcount_data $q ORDER BY status,id DESC";  
$result = mysqli_query($db, $query);  
if($result->num_rows > 0)
{
	$x=0;
	$total_amount=0;
	while($ROWS = mysqli_fetch_array($result))  
	{
		$x++;
		$rowid = $ROWS['id'];
		if($ROWS['posted'] == 'Posted')
		{
			$status = '<strong>Posted <i class="fa-solid fa-check text-success"></i></strong>';
			$tr_command = '';
		} else {
			$status = 'Open';
			$tr_command = 'ondblclick=editItem("edit","'.$file_name.'","'.$title.'","'.$rowid.'")';
		}
		
		if($ROWS['item_id'] == '' || $ROWS['item_id'] == NULL || $ROWS['item_id'] == Null || $ROWS['item_id'] == 0)
		{
			$no_id = 'style="background:#fad8d7"';
			$no_id_message = "<i class='fa-solid fa-circle-exclamation' style='color:orange'></i> <span style='color:red'><strong>This item has no ID or not Existing Please Update Item List.</strong></span>";
		} else {
			$no_id = "AAA";
			$no_id_message = "";
		}
		
?>	
	<tbody>	
		<tr <?php echo $tr_command; ?> <?php echo $no_id; ?>>
			<td style="text-align:center"><?php echo $x; ?></td>
			<td style="width:250px"><?php echo $ROWS['employee_name']; ?></td> <!-- ACTUAL YIELD -->
			<td style="min-width:250px;"><?php echo $ROWS['item_name']; ?></td> <!-- ITEM NAME -->
			<td style="text-align:right; padding-right:30px !important; width:150px"><?php echo $ROWS['actual_count']; ?></td> <!-- ITEM NAME -->			
			<!--td style="text-align:center;width:30%"><?php echo $no_id_message; ?></td>
			<td style="text-align:center;width:100px"><?php echo $ROWS['from_shift']; ?></td>
			<td style="text-align:center;width:100px"><?php echo $ROWS['to_shift']; ?></td>
			<td style="text-align:center;width:100px"><?php echo $ROWS['to_shift_date']; ?></td-->
			<td style="text-align:center;width:100px"><?php echo $status; ?></td>
			<td style="text-align:center; padding:1px !important;width:100px" class="actions">
				<?php if($status == 'Open') { ?>
				<div>
					<button class="btn btn-success btn-sm" style="width:49%;font-size:11px" onclick="EditUserChecking('edit','<?php echo $file_name; ?>','<?php echo $title; ?>','<?php echo $rowid; ?>')"><i class="fa-duotone fa-pencil"></i></button>
					<button class="btn btn-danger btn-sm" style="width:49%;font-size:11px" onclick="DeleteUserChecking('<?php echo $rowid; ?>','<?php echo $file_name; ?>','This Amount')"><i class="fa-solid fa-trash"></i></button>
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
			<td colspan="10" style="text-align:center;font-size:16px;"><i class="fa fa-bell"></i>&nbsp;&nbsp;&nbsp;Records</td>
		</tr>
	</tbody>
<?php } ?>

</table>
<div class="results"></div>
</div>
<script>
function deleteItem(rowid,filename,itemname)
{
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
