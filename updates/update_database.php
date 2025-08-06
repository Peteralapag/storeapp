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
.tdtd td {
	text-align:center;
}
</style>
<table style="width: 100%" class="table tdtd">
	<tr>
		<th style="width: 48%">MAIN&nbsp;</th>
		<th style="width:10PX">&nbsp;</th>
		<th style="width: 48%">BRANCH</th>
		<th style="width:10PX">&nbsp;</th>
		<th style="width: 70px">ACTIONS</th>
	</tr>
	<tr>
		<td colspan="5" style="text-align:left; font-size:16px;font-weight:bold">EMPLOYEES</td>
	</tr>
	<tr>
		<td><?php echo $functions->GetDataStatus('employees','main',$branch,$db,$conn); ?></td>
		<td>&nbsp;</td>
		<td><?php echo $functions->GetDataStatus('employees','branch',$branch,$db,$conn); ?></td>
		<td></td>
		<td><button style="text-align:center" class="btn btn-primary" onclick="UpdateEmployees()">UPDATE</button></td>
	</tr>
	<tr>
		<td colspan="5" style="text-align:left; font-size:16px;font-weight:bold">BRANCH LIST</td>
	</tr>	
	<tr>
		<td><?php echo $functions->GetDataStatus('branch','main',$branch,$db,$conn); ?></td>
		<td>&nbsp;</td>
		<td><?php echo $functions->GetDataStatus('branch','branch',$branch,$db,$conn); ?></td>
		<td></td>
		<td><button style="text-align:center" class="btn btn-primary" onclick="UpdateBranch()">UPDATE</button></td>
	</tr>	
	<tr>
	<td colspan="5" style="text-align:left; font-size:16px;font-weight:bold">APP USERS</td>
	</tr>
	<tr>
		<td><?php echo $functions->GetDataStatus('users','main',$branch,$db,$conn); ?></td>
		<td>&nbsp;</td>
		<td><?php echo $functions->GetDataStatus('users','branch',$branch,$db,$conn); ?></td>
		<td></td>
		<td><button style="text-align:center" class="btn btn-primary" onclick="UpdateUsers()">UPDATE</button></td>
	</tr>	
	<tr>
		<td colspan="5" style="text-align:left; font-size:16px;font-weight:bold">ITEM LISTS</td>
	</tr>
	<tr>
		<td><?php echo $functions->GetDataStatus('items','main',$branch,$db,$conn); ?></td>
		<td>&nbsp;</td>
		<td><?php echo $functions->GetDataStatus('items','branch',$branch,$db,$conn); ?></td>
		<td></td>
		<td><button style="text-align:center" class="btn btn-primary" onclick="UpdateItems()">UPDATE</button></td>
	</tr>	
</table>
<div id="dataresults"></div>
<script>
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