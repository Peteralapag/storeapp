<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
require '../includes/config.php'; 
$trans_date = $functions->GetSession('branchdate');
$store_branch = $functions->AppBranch();
$file_name = $_POST['pagename'];
$title = strtoupper($file_name);

if(isset($_POST['search']))
{
	$item_name = $_POST['search'];
	$shift = $_SESSION['session_shift'];
	$q = "WHERE report_date='$trans_date' AND shift='$shift' AND branch='$store_branch' AND item_name LIKE '%$item_name%'";
	$iN = "WHERE report_date='$trans_date' AND shift='$shift' AND transfer_to='$store_branch' AND item_name LIKE '%$item_name%'";
} 
else
{
	if(isset($_SESSION['session_shift'])) 
	{
		$shift = $_SESSION['session_shift'];
		$q = "WHERE report_date='$trans_date' AND shift='$shift' AND branch='$store_branch'";
		$iN = "WHERE report_date='$trans_date' AND shift='$shift' AND transfer_to='$store_branch'";
	} else {
		$shift = '';
		$q = "WHERE report_date='$trans_date' AND branch='$store_branch'";
		$iN = "WHERE report_date='$trans_date' AND transfer_to='$store_branch'";
	}
}
?>
<style>
</style>
<div id="tcontainer" style="position:relative;overflow:hidden">
		<div class="tout" id="tout">
		<div class="tableFixHeadTF">
		<table style="width: 100%" class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<th style="text-align:center">#</th>
					<th style="background:orange">OUT</th>
					<th>ITEM NAME</th>
					<th>QUANTITY</th>
					<th>UNIT PRICE</th>
					<th>TRANSFER TO</th>
					<th>STATUS</th>
					<th>ACTIONS</th>
				</tr>
			</thead>
		<?php
		$queryOUT ="SELECT * FROM store_rm_transfer_data $q ORDER BY status,id DESC";  
		$OUTresult = mysqli_query($db, $queryOUT);  
		if($OUTresult->num_rows > 0)
		{
			$x=0;
			while($ROW = mysqli_fetch_array($OUTresult))  
			{
				$x++;
				$rowid = $ROW['id'];
				$item_name = $ROW['item_name'];
				if($ROW['item_id'] == '' || $ROW['item_id'] == NULL || $ROW['item_id'] == Null || $ROW['item_id'] == 0)
				{
					$noid_class = 'class="noid"';
					$noid_text = '<i class="fa-solid fa-triangle-exclamation icon-color-orange pull-right" onclick="showError()"></i>';
				} else {
					$noid_class = '';
					$noid_text = '';
				}
				if($ROW['posted'] == 'Posted')
				{
					$status = '<strong>Posted <i class="fa-solid fa-check text-success"></i></strong>';
					$tr_command = '';
				} else {
					$status = 'Open';
					$tr_command = 'ondblclick=editItem("edit","'.$file_name.'","'.$title.'","'.$rowid.'")';
				}
				// $remote_transfer = $functions->getRemoteTransfer()
		?>	
			<tbody>	
				<tr <?php echo $tr_command; ?> <?php echo $noid_class; ?>>
					<td style="text-align:center"><?php echo $x; ?></td>
					<td colspan="2"><?php echo $ROW['item_name']." ".$noid_text; ?></td> <!-- ITEM NAME -->
					<td class="al-right" style=" padding-right:30px !important"><?php echo $ROW['weight']; ?></td> <!-- ITEM NAME -->
					<td style="text-align:right; padding-right:30px !important"><?php echo $ROW['units']; ?></td> <!-- ACTUAL YIELD -->
					<td><?php echo $ROW['transfer_to']; ?></td> <!-- BAKE -->
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
					<td colspan="10" style="text-align:center;font-size:16px;"><i class="fa fa-bell"></i>&nbsp;&nbsp;&nbsp;No Records</td>
				</tr>
			</tbody>
		<?php } ?>
		</table>
		<div class="results"></div>
		</div>	
	</div>
	<!-- ################################################### TRANSFER IN ############################################### -->
	<div class="tin" id="tin">
		<div style="text-align:right; padding-bottom:4px">
		</div>

		<div class="tableFixHeadTF">
		<table style="width: 3000px" class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<th style="text-align:center; height: 23px;">#</th>
					<th style="background:orange; height: 23px;">IN</th>
					<th style="height: 23px">ITEM NAME</th>
					<th style="height: 23px">QUANTITY</th>
					<th style="height: 23px">UNIT PRICE</th>
					<th style="height: 23px">FROM BRANCH</th>
					<th style="height: 23px">STATUS</th>
					<th style="height: 23px">ACTIONS</th>
				</tr>
			</thead>
		<?php
		$queryIN ="SELECT * FROM store_rm_transfer_data $iN ORDER BY status,id DESC";  
		$INresult = mysqli_query($db, $queryIN);  
		if($INresult->num_rows > 0)
		{
			$x=0;
			while($ROWS = mysqli_fetch_array($INresult))  
			{
				$x++;
				$tid = $ROWS['tid'];
				$rowid = $ROWS['id'];
				$item_id = $ROWS['item_id'];
				$item_name = $ROWS['item_name'];
				if($ROWS['item_id'] == '' || $ROWS['item_id'] == Null)
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
				if($tid > 0)
				{
					$trans_type = '<strong>A <i class="fa-solid fa-arrow-right"></i></strong>';
					$btn_delete = 'disabled';
				} else {
					$trans_type = '<strong>M <i class="fa-solid fa-arrow-right"></i></strong>';
					$btn_delete = '';
				}
			?>	
			<tbody>	
				<tr <?php echo $noid_class; ?>>
					<td style="text-align:center"><?php echo $x; ?></td>
					<td colspan="2"><?php echo $trans_type." ".$ROWS['item_name']." ".$noid_text; ?></td> <!-- ITEM NAME -->
					<td class="al-right" style=" padding-right:30px !important"><?php echo $ROWS['weight']; ?></td> <!-- ITEM NAME -->
					<td style="text-align:right; padding-right:30px !important"><?php echo $ROWS['units']; ?></td> <!-- ACTUAL YIELD -->
					<td><?php echo $ROWS['branch']; ?></td> <!-- BAKE -->
					<td style="text-align:center"><?php echo $status; ?></td>
					<td style="text-align:center; padding:1px !important" class="actions">
						<?php if($status == 'Open') { ?>
						<div>
							<button id="postitembtn<?php echo $x; ?>" class="btn btn-info btn-sm" style="font-size:11px;width:49%" onclick="postItem('<?php echo $file_name; ?>','<?php echo $x; ?>','<?php echo $rowid; ?>')"><i class="fa-solid fa-bring-forward"></i></button>
							<button class="btn btn-danger btn-sm" <?php echo $btn_delete; ?> style="width:49%;font-size:11px" onclick="deleteItem('<?php echo $rowid; ?>','<?php echo $file_name; ?>','<?php echo $item_name; ?>')"><i class="fa-solid fa-trash"></i></button>							
							<!-- button class="btn btn-danger btn-sm" style="font-size:11px;width:49%;" onclick="postItems()" <?php echo $btn_delete; ?>><i class="fa-solid fa-trash"></i></button -->
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
					<td colspan="10" style="text-align:center;font-size:16px;"><i class="fa fa-bell"></i>&nbsp;&nbsp;&nbsp;No Records</td>
				</tr>
			</tbody>
		<?php } ?>
		</table>
		<div class="results"></div>
		</div>
		<div class="detection"></div>	
	</div>
</div>
<script>
function unLockIN(params)
{
	var title = '<?php echo $title; ?>';
	$('#posttosummarybtn,#unlockbutton,#additembtn').attr("disabled", true);
	$('#unlockbuttonIN').html('<i class="fa-solid fa-bring-forward"></i>&nbsp;&nbsp;Unlocking... data &nbsp;&nbsp;<i class="fa fa-spinner fa-spin"></i>');
	var mode = "unlockpost";	
	setTimeout(function()
	{
		$.post("./actions/actions.php", { mode: mode, database: params },
		function(data) {
			$(".Results").html(data);
			$('#' + sessionStorage.navcount).click();
			app_alert("System Message","Summary Successfuly Report Unlock","success","Ok","","");
		});
	},1000);
}

function postItem(params,btnid,rowid)
{
	$('#postitembtn' + btnid).attr("disabled", true);
	$('#postitembtn' + btnid).html('<i class="fa fa-spinner fa-spin"></i>');
	var mode = params + "in";

	setTimeout(function()
	{
		$.post("./actions/post_summary_process.php", { mode: mode, rowid: rowid },
		function(data) {
			$(".results").html(data);
//			$('#' + sessionStorage.navcount).click();
		});
	},1000);
}
$(function()
{
	var wrapper_height = $('#contentdata').height();
	$('#tcontainer').height(wrapper_height - 1);	
	$('.tableFixHeadTF').height($('#tout').height() -5);
	
		// AUTOMATIC TRANSFER DETECTION
	var isconnected = '<?php echo $_SESSION["OFFLINE_MODE"]; ?>';
	if(isconnected == 0)
	{
		var mode = 'rm_checktransfer';
		var params = 'rm_transferin';
	//	rms_reloaderOn('Checking Transfer In...');
		setTimeout(function()
		{
			$.post("./actions/actions.php", { mode: mode, params: params },
			function(data) {
				$('.detection').html(data);
			});
		},5000);		
		setInterval(function()
		{
			$.post("./actions/actions.php", { mode: mode, params: params },
			function(data) {
				$('.detection').html(data);
			});
		},30000);
	}
	$('#posttosummarybtnIN').click(function(){
		$('#posttosummarybtnIN,#unlockbutton,#additembtn,#postAllTransferIn').attr("disabled", true);
		$('#posttosummarybtnIN').html('<i class="fa-solid fa-bring-forward"></i>&nbsp;&nbsp;Posting to Summary&nbsp;&nbsp;<i class="fa fa-spinner fa-spin"></i>');
		
		setTimeout(function()
		{	
			var mode = 'postAllRMTransferIn';
			$.post("./actions/post_summary_process.php", { mode: mode },
			function(data) {
				$(".results").html(data);
			//	$('#' + sessionStorage.navcount).click();
			});
		},1000);

	});
});
$(window).resize(function()
{
	var wrapper_height = $('#contentdata').height();
	$('#tcontainer').height(wrapper_height - 1);
	$('.tableFixHeadTF').height($('#tout').height() -5);
});
function deleteItem(rowid,filename,itemname)
{
	app_confirm("Delete Item","Are you sure to delete " + itemname + "?","warning",filename,rowid);
	return false;
}
function deleteItemYes(rowid,filename)
{
	var mode = 'deleteitem';
	$.post("./actions/actions.php", { mode: mode, rowid: rowid, filename: filename },
	function(data) {
		$('.results').html(data);
		$('#' + sessionStorage.navcount).trigger('click');
	});
}
</script>
