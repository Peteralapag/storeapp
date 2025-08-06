<?PHP

include '../init.php';
include '../db_config_main.php';

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
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

$sql = "SELECT branch, report_date, lock_by, unlock_by FROM store_datelock_checker WHERE branch='$branch' AND branch_execute=0 AND office_execute=1 AND status=0";
$result = $conn->query($sql);
if ($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {
        $branch = $row["branch"];
        $reportDate = $row["report_date"];
        $lockBy = $row["lock_by"];
        $unlockBy = $row["unlock_by"];
        $functions->dateLockCheckerInsert($branch, $reportDate, $lockBy, $unlockBy, $db);
        $functions->dateLockCheckerUpdate($branch, $reportDate, $lockBy, $unlockBy, $conn);
    }
}


$dateLockChecker = $functions->dateLockChecker($branch,$transdate,$db);

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
		<td class="btn-td"><button class="btn btn-primary" onclick="SubmitToServer('foodpanda')"><i class="fa fa-motorcycle" aria-hidden="true"></i><?php echo $btn_spacher; ?>SUBMIT FOOD PANDA</button></td>
		<td id="discount">&nbsp;</td>
		<td style="text-align:right">
			<?php 
				if($connected == 1){$value = $functions->GetSubmittedData('foodpanda',$branch,$transdate,$shift,$conn);} else { $value = 0; }
				echo $value ."&nbsp;&nbsp;Items Submitted ";
				if($value > 0) { ?>
					| <span class='view-btn icon-color-dodger' onclick="viewData('foodpanda')">View Items</span>
					| <span class='view-btn icon-color-dodger' onclick="deleteData('foodpanda')">Delete All Items</span>
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
	psaSpinnerOn();
	setTimeout(function()
	{
		$.post("../actions/delete_to_server.php", { modulename: params },
		function(data) {
			$('#querymessage').html(data);
			set_function('To Server','submitserver');
			psaSpinnerOff();
		});
	},1000);
}
function viewData(params)
{

	psaSpinnerOn();
	setTimeout(function()
	{
		$('#viewserverdata_title').html(params.toUpperCase() + " SERVER DATA");
		$.post("../actions/view_data.php", { tablename: params },
		function(data) {
			$('#viewserverdata').show();
			$('#viewserverdata_page').html(data);
			psaSpinnerOff();
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

	psaSpinnerOn();
	setTimeout(function()
	{
		$.post("../actions/submit_to_server.php", { modulename: params },
		function(data) {
			$('#querymessage').html(data);
			set_function('To Server','submitserver');
			psaSpinnerOff();
	
		});
	},4000);
}
</script>