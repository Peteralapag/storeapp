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
		
		<td style="width:0.5em" class="branch-info"></td>
		<td style="width:90px; !important" class="branch-info">
			<input id="year" type="number" style="text-align:center" class="form-control" placeholder="Year" value="<?php echo $year; ?>">
		</td>
		<td style="width:10px" class="branch-info"></td>
		<td style="width:160px; !important" class="branch-info">
			<select id="monthname" class="form-control" onchange="selectMonth(this.value),'<?php echo $year; ?>'"><?php echo $functions->GetMonths($months)?></select>
		</td>
		<td style="width:0.5em" class="branch-info"></td>
		<td style="width:150px" class="branch-info">
			<button class="btn btn-primary" onclick="FecthProdItems()"><i class="fa-solid fa-download"></i>&nbsp;&nbsp;&nbsp;Go</button>
		</td>
		<td style="width:0.5em" class="branch-info"></td>
		<td style="text-align:right">
			
		</td>
	</tr>
</table>
<div id="pdata"></div>
<script>
function FecthProdItems()
{

	var branch = '<?php echo $branch ?>';
	rms_reloaderOn();
	var yearname = $('#year').val();
	var monthname = $('#monthname').val();

	rms_reloaderOn('Process data...');
	$.post("../includes/sales_breakdown2_data.php", { monthname: monthname, yearname: yearname, branch: branch},
	function(data) {
		$("#contentdata").html(data);
		rms_reloaderOff();
	});

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
function load_production()
{
//	rms_reloader('Loading...');
	$.post("includes/sales_breakdown2_data.php", { },
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