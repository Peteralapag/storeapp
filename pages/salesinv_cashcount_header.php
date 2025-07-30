<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$file_name = $_POST['pagename'];
$title = strtoupper($file_name);
$table = "store_".$file_name."_data";
$branch = $functions->AppBranch();
if(isset($_SESSION['appstore_year'])) { $year = $_SESSION['appstore_year']; } else { $year = date("Y"); }
if(isset($_SESSION['appstore_month'])) { $months = $_SESSION['appstore_month']; } else { $months = date("F"); }
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
		<td style="width:0.5em; height: 26px;" class="branch-info"></td>
		<td style="width:90px; height: 26px;" class="branch-info">
			<input id="year" type="number" style="text-align:center" class="form-control" placeholder="Year" value="<?php echo $year; ?>">
		</td>
		<td style="width:10px; height: 26px;" class="branch-info"></td>
		<td style="width:160px; height: 26px;" class="branch-info">
			<select id="monthname" class="form-control" onchange="selectMonth(this.value),'<?php echo $year; ?>'"><?php echo $functions->GetMonths($months)?></select>
		</td>
		<td style="width:0.5em; height: 26px;" class="branch-info"></td>
		<td style="width:150px; height: 26px;" class="branch-info">
			<button class="btn btn-primary" onclick="FecthProdItems()"><i class="fa-solid fa-download"></i>&nbsp;&nbsp;&nbsp;Fetch Items</button>
		</td>
		
	</tr>
</table>
<div id="pdata"></div>
<script>
function deleteMe(){
	var yEar = $('#year').val();
	var mOnth = $('#monthname').val();
	app_confirm("Clear Production Report","Are you sure to clear "+mOnth+", "+yEar+"?",'warning',"delete_sumari","","red")
}
function delete_sumariYes()
{
	var mode = 'deleteprod';			
	var branch = '<?php echo $branch; ?>';
	var yEar = $('#year').val();
	var mOnth = $('#monthname').val();
	var mnth;
	if(mOnth == "January"){ mnth = '1'; }
	else if(mOnth == "February") { mnth = '2'; }
	else if(mOnth == "March") { mnth = '3'; }
	else if(mOnth == "April") { mnth = '4'; }
	else if(mOnth == "May") { mnth = '5'; }
	else if(mOnth == "June") { mnth = '6'; }
	else if(mOnth == "July") { mnth = '7'; }
	else if(mOnth == "August") { mnth = '8'; }
	else if(mOnth == "September") { mnth = '9'; }
	else if(mOnth == "October") { mnth = '10'; }
	else if(mOnth == "November") { mnth = '11'; }
	else if(mOnth == "December") { mnth = '12'; }
	else{
		mnth = '0';
	}
		
	rms_reloaderOn('Deleting of month of '+mOnth+' Production Report...');
	setTimeout(function()
	{
		$.post("./actions/actions.php", { mode: mode, branch: branch, yEar: yEar, mOnth: mnth },
		function(data) {
			$(".Results").html(data);
			rms_reloaderOff();
			$('#' + sessionStorage.navcount).click();
		});
	},1000);
}

function CalculateProduction()
{
	var mode = 'calculateproduction';
	var branch = '<?php echo $branch ?>';
	var monthname = $('#monthname').val();
	var year = $('#year').val();
	rms_reloaderOn('Loading...');
	setTimeout(function()
	{
		$.post("actions/production_report_process.php", { mode: mode, monthname: monthname, year: year },
		function(data) {
			$('#prodtable').html(data);
			load_production(branch);
			rms_reloaderOff();
		});	
	},1000);
}
function FecthProdItems()
{
	var branch = '<?php echo $branch ?>';
	rms_reloaderOn();
	var mode = 'fetchproditems';
	var year = $('#year').val();
	var monthname = $('#monthname').val();
	setTimeout(function()
	{
		$.post("./actions/production_report_process.php", { mode: mode, monthname: monthname, year: year },
		function(data) {
			$('#pdata').html(data);
			rms_reloaderOff();
			load_production(branch);
		});
	},1000);
}
function FetchData()
{
	var branch = '<?php echo $branch ?>';
	rms_reloaderOn();
	var mode = 'fetchproddata';
	var year = $('#year').val();
	var monthname = $('#monthname').val();
	setTimeout(function()
	{
		$.post("./actions/production_report_process.php", { mode: mode, monthname: monthname, year: year },
		function(data) {
			$('#pdata').html(data);
			rms_reloaderOff();
			load_production(branch);
		});
	},1000);
}
$(function()
{
	$('#year').change(function()
	{
		var value = $('#year').val();
		var session_name = 'appstore_year';
		$.post("./actions/set_session_process.php", { params: session_name, value: value },
		function(data) {});
	});
});
function selectMonth(month,year)
{
	var value = month;
	var session_name = 'appstore_month';
	$.post("./actions/set_session_process.php", { params: session_name, value: value },
	function(data) {
		load_production();
	});
}
function load_production()
{
//	rms_reloader('Loading...');
	$.post("includes/production_data.php", { },
	function(data) {
		$('#contentdata').html(data);
//		rms_reloaderOff();
	});	
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