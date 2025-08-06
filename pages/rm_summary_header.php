<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');

$file_name = $_POST['pagename'];
$title = strtoupper($file_name);

$table = "store_rm_summary_data";
if($functions->CheckData($table,$functions->AppBranch(),$functions->GetSession('branchdate'),$functions->GetSession('shift'),$db) == 1)
{
	$fetch_btn = '';
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
}
?>
<table style="width: 100%;border-collapse:collapse;white-space:nowrap" cellpadding="0" cellspacing="0">
	<tr>
		<td style="width:350px;position:relative">
			<i class="fa-solid fa-magnifying-glass searchicon"></i>
			<span class="tm" onclick="clearSearch('searchItems')"></span>
			<input id="itemsearch" type="text" class="form-control" style="padding-left:35px;padding-right:57px" placeholder="Search Item" autocomplete="no">
		</td>
		<td style="width:150px"></td>
		<td style="text-align:right">
			<!--button id="fetchbegbtn" <?php echo $fetch_btn; ?> class="btn btn-primary" onclick="fetchBeginnings()"><i class="fa-solid fa-arrows-retweet"></i> FETCH ALL BEGINNINGS</button-->
			<button id="posttosummarybtn" class="btn btn-success" <?php echo $summary_btn; ?> onclick="postToSummary('<?php echo $file_name; ?>')"><i class="fa-solid fa-bring-forward"></i>&nbsp;&nbsp;POST SUMMARY</button>&nbsp;
			<button id="unlockbutton" class="btn btn-info" <?php echo $unlock_btn; ?> onclick="calculateSummary('<?php echo $file_name; ?>')"><i class="fa-solid fa-calculator"></i>&nbsp;&nbsp;CALCULATE</button>
		</td>
	</tr>
</table>
<div class="Results"></div>
<script>
$(function()
{
	$('#itemsearch').keyup(function()
	{
		var search = $('#itemsearch').val();
		$.post("./includes/rm_summary_data.php", { search: search },
		function(data) {
			$("#contentdata").html(data);
		});
	});
	$(document).keydown(function(event)
	{
		if(event.keyCode == 120)
		{
			app_confirm("Delete Summary","Are you sure to clear all rawmats summary?",'warning',"delete_rmsumari","","red")
	    } 
	});

});
function delete_rmsumariYes()
{
	var mode = 'deletermsummari';			
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

function postToSummary(file_name)
{
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

function calculateSummary()
{
	var mode = 'calculatermsummary';
	rms_reloaderOn('Calculating...');
	setTimeout(function()
	{
		$.post("./actions/calculate_summary_process.php", { mode: mode },
		function(data) {
			$(".Results").html(data);
			rms_reloaderOff();
			$('#' + sessionStorage.navcount).click();
			$("#" + sessionStorage.navcount).click();
		});
	},1000);
}
function fetchBeginnings()
{
	$('#posttosummarybtn,#fetchbegbtn').attr("disabled", true);
	$('#fetchbegbtn').html('<i class="fa-solid fa-bring-forward"></i>&nbsp;&nbsp;Fetching Beginnings&nbsp;&nbsp;<i class="fa fa-spinner fa-spin"></i>');
	var mode = 'fetchbeginnings';
	var shift = $('shift').val();
	var table = 'store_rm_pcount_data';
	setTimeout(function()
	{
		$.post("./actions/fetch_beginnings_process.php", { mode: mode, table: table, shift: shift },
		function(data) {
			$(".Results").html(data);
			// $('#' + sessionStorage.navcount).click();
		});
	},1000);
}
</script>