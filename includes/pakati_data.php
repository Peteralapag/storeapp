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
			<th style="text-align:center;width:50px">#</th>
			<th>DINOMINATION</th>
			<th>QUANTITY</th>
			<th>TOTAL AMOUNT</th>
			<th></th>
			<th>STATUS</th>
			<th>ACTIONS</th>
		</tr>
	</thead>
<?php
$query ="SELECT * FROM store_pakati_data $q ORDER BY status,id DESC";  
$result = mysqli_query($db, $query);  
$total_amount=0;
if($result->num_rows > 0)
{
	$x=0;
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
		$total_amount += $ROWS['total_amount'];
	
?>	
	<tbody>	
		<tr <?php echo $tr_command; ?>>
			<td style="text-align:center"><?php echo $x; ?></td>
			<td style="text-align:right; padding-right:30px !important; width:150px"><?php echo $ROWS['denomination']; ?></td> <!-- ITEM NAME -->
			<td style="text-align:right; padding-right:30px !important; width:150px"><?php echo $ROWS['quantity']; ?></td> <!-- ITEM NAME -->
			<td style="text-align:right; padding-right:30px !important; width:150px"><?php echo number_format($ROWS['total_amount'],2); ?></td> <!-- ACTUAL YIELD -->
			<td style="text-align:center"></td>
			<td style="text-align:center;width:100px"><?php echo $status; ?></td>
			<td style="text-align:center; padding:1px !important" class="actions">
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
<?php } ?>
	<tbody>
		<tr>
			<td colspan="3" style="text-align:left;font-weight:bold">TOTAL</td> 
			<td style="padding-right:30px !important; width:150px; text-align:right; border-top:3px solid #232323;font-weight:bold"><?php echo number_format($total_amount,2)?></td> 
			<td colspan="4" style="text-align:center">&nbsp;</td>
		</tr>
	</tbody>
<?php } else { ?>
	<tbody>
		<tr>
			<td colspan="7" style="text-align:center;font-size:16px;"><i class="fa fa-bell"></i>&nbsp;&nbsp;&nbsp;Records</td>
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
