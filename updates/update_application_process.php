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

	$remote_file = $_POST['remote_file'];
	$local_file = $_POST['local_file'];
 	$file_date = date("Y-m-d");

?>
<script>
$(function()
{
	var fileko = '<?php echo $remote_file; ?>';
	var localko = '<?php echo $local_file; ?>';
	var dateko = '<?php echo $file_date; ?>';

	$('#downloading').html('<p><i class="fa fa-spinner fa-spin" style="font-size:18px;margin-right:10px"></i> Downloading updates...</p>');
	$.post("../updates/process_update.php", { fileko: fileko, localko: localko, dateko: dateko },
	function(data) {
		$('#resultas').html(data);
	});
});
function installUpdates(fileko,localko,dateko)
{
	setTimeout(function()
	{
		$.post("../updates/install_updates.php", { fileko: fileko, localko: localko, dateko: dateko },
		function(data) {
			$('#resultas').html(data);
		});
	},1000);
}
function reloadApps()
{
	closeDialoque('updateapps');
	location.reload(true);
	window.location.href = '../log_awt.php';
}
</script>
