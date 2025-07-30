<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');

$file_name = $_POST['pagename'];
$title = strtoupper($file_name);

$dateLockChecker = $functions->dateLockChecker($branch,$transdate,$db);
$lock_by = $functions->analystVal($branch,$transdate,$db);

$table = "store_summary_data";
if($functions->CheckData($table,$functions->AppBranch(),$functions->GetSession('branchdate'),$functions->GetSession('shift'),$db) == 1)
{
	$fetch_btn = '';
	$price_btn = '';
	$summary_btn = '';
	if($functions->GetSession('userlevel') == 50 || $functions->GetSession('userlevel') >= 80)
	{
		$unlock_btn = '';
	} else {
		$unlock_btn = 'style="display: none"';
	}
} else {
	$summary_btn = 'disabled';
	$unlock_btn = 'disabled';
	$fetch_btn = 'disabled';
	$price_btn = 'disabled';
}
$summary_btn = $functions->detechPostedData($table,$branch,$transdate,$db) === 1? '': 'disabled';
?>
<style>
</style>
<table style="width: 100%;border-collapse:collapse;white-space:nowrap" cellpadding="0" cellspacing="0">
	<tr>
		
		<td style="position:relative;">
		  <input id="itemsearch" type="text" class="form-control form-control-sm" placeholder="Search Item" autocomplete="off" style="width:250px;padding-left : 30px;">     
		  <i class="fa fa-search" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: gray;"></i>
		</td>
		

		<td style="width:10px"></td>
		<!--td style="width:150px"><button class="btn btn-info" onclick="FetchEndingToBeginning()">Fetch Beginning</button></td-->
		<td style="text-align:right">
			<!--button id="posttosummarybtn" class="btn btn-success" <?php echo $summary_btn; ?> onclick="postSummary('<?php echo $file_name; ?>')"><i class="fa-solid fa-bring-forward"></i>&nbsp;&nbsp;POST SUMMARY</button>&nbsp;	-->		
		</td>
	</tr>
</table>
<div class="Results"></div>
<script>

document.getElementById("itemsearch").addEventListener("keyup", function () {
	const searchValue = this.value.toLowerCase();
	const rows = document.querySelectorAll("#upper tbody tr");

	rows.forEach(function (row) {
		const rowText = row.textContent.toLowerCase();
		row.style.display = rowText.includes(searchValue) ? "" : "none";
	});
});

function FetchEndingToBeginning()
{
	rms_reloaderOn("Fetching Begging! Please Wait...");
	$.post("./actions/fetch_ending_to_beginning.php", { },
	function(data) {
		$(".Results").html(data);
		rms_reloaderOff();
	});
}

function dltsummary(){

	var userAnalyst ='<?php echo $lock_by;?>';
	var dateLockChecker = '<?php echo $dateLockChecker; ?>';
	if(dateLockChecker == 1){
		app_alert("System Message","The date is already locked, if there are any changes, please contact "+userAnalyst+" the Data Analysts","warning","Ok","","");
		return false();
	}

	app_confirm("Delete Summary","Are you sure to clear all summary?",'warning',"delete_sumari","","red");
}
function delete_sumariYes()
{
	var userAnalyst ='<?php echo $lock_by;?>';
	var dateLockChecker = '<?php echo $dateLockChecker; ?>';
	if(dateLockChecker == 1){
		app_alert("System Message","The date is already locked, if there are any changes, please contact "+userAnalyst+" the Data Analysts","warning","Ok","","");
		return false();
	}
	
	var mode = 'deletesummari';			
	var branch = '<?php echo $branch; ?>';
	var report_date = '<?php echo $transdate; ?>';
	var shift = '<?php echo $shift; ?>';			
	rms_reloaderOn('Deleting all summary...');
	setTimeout(function()
	{
		$.post("./actions/actions.php", { mode: mode, branch: branch, report_date: report_date, shift: shift },
		function(data) {
			$(".Results").html(data);
			rms_reloaderOff();
			$('#' + sessionStorage.navcount).click();
		});
	},1000);
}
function calculateSummary()
{
	var userAnalyst ='<?php echo $lock_by;?>';
	var dateLockChecker = '<?php echo $dateLockChecker; ?>';
	if(dateLockChecker == 1){
		app_alert("System Message","The date is already locked, if there are any changes, please contact "+userAnalyst+" the Data Analysts","warning","Ok","","");
		return false();
	}

	var mode = 'calculateSummary';
	rms_reloaderOn('Calculating...');
	setTimeout(function()
	{
		$.post("./actions/calculate_summary_process.php", { mode: mode },
		function(data) {
			$(".Results").html(data);
			rms_reloaderOff();
			$('#' + sessionStorage.navcount).click();
		});
	},1000);
}
function postSummary(file_name)
{
	var userAnalyst ='<?php echo $lock_by;?>';
	var dateLockChecker = '<?php echo $dateLockChecker; ?>';
	if(dateLockChecker == 1){
		app_alert("System Message","The date is already locked, if there are any changes, please contact "+userAnalyst+" the Data Analysts","warning","Ok","","");
		return false();
	}

	var mode = 'postsummaries';
	rms_reloaderOn('Posting Summary');
	setTimeout(function()
	{
		$.post("./actions/post_summary_process.php", { mode: mode, file_name: file_name },
		function(data) {
			$(".Results").html(data);
			rms_reloaderOff();
			$('#' + sessionStorage.navcount).click();
		});
	},1000);
}
function fetchBeginnings()
{
	$('#posttosummarybtn,#fetchbegbtn').attr("disabled", true);
	$('#fetchbegbtn').html('<i class="fa-solid fa-bring-forward"></i>&nbsp;&nbsp;Fetching Beginnings&nbsp;&nbsp;<i class="fa fa-spinner fa-spin"></i>');
	var mode = 'fetchbeginnings';
	var shift = $('shift').val();
	var table = 'store_pcount_data';
	setTimeout(function()
	{
		$.post("./actions/fetch_beginnings_process.php", { mode: mode, shift: shift, table: table },
		function(data) {
			$(".Results").html(data);
			$('#' + sessionStorage.navcount).click();
		});
	},1000);
}
</script>