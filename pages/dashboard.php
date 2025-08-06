<?php
include '../actions/datelock_permissions_process.php';

$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');

include '../db_config_main.php';
$conn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);


$sidebar = $_SESSION['session_sidebar'];


function mainCheckingAnalystVal($branch,$transdate,$sidebar,$conn)
{
	$table = 'store_datelock_checker';
	if($sidebar == 'rawmats'){
		$table = 'store_datelock_checker_rm';
	}
	$sql = "SELECT * FROM $table WHERE report_date='$transdate' AND branch='$branch' AND office_execute=1";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		return 1;
	} 
	else
	{	  
		return 0; 
	}
	$conn->close();
}


$checker = mainCheckingAnalystVal($branch,$transdate,$sidebar,$conn);
$lock_by = $functions->analystValVer2($branch,$transdate,$sidebar,$conn);



?>
<div class="dashboard-wrapper">
	<div class="dash-left">
		<div class="alert alert-success">The new update on the StoreApps dashboard page has a button that you can click to request again from your analyst for permission to submit.</div>
		<button type="button" class="btn btn-info" onclick="confirmMe()">Click me to enable another submission.</button>
	</div>
</div>
<div id="dashboardResult"></div>

<script>
function confirmMe(){
	var checker = '<?php echo $checker;?>';
	var lockBy = '<?php echo $lock_by;?>';
	if(checker == 1){
		app_alert('System Message','Please contact '+lockBy+' the Data Analyst','warning');
	}
	else 
	{
		var sidebar = '<?php echo $sidebar?>';
		var mode = 'deletedatelocker';
		$.post("../actions/actions.php", { mode: mode, sidebar: sidebar },
		function(data) {
			$('#dashboardResult').html(data);
		});
	}
}
</script>