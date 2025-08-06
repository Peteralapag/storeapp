<?php
session_start();
if($_SESSION['OFFLINE_MODE'] == 0)
{
	$cbox = '';
}
else if($_SESSION['OFFLINE_MODE'] == 1)
{
	$cbox = 'checked="checked"';
}
?>
<p><strong>Note:</strong><br> If you wish to use this feature. Your encoding will be more faster and less lags.</p>
<p>However, some functions will be disabled like</p>
<ol>
	<li>1. Auto Dectect Update</li>
	<li>2. Transfer Out to Branch</li>
	<li>3. Submit to server</li>
	<li>4. Update Database</li>
</ol>
<p>Please turn back on if you wish to submit to server and recieve automatic updates and more</p>
<hr>
<div>
	<label class="switch">
		<input id="myswtich" <?php echo $cbox; ?> type="checkbox" onchange="executeOfflineMode()">
		<span class="slider round"></span>
	</label>
	<br>
</div>
<div id="statusresults"></div>
<script>
$('#')
function executeOfflineMode()
{
	if($('#myswtich').is(":checked") == true)
	{
		var params = 'OFFLINE_MODE';
		console.log(params);
		var value = 1;
		$.post("./actions/set_session_process.php", { params: params, value: value },
		function(data) {
			$('#statusresults').html(data);
			$("#"+sessionStorage.navcount).click();
		});		
	} else {
		var params = 'OFFLINE_MODE';
		var value = 0;
		$.post("./actions/set_session_process.php", { params: params, value: value },
		function(data) {
			$('#statusresults').html(data);
			$("#"+sessionStorage.navcount).click();
		});
	}
}
</script>