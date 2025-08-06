<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');

$file_name = $_POST['pagename'];
$title = strtoupper($file_name);

$table = "store_summary_data";
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
		<td style="width:250px;">
			<input type="text" class="form-control" value="<?php echo $functions->AppBranch(); ?>">
		</td>
		<td style="width:0.5em"></td>
		<td style="width:150px">
			<input id="date" type="text" class="form-control" value="<?php echo $functions->GetSession('branchdate'); ?>">
		</td>
		<td style="width:0.5em"></td>
		<td style="width:150px">
			<input id="shift" type="text" class="form-control" value="<?php echo $functions->GetSession('shift'); ?>">
		</td>
		<td style="width:0.5em"></td>
		<td style="width:150px">
		</td>
		<td style="text-align:right">
			<button class="btn btn-info" onclick="ronanReload()"><i class="fa-solid fa-arrows-retweet"></i> Reload</button>
			<!-- button id="fetchbegbtn" <?php echo $fetch_btn; ?> class="btn btn-primary" onclick="fetchBeginnings()"><i class="fa-solid fa-arrows-retweet"></i> FETCH ALL BEGINNINGS</button>
			<button id="posttosummarybtn" class="btn btn-success" <?php echo $summary_btn; ?> onclick="postSummary('<?php echo $file_name; ?>')"><i class="fa-solid fa-bring-forward"></i>&nbsp;&nbsp;POST SUMMARY</button>&nbsp;
			<button id="unlockbutton" class="btn btn-primary" <?php echo $unlock_btn; ?> onclick="unLock('<?php echo $file_name; ?>')"><i class="fa-solid fa-lock-open"></i>&nbsp;&nbsp;UNLOCK POST</button -->
		</td>
	</tr>
</table>
<div class="Results"></div>
<script>
function postSummary(file_name)
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
function postToSummary(params)
{
}
</script>