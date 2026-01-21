<?PHP
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
include '../db_config_main.php';
$conn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
?>
<style>

.scroll-table {
    max-height: 600px;
    overflow-y: auto;
    overflow-x: hidden;
    border: 1px solid #ddd;
    padding-right: 5px;
}

.tdtd td {
	text-align:center;
}
</style>
<div class="scroll-table">
	<table style="width: 100%" class="table tdtd">
		<tr>
			<th style="width: 48%">MAIN&nbsp;</th>
			<th style="width:10PX">&nbsp;</th>
			<th style="width: 48%">BRANCH</th>
			<th style="width:10PX">&nbsp;</th>
			<th style="width: 70px">ACTIONS</th>
		</tr>
		<tr>
			<td colspan="5" style="text-align:left; font-size:16px;font-weight:bold"><i class="fa fa-database" aria-hidden="true"></i> EMPLOYEES</td>
		</tr>
		<tr>
			<td><?php echo $functions->GetDataStatus('employees','main',$branch,$db,$conn); ?></td>
			<td>&nbsp;</td>
			<td><?php echo $functions->GetDataStatus('employees','branch',$branch,$db,$conn); ?></td>
			<td></td>
			<td><button style="text-align:center" class="btn btn-primary" onclick="UpdateEmployees()"><i class="fa fa-bookmark" aria-hidden="true"></i> UPDATE</button></td>
		</tr>
		<tr>
			<td colspan="5" style="text-align:left; font-size:16px;font-weight:bold"><i class="fa fa-database" aria-hidden="true"></i> BRANCH LIST</td>
		</tr>	
		<tr>
			<td><?php echo $functions->GetDataStatus('branch','main',$branch,$db,$conn); ?></td>
			<td>&nbsp;</td>
			<td><?php echo $functions->GetDataStatus('branch','branch',$branch,$db,$conn); ?></td>
			<td></td>
			<td><button style="text-align:center" class="btn btn-primary" onclick="UpdateBranch()"><i class="fa fa-bookmark" aria-hidden="true"></i> UPDATE</button></td>
		</tr>	
		<tr>
		<td colspan="5" style="text-align:left; font-size:16px;font-weight:bold"><i class="fa fa-database" aria-hidden="true"></i> APP USERS</td>
		</tr>
		<tr>
			<td><?php echo $functions->GetDataStatus('users','main',$branch,$db,$conn); ?></td>
			<td>&nbsp;</td>
			<td><?php echo $functions->GetDataStatus('users','branch',$branch,$db,$conn); ?></td>
			<td></td>
			<td><button style="text-align:center" class="btn btn-primary" onclick="UpdateUsers()"><i class="fa fa-bookmark" aria-hidden="true"></i> UPDATE</button></td>
		</tr>	
		<tr>
			<td colspan="5" style="text-align:left; font-size:16px;font-weight:bold"><i class="fa fa-database" aria-hidden="true"></i> ITEM LISTS</td>
		</tr>
		<tr>
			<td><?php echo $functions->GetDataStatus('items','main',$branch,$db,$conn); ?></td>
			<td>&nbsp;</td>
			<td><?php echo $functions->GetDataStatus('items','branch',$branch,$db,$conn); ?></td>
			<td></td>
			<td><button style="text-align:center" class="btn btn-primary" onclick="UpdateItems()"><i class="fa fa-bookmark" aria-hidden="true"></i> UPDATE</button></td>
		</tr>	
	
		<tr>
			<td colspan="5" style="text-align:left; font-size:16px;font-weight:bold"><i class="fa fa-database" aria-hidden="true"></i> BAKER'S GUIDE</td>
		</tr>
		<tr>
			<td><?php echo $functions->GetDataStatus('bakersguide','main',$branch,$db,$conn); ?></td>
			<td>&nbsp;</td>
			<td><?php echo $functions->GetDataStatus('bakersguide','branch',$branch,$db,$conn); ?></td>
			<td></td>
			<td><button style="text-align:center" class="btn btn-primary" onclick="UpdateBakersGuide()"><i class="fa fa-bookmark" aria-hidden="true"></i> UPDATE</button></td>
		</tr>	
		



		<tr>
			<td colspan="5" style="text-align:left; font-size:16px;font-weight:bold"><i class="fa fa-database" aria-hidden="true"></i> PRODUCTION SETTINGS</td>
		</tr>
		<tr>
			<td><?php echo $functions->GetDataStatus('branchlist_burgerbuns','main',$branch,$db,$conn); ?></td>
			<td>&nbsp;</td>
			<td><?php echo $functions->GetDataStatus('branchlist_burgerbuns','branch',$branch,$db,$conn); ?></td>
			<td></td>
		
		<td rowspan="4" style="vertical-align:middle"><button style="text-align:center" style="width:100%; height:100%; padding: 10px 0;" class="btn btn-primary" onclick="UpdateProductionSettings()"><i class="fa fa-bookmark" aria-hidden="true"></i> UPDATE</button></td>
		</tr>	
		
		<tr>
			<td><?php echo $functions->GetDataStatus('branchlist_wheatloaf','main',$branch,$db,$conn); ?></td>
			<td>&nbsp;</td>
			<td><?php echo $functions->GetDataStatus('branchlist_wheatloaf','branch',$branch,$db,$conn); ?></td>
			<td></td>

		</tr>	

		<tr>
			<td><?php echo $functions->GetDataStatus('branchlist_production_exclude_items','main',$branch,$db,$conn); ?></td>
			<td>&nbsp;</td>
			<td><?php echo $functions->GetDataStatus('branchlist_production_exclude_items','branch',$branch,$db,$conn); ?></td>
			<td></td>
		</tr>	
		
		<tr>
			<td><?php echo $functions->GetDataStatus('ba_rm_header','main',$branch,$db,$conn); ?></td>
			<td>&nbsp;</td>
			<td><?php echo $functions->GetDataStatus('ba_rm_header','branch',$branch,$db,$conn); ?></td>
			<td></td>
		</tr>	
		
		

	
	
	</table>
</div>
<div id="dataresults"></div>
<script>

function UpdateProductionSettings()
{

	var mode = 'UpdateProductionSettings';
	rms_reloaderOn('Updating Store Items...');
	setTimeout(function()
	{
		$.post("./updates/process.php", { mode: mode },
		function(data) {
			$('#dataresults').html(data);						
			rms_reloaderOff();
		});
	},1000);
}

function UpdateBakersGuide()
{
	var mode = 'updatingbakersguide';
	rms_reloaderOn('Updating Store Items...');
	setTimeout(function()
	{
		$.post("./updates/process.php", { mode: mode },
		function(data) {
			$('#dataresults').html(data);						
			rms_reloaderOff();
		});
	},1000);
}

function UpdateItems()
{
	var mode = 'updatingproducts';
	rms_reloaderOn('Updating Store Items...');
	setTimeout(function()
	{
		$.post("./updates/process.php", { mode: mode },
		function(data) {
			$('#dataresults').html(data);						
			rms_reloaderOff();
		});
	},1000);
}

function UpdateUsers()
{
	var mode = 'checkusers';
	rms_reloaderOn('Updating Users...');
	setTimeout(function()
	{
		$.post("./updates/process.php", { mode: mode },
		function(data) {
			$('#dataresults').html(data);						
			rms_reloaderOff();
		});
	},1000);
}
function UpdateEmployees()
{
	var mode = 'checkemployees';
	rms_reloaderOn('Updating Employees...');
	setTimeout(function()
	{
		$.post("./updates/process.php", { mode: mode },
		function(data) {
			$('#dataresults').html(data);						
			rms_reloaderOff();
		});
	},1000);
}
function UpdateBranch()
{
	var mode = 'checkbranch';
	rms_reloaderOn('Updating Branch...');
	setTimeout(function()
	{
		$.post("./updates/process.php", { mode: mode },
		function(data) {
			$('#dataresults').html(data);						
			rms_reloaderOff();
		});
	},1000);
}

</script>