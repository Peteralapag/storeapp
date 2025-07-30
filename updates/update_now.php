<?php
include '../includes/config.php';
?>
<div id="updateshell">
	<div id="downloading"></div>
	<div id="installing"></div>
	<div id="updatefinish"></div>
</div>
<div id="finishedbtn" style="display:none;text-align:center;margin-top:20px;border-top:5px solid dodgerblue;padding:10px;">
	<button class="btn btn-success btn-small" onclick="reloadApps()">Close & Reload</button>
</div>
<div id="resultas"></div>
<?php 
	$file_ko = $_GET['fileko']; 
	$local_ko = $_GET['localko']; 
	$date_ko = $_GET['dateko']; 
?>
<script>
$(function()
{
	var fileko = '<?php echo $file_ko ?>';
	var localko = '<?php echo $local_ko ?>';
	var dateko = '<?php echo $date_ko ?>';

	$('#downloading').html('<p><i class="fa fa-spinner fa-spin" style="font-size:18px;margin-right:10px"></i> Downloading updates...</p>');
	$.post("../autoupdate/process_update.php", { fileko: fileko, localko: localko, dateko: dateko },
	function(data) {
		$('#resultas').html(data);
	});
});
function installUpdates(fileko,localko,dateko)
{
	$.post("../autoupdate/install_updates.php", { fileko: fileko, localko: localko, dateko: dateko },
	function(data) {
		$('#resultas').html(data);
	});
}
function reloadApps()
{
	closeDialoque('updateapps');
	window.location.href = '../log_awt.php';
}
</script>
