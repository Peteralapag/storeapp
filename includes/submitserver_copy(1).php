<?PHP
include '../init.php';
include '../db_config_main.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$btn_spacher = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$dateLockChecker = $functions->dateLockChecker($branch,$transdate,$db);


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
.submit-btn td button {	width:280px;text-align:left}
.btn-td {width:350px;}
.view-btn {font-weight:bold;cursor:pointer;}
.view-btn:hover {
	color:red;
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
<div class="alert alert-success">All modules need to be submitted to ensure that all data aligns with your branch and the branch being reviewed by the analyst at the Head Office.</div>

<?php if($connected == 1) { ?>
<table style="width: 100%" class="table submit-btn" id="submittoserver">
	<tr>
		<td class="btn-td">
			<button class="btn btn-primary" onclick="SubmitToServer('fgts')"><i class="fa-solid fa-utensils pull-left"></i><?php echo $btn_spacher; ?>SUBMIT FGTS</button>
		</td>
		<td id="fgts"></td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('fgts',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('fgts')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('fgts')">Delete All Items</span>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('transfer')"><i class="fa-solid fa-right-left pull-left"></i><?php echo $btn_spacher; ?>SUBMIT TRANSFER IN/OUT</button></td>
		<td id="transfer">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){
					$value = $functions->GetSubmittedData('transfer',$branch,$transdate,$shift,$conn);
				}
				else{ 
					$value = 0; 
				}
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('transfer')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('transfer')">Delete All Items</span>

			<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('charges')"><i class="fa-solid fa-file-invoice-dollar pull-left"></i><?php echo $btn_spacher; ?>SUBMIT CHARGES</button></td>
		<td id="charges">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('charges',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('charges')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('charges')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr>
	<!--tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('snacks')"><i class="fa-solid fa-popcorn pull-left"></i><?php echo $btn_spacher; ?>SUBMIT SNACKS</button></td>
		<td id="snacks">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('snacks',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('snacks')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('snacks')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr>	
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('badorder')"><i class="fa-solid fa-send-back pull-left"></i><?php echo $btn_spacher; ?>SUBMIT BAD ORDER</button></td>
		<td id="badorder">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('badorder',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('badorder')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('badorder')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr>	
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('damage')"><i class="fa-solid fa-wine-glass-crack pull-left"></i><?php echo $btn_spacher; ?>SUBMIT DAMAGE</button></td>
		<td id="damage">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('damage',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('damage')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('damage')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr>	
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('complimentary')"><i class="fa-sharp fa-solid fa-burger-soda pull-left"></i><?php echo $btn_spacher; ?>SUBMIT COMPLIMENTARY</button></td>
		<td id="complimentary">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('complimentary',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('complimentary')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('complimentary')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr-->	
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('receiving')"><i class="fa-solid fa-inbox-in pull-left"></i><?php echo $btn_spacher; ?>SUBMIT RECEIVING</button></td>
		<td id="receiving">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('receiving',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('receiving')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('receiving')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr>	
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('cashcount')"><i class="fa-solid fa-treasure-chest pull-left"></i><?php echo $btn_spacher; ?>SUBMIT CASH COUNT</button></td>
		<td id="cashcount">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('cashcount',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('cashcount')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('cashcount')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr>
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('frozendough')"><i class="fa-solid fa-icicles pull-left"></i><?php echo $btn_spacher; ?>SUBMIT FROZEN DOUGH</button></td>
		<td id="frozendough">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('frozendough',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('frozendough')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('frozendough')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr>
	<!--tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('pcount')"><i class="fa-solid fa-tally pull-left"></i><?php echo $btn_spacher; ?>SUBMIT PHYSICAL COUNT</button></td>
		<td id="pcount">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('pcount',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('pcount')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('pcount')">Delete All Items</span>

			<?php } ?>
		</td>

	</tr-->
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('discount')"><i class="fa-solid fa-tags pull-left"></i><?php echo $btn_spacher; ?>SUBMIT DISCOUNT</button></td>
		<td id="discount">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('discount',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('discount')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('discount')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr>
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('gcash')"><i class="fa-solid fa-treasure-chest pull-left"></i><?php echo $btn_spacher; ?>SUBMIT GCASH SALES</button></td>
		<td id="discount">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('gcash',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('gcash')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('gcash')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr>
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('grab')"><i class="fa fa-motorcycle" aria-hidden="true"></i><?php echo $btn_spacher; ?>SUBMIT GRAB</button></td>
		<td id="discount">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('grab',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('grab')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('grab')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr>
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('pakati')"><i class="fa-solid fa-treasure-chest pull-left"></i><?php echo $btn_spacher; ?>SUBMIT PAKATI</button></td>
		<td id="discount">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('pakati',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('pakati')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('pakati')">Delete All Items</span>
			<?php } ?>
		</td>

	</tr>
	<!--tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('production')"><i class="fa-solid fa-hammer pull-left"></i><?php echo $btn_spacher; ?>SUBMIT PRODUCTION</button></td>
		<td id="production">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('production',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('production')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('production')">Delete All Items</span>
			<?php } ?>
			
		</td>

	</tr-->
	<tr>
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('summary')"><i class="fa-solid fa-file-spreadsheet pull-left"></i><?php echo $btn_spacher; ?>SUBMIT SUMMARY</button></td>
		<td id="summary">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('summary',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('summary')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('summary')">Delete All Items</span>
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
	var dateLockChecker = '<?php echo $dateLockChecker; ?>';
	if(dateLockChecker == 1){
		app_alert("System Message","The date is already locked, if there are any changes, please contact the Analysts.","warning","Ok","","");
		return false();
	}
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
			set_function('To Server','submitserver');
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
	var dateLockChecker = '<?php echo $dateLockChecker; ?>';
	if(dateLockChecker == 1){
		app_alert("System Message","The date is already locked, if there are any changes, please contact the Analysts.","warning","Ok","","");
		return false();
	}

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