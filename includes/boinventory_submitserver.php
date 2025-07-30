<?PHP
include '../init.php';
include '../db_config_main.php';
$functions = new TheFunctions;
$btn_spacher = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
$branch = $functions->AppBranch();

$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');

if($_SESSION['OFFLINE_MODE'] == 0 AND $_SESSION['IS_ONLINE'] == 1)
{	
	$conn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);
	$connected = 1;
}
else
{
	$connected = 0;
}
?>
<style>
.submit-btn td button {
	width:280px;
	text-align:left
}
.btn-td {
	width:350px;
}
</style>
<script>
$(function()
{
	var is_connected = '<?php echo $_SESSION["OFFLINE_MODE"]; ?>';
	var is_online = '<?php echo $_SESSION["IS_ONLINE"]; ?>';
	if(is_connected == 1 || is_online == 0)
	{
		$('#submittoserver :button').prop('disabled', true);
		$('.blockinput').show();
	} else {
		$('#submittoserver :button').prop('disabled', false);
		$('.blockinput').hide();
	}
});
</script>
<?PHP
	$btn_spacher = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
	if($connected == 1) {
?>
<table style="width: 100%" class="table submit-btn" id="submittoserver">
	
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('boinventory_receiving')"><i class="fa-solid fa-inbox-in pull-left"></i><?php echo $btn_spacher; ?>SUBMIT RECEIVING</button></td>
		<td id="rm_receiving">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('boinventory_receiving',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('boinventory_receiving')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('boinventory_receiving')">Delete All Items</span>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('boinventory_transfer')"><i class="fa-solid fa-right-left pull-left"></i><?php echo $btn_spacher; ?>SUBMIT TRANSFER IN/OUT</button></td>
		<td id="rm_transfer">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('boinventory_transfer',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('boinventory_transfer')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('boinventory_transfer')">Delete All Items</span>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('boinventory_badorder')"><i class="fa-solid fa-send-back pull-left"></i><?php echo $btn_spacher; ?>SUBMIT BAD ORDER</button></td>
		<td id="rm_badorder">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('boinventory_badorder',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('boinventory_badorder')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('boinventory_badorder')">Delete All Items</span>
			<?php } ?>
		</td>
	</tr>	
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('boinventory_pcount')"><i class="fa-solid fa-tally pull-left"></i><?php echo $btn_spacher; ?>SUBMIT PHYSICAL COUNT</button></td>
		<td id="rm_pcount">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('boinventory_pcount',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('boinventory_pcount')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('boinventory_pcount')">Delete All Items</span>

			<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('boinventory_summary')"><i class="fa-solid fa-file-spreadsheet pull-left"></i><?php echo $btn_spacher; ?>SUBMIT SUMMARY</button></td>
		<td id="rm_summary">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('boinventory_summary',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('boinventory_summary')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('boinventory_summary')">Delete All Items</span>

			<?php } ?>
		</td>
	</tr>
</table>
<?php } ?>
<style>
.blockinput {
	position: absolute;
	top: 50%;
	left: 50%;
	max-height:95%;
	max-width:95%;
	-webkit-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	-webkit-box-shadow: 0 3px 9px rgba(0,0,0,.5);
	box-shadow: 0 3px 9px rgba(0,0,0,.5);
	border-radius: 10px;
	background: #fff;
	border:1px solid silver;
	overflow: hidden;
	padding: 20px;
	font-size:32px;
	text-align: center;
	display:none;
}
.info-size {
	font-size:32px;
}
</style>
<div class="blockinput"><i class="fa-solid fa-do-not-enter icon-color-red"></i> Offline Mode is ON / Server is offline<br>
or Internet is not available at this Time<br>
<span style="font-size:18px;">Please try again later!</span></div>
<div id="querymessage"></div>
<div id="viewdatas"></div>
<script>
function deleteData(params){
	 app_confirm("Delete Summary Item","Are you sure to delete to Server?","warning","deleteServerDataYes",params,"true");
}
function deleteServerDataYes(params){
	rms_reloaderOn("Deleting to Server. Please Wait...");
	setTimeout(function()
	{
		$.post("../actions/delete_to_server.php", { modulename: params },
		function(data) {
			$('#querymessage').html(data);
			rms_reloaderOff();
			set_function('To Server','scrapinventory_submitserver');
			
		});
	},1000);
}
function viewData(params)
{
//	swal("Notice","This feature is not available yet and it is under development", "warning");

	rms_reloaderOn("View Submitted to Server. Please Wait...");
	setTimeout(function()
	{
		$('#viewserverdata_title').html(params.toUpperCase() + " SERVER DATA");
		$.post("../actions/view_data.php", { tablename: params },
		function(data) {
			$('#viewserverdata').show();
			$('#viewserverdata_page').html(data);
			rms_reloaderOff();
		});
	},1000);
}
function SubmitToServer(params) 
{
	rms_reloaderOn("Submitting to Server. Please Wait...");
	setTimeout(function()
	{
		$.post("../actions/submit_to_server.php", { modulename: params },
		function(data) {
			$('#querymessage').html(data);
			rms_reloaderOff();
		});
	},1000);
}
</script>