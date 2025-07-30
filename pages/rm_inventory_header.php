<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');

$file_name = $_POST['pagename'];
$title = strtoupper($file_name);
$selectedDate = $_SESSION['session_date'];

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
<style>
</style>
<table style="width: 100%;border-collapse:collapse;white-space:nowrap" cellpadding="0" cellspacing="0">
	<tr>
		<td style="width:50px"><button type="button" style="width:100%" class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Date From</button></td>
		<td style="width:100px"><input type="text" id="dateselectfrom" class="form-control input-sm" value="<?php echo $selectedDate?>"></td>
		<td style="width:50px">
		<td style="width:50px"><button type="button" style="width:100%" class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Date To</button></td>
		<td style="width:100px"><input type="text" id="dateselectto" class="form-control input-sm" value="<?php echo $selectedDate?>"></td>
		<td style="width:50px">
		<td style="width:150px"><button type="button" style="width:100%" class="btn btn-success btn-sm" onclick="selectedDate()"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search</button></td>
		<td style="width:365px">
	</tr>
</table>
<div id="resultme"></div>
<div class="Results"></div>
<script>
$(function(){
	$('#dateselectfrom').click(function(){
		$('#dateselectfrom').datepicker({ dateFormat: 'yy-mm-dd' });
	});
	$('#dateselectto').click(function(){
		$('#dateselectto').datepicker({ dateFormat: 'yy-mm-dd' });
	});
});
function selectedDate(){
	var dateselectfrom = $('#dateselectfrom').val();
	var dateselectto = $('#dateselectto').val();
	
	if(dateselectfrom > dateselectto && dateselectto != ''){
		swal('Date Invalid','Date From is greater than Date To','warning');
		return false;
	}
	if(dateselectfrom == ''){
		swal('Date Invalid','Please select date from','warning');
		return false;
	}
	if(dateselectto == ''){
		swal('Date Invalid','Please select date to','warning');
		return false;
	}

	rms_reloaderOn('Process data...');
	$.post("../includes/rm_inventory_data.php", { dateselectto: dateselectto, dateselectfrom: dateselectfrom },
	function(data) {
		$("#upper").html(data);
		rms_reloaderOff();
	});

	
}

</script>
