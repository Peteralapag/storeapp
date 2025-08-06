<?php
session_start();
$server_name = $_POST['servername'];
$server_ip = $_POST['server'];
$remote_url = $_POST['remote_url'];
$_SESSION['server'] = $server_name;
$data = array();
array_push($data, [
	'server_name'=>$server_name,
	'server_ip'=>$server_ip,
	'remote_url'=>$remote_url
]);
$array = json_encode($data);
$myfile = fopen("../.file/server.rms", "w") or die("Unable to open file!");
$txt = $array;
fwrite($myfile, $txt);
fclose($myfile);
?>
<script>
	location.reload();
	rms_reloaderOff();
</script>