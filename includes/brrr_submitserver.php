<?PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);


include '../init.php';

include '../class/brrr.class.php';

include '../db_config_main.php';
$conn = @new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$brrr = new brrr;


$branch = $functions->GetSession('branch');
$transdate = $functions->GetSession('branchdate');


$connected = ($_SESSION['IS_ONLINE'] === 1) ? 1 : 0;



?>




<?php if ($connected === 1): ?>
	
	<div class="alert alert-success">All modules must be submitted to ensure data consistency and alignment with the Head Office.</div>
	
	<table style="width: 100%" class="table submit-btn" id="submittoserver">
		<tr>
			<td class="btn-td">
				<button class="btn btn-primary" onclick="BrrrSubmitToServer('overhead')"><i class="fa-solid fa-user pull-left"></i> SUBMIT OVERHEAD</button>
			</td>
			<td id="fgts"></td>
			<td style="text-align:right">
				<?php 
					$value = $brrr->GetSubmittedData('overhead', $branch, $transdate, $conn);
					echo $value ."&nbsp;&nbsp;Items Submitted ";
					if($value > 0) { ?>
						| <span class='view-btn icon-color-dodger' onclick="viewData('overhead')">View Items</span>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td class="btn-td"><button class="btn btn-primary" onclick="BrrrSubmitToServer('expense')"><i class="fa-solid fa-right-left pull-left"></i> SUBMIT EXPENSE INPUT</button></td>
			<td id="transfer">&nbsp;</td>
			<td style="text-align:right">
				<?php 
					$value = $brrr->GetSubmittedData('expense', $branch, $transdate, $conn);
					echo $value ."&nbsp;&nbsp;Items Submitted ";
					if($value > 0) { ?>
						| <span class='view-btn icon-color-dodger' onclick="viewData('expense')">View Items</span>
				<?php } ?>
			</td>
		</tr>
		
		<tr>
			<td class="btn-td">
				<button class="btn btn-primary" onclick="BrrrSubmitToServer('summary')">
					<i class="fa-solid fa-chart-line pull-left"></i> SUBMIT SUMMARY
				</button>
			</td>
			<td id="summarystatus">&nbsp;</td>
			<td style="text-align:right">
				<?php 
					$value = $brrr->GetSubmittedData('summary', $branch, $transdate, $conn);
					echo $value ."&nbsp;&nbsp;Items Submitted ";
					if($value > 0) { ?>
						| <span class='view-btn icon-color-dodger' onclick="viewData('summary')">View Items</span>
				<?php } ?>
			</td>
		</tr>
		
		
	</table>
<?php else: ?>
	<div class="alert alert-danger">
		<i class="fa fa-exclamation-circle" aria-hidden="true"></i> Cannot connect to Head Office server. Internet connection is required to submit data.
	</div>
<?php endif; ?>




<script>


function viewData(params)
{

	psaSpinnerOn();
	setTimeout(function()
	{
		$('#viewserverdata_title').html(params.toUpperCase() + " SERVER DATA");
		$.post("../actions/brrr_view_data.php", { tablename: params },
		function(data) {
			$('#viewserverdata').show();
			$('#viewserverdata_page').html(data);
			psaSpinnerOff();
		});
	},1000);

}


function BrrrSubmitToServer(params) 
{

	psaSpinnerOn();
	setTimeout(function()
	{
		$.post("../actions/brrr_submit_to_server.php", { modulename: params },
		function(data) {
			$('#querymessage').html(data);
			set_function('BRRR Submit To Server','brrr_submitserver');
			psaSpinnerOff();
	
		});
	},4000);
}
</script>