<?php
require '../init.php'; 
require '../includes/config.php'; 
$lines = file('../.file/version.rms');
foreach($lines as $line) {
    define("VERSION_NUMBER", $line);
}
$status = $_SESSION['OFFLINE_MODE'];
if($status == 0)
{
	$arrays = file_get_contents($remote);
	$datas = json_decode($arrays, true);
	$arays = $datas;
	for ($i = 0; $i < count($arays); $i++) {
		$remote_update_no = ($arays[$i]['updateno']);
		$remote_lastdate = ($arays[$i]['lastdate']);
	}
	/* 	LOCAL  */
	$array = file_get_contents("../updates/data/update.json", "r");
	$data = json_decode($array, true);
	$aray = $data;
	for ($t = 0; $t < count($aray); $t++) {	
		$local_update_no = ($aray[$t]['updateno']);
	}
	if($local_update_no < $remote_update_no)
	{
?>
<div id="goupdate" style="text-align:center">
	<h4>Hey <?php echo ucfirst($functions->GetSession('encoder')); ?></h4>
	<p>We have new update for this application. Please hit Update Now button to install the Patch No. <?php echo $remote_update_no; ?></p>
	<hr>
	<p>
		<button class="btn btn-primary" onclick="get_update('<?php echo $remote_update_no; ?>','<?php echo $local_update_no; ?>')">Update Now</button>
		<!-- button class="btn btn-warning" onClick="updateLater();">Update Later</button -->
	</p>
</div>
<?php } else { ?>
<div id="noupdate" style="text-align:center">
	<h3 style='text-align:center'>Your system is up to date</h3>
	<p style='text-align:center'>Current Application Version: <strong><?php echo VERSION_NUMBER; ?></strong></p>
	<p style='text-align:center'>Patch Version No.: <strong><?php echo $local_update_no; ?></strong></p>
	<hr>
	<p><button class="btn btn-primary" onClick="closeDialoque('updateapp');">Close</button></p>
</div>
<?php }  }  else { ?>
<div style="text-align:center">
	<span><i class="fa-solid fa-circle icon-color-red"></i>&nbsp;&nbsp;&nbsp;SERVER IS OFFLINE</span>
	<hr>
	<p><button class="btn btn-primary" onClick="closeDialoque('updateapp');">Close</button></p>	
</div>
<?php } ?>
<script>
function updateLater()
{
	sessionStorage.setItem("updatelater", 1);	
	closeDialoque('updateapp');
}
function get_update(remotefile,localfile)
{
	$.post("./updates/update_application_process.php", { remote_file: remotefile, local_file: localfile },
	function(data) {
		$('#updateapp_page').html(data);
	});
}
</script>