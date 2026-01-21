<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');

$file_name = $_POST['pagename'];
$title = strtoupper($file_name);
$table = "store_".$file_name."_data";
if($functions->CheckData($table,$functions->AppBranch(),$functions->GetSession('branchdate'),$functions->GetSession('shift'),$db) == 1)
{
	$action_btn = '';
} else {
	$action_btn = 'disabled';
}
if($file_name == 'cashcount')
{
	$post_to = 'CASH COUNT';
} else {
	$post_to = 'TO SUMMARY';
}


$dateLockCheckerrm = $functions->dateLockCheckerRM($branch,$transdate,$db);
?>
<style>
.pagemenu {
	border: 1px solid var(--text-grey);
	background: #cecece;
	padding:5px 15px 5px 15px;
	border-radius:7px;
	cursor: pointer;
	color: var(--text-grey);
}
.pagemenu:hover {
	background: #f1f1f1;
	border: 1px solid #f1f1f1;
}
.downdownmenu-wrapper {
	position:absolute;
	top:40px;
	display:none;
	background:#fff;
	width:350px;
	z-index:20;
	border-radius:5px;
	-webkit-box-shadow: 0 3px 9px rgba(0,0,0,.5);
	box-shadow: 0 3px 9px rgba(0,0,0,.5);
	overflow-y:auto;
}
</style>
<table style="width: 100%;border-collapse:collapse;white-space:nowrap" cellpadding="0" cellspacing="0">
	<tr>
		<td style="width:50px;position:relative">
			<i id="submenu" class="fa-solid fa-caret-down pagemenu" onclick="openSubMenu('<?php echo $file_name; ?>','<?php echo $title; ?>')"></i>
			<div class="downdownmenu-wrapper" id="downdownmenuwrapper"></div>
		</td>
		<td style="width:0.5em" class="branch-info"></td>
		<td style="width:250px;" class="branch-info">
			<input type="text" class="form-control" value="<?php echo $functions->AppBranch(); ?>">
		</td>
		<td style="width:0.5em" class="branch-info"></td>
		<td style="width:150px" class="branch-info">
			<input id="date" type="text" class="form-control" value="<?php echo $functions->GetSession('branchdate'); ?>">
		</td>
		<td style="width:0.5em" class="branch-info"></td>
		<td style="width:150px" class="branch-info">
			<input id="shift" type="text" class="form-control" value="<?php echo $functions->GetSession('shift'); ?>">
		</td>
		<td style="width:0.5em" class="branch-info"></td>
		<td style="width:150px">
			
		</td>
		<td style="text-align:right;">
			<!--button id="gendum" class="btn btn-primary" onclick="generateDUM()">Generate DUM</button>
			<button class="btn btn-warning" onclick="calculateDUM()" <?php echo $action_btn; ?>>Calculate DUM</button>
			<button class="btn btn-danger" onclick="reGenerateDUM()" <?php echo $action_btn; ?>>Regeneate DUM</button-->
		</td>
	</tr>
</table>
<div class="Results"></div>
<script>
function calculateDUM()
{

	var dateLockChecker = '<?php echo $dateLockCheckerrm; ?>';
	if(dateLockChecker == 1){
		app_alert("System Message","The date is already locked, if there are any changes, please contact the Analysts.","warning","Ok","","");
		return false();
	}

	rms_reloaderOn('Calculating all DUM...');
	setTimeout(function()
	{
		$.post("./actions/calculate_dum.php", {  },
		function(data) {
			$('.Results').html(data);
			rms_reloaderOff();
			$('#' + sessionStorage.navcount).trigger('click');
			set_function('Daily Usage Report','dum');
		});
	},700);
}
function generateDUM()
{

	var dateLockChecker = '<?php echo $dateLockCheckerrm; ?>';
	if(dateLockChecker == 1){
		app_alert("System Message","The date is already locked, if there are any changes, please contact the Analysts.","warning","Ok","","");
		return false();
	}

	
	rms_reloaderOn('Fetching Inventory...');
	setTimeout(function()
	{
		$.post("./actions/fetch_dum.php", {  },
		function(data) {
			$('.Results').html(data);
			rms_reloaderOff();
			set_function('Daily Usage Report','dum');
		});
	},700);
}
function reGenerateDUM()
{
	var dateLockChecker = '<?php echo $dateLockCheckerrm; ?>';
	if(dateLockChecker == 1){
		app_alert("System Message","The date is already locked, if there are any changes, please contact the Analysts.","warning","Ok","","");
		return false();
	}

	app_confirm("Regenerate DUM Report","Are you sure to do this? All data will be deleted and regenerate from default value. If you are not sure please select No","warning","deletedumreport",'<?php echo $transdate; ?>');
}
function reGenerateDUMYes(reportdate)
{
	rms_reloaderOn('Regenerating DUM...');
	setTimeout(function()
	{
		$.post("./actions/delete_dum_report.php", { reportdate: reportdate },
		function(data) {
			$('.dumresults').html(data);
			rms_reloaderOff();
			set_function('Daily Usage Report','dum');
		});
	},1000);
}

function openSubMenu(filename,title)
{
	$.post("./includes/submenu.php", { title: title, filename: filename },
	function(data) {
		$("#downdownmenuwrapper").html(data);
		if($("#downdownmenuwrapper").is(":visible"))
		{
			$('#downdownmenuwrapper').slideUp();
		} else {
			$('#downdownmenuwrapper').slideDown();
		}
	});
}
</script>