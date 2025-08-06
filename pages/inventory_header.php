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
		<td style="width:50px"><button type="button" style="width:100%" class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Date From</button></td>
		<td style="width:100px"><input type="text" id="dateselectfrom" class="form-control input-sm" value="<?php echo $selectedDate?>"></td>
		<td style="width:10px">
		<td style="width:50px"><button type="button" style="width:100%" class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;Date To</button></td>
		<td style="width:100px"><input type="text" id="dateselectto" class="form-control input-sm" value="<?php echo $selectedDate?>"></td>
		<td style="width:10px">
		<td style="width:150px"><button type="button" style="width:100%" class="btn btn-success btn-sm" onclick="selectedDate()"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search Date</button></td>
		<td style="width:50px">
		<td style="width:350px;position:relative">
			<i class="fa-solid fa-magnifying-glass searchicon"></i>
			<span class="tm" onclick="clearSearch('searchItems')"></span>
			<input id="itemsearch" type="text" class="form-control" style="padding-left:35px;padding-right:57px" placeholder="Search Item">
		</td>
		<td style="width:380px"></td>
		<td style="width:320px">
			<select id="inventoryOptionPage" class="form-control form-control-sm">
				<option value="inventory">Inventory Report</option>
				<option value="inventoryvsdeposited">Inventory Sales VS Deposited (Cashcount)</option>
			</select>

		</td>
	</tr>
</table>
<div id="resultme"></div>
<div class="Results"></div>
<script>
$(function(){
	$('#inventoryOptionPage').on('change', function() {
		var value = $(this).val();
		if(value == 'inventoryvsdeposited'){
			set_function('Store Discount','discount');
		}
		else
		{
			set_function('Inventory Report','inventory');
		}
	});
	$('#dateselectfrom').click(function(){
		$('#dateselectfrom').datepicker({ dateFormat: 'yy-mm-dd' });
	});
	$('#dateselectto').click(function(){
		$('#dateselectto').datepicker({ dateFormat: 'yy-mm-dd' });
	});
	$('#itemsearch').keyup(function()
	{
		var dateselectfrom = $('#dateselectfrom').val();
		var dateselectto = $('#dateselectto').val();
		var search = $('#itemsearch').val();
		rms_reloaderOn('Process data...');
		
		$.post("./includes/inventory_data.php", { dateselectfrom: dateselectfrom, dateselectto:dateselectto, search: search },
		function(data) {
			$("#contentdata").html(data);
			rms_reloaderOff();
		});
	});

});
function selectedDate(){
	var dateselectfrom = $('#dateselectfrom').val();
	var dateselectto = $('#dateselectto').val();
	$('#itemsearch').val('');
	
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
	$.post("../includes/inventory_data.php", { dateselectto: dateselectto, dateselectfrom: dateselectfrom },
	function(data) {
		$("#upper").html(data);
		rms_reloaderOff();
	});

	
}

</script>
