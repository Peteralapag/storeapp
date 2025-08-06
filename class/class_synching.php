<?PHP
//session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$time_out = 50;
$server_ip = '120.28.196.113';

$server_status = (bool)@fsockopen($server_ip, '80', $err_no, $err_str, $time_out);
if($server_status)
{
	$server = 1;
	$_SESSION['IS_ONLINE'] = 1;
} else { 
	$server = 0; 
	$_SESSION['IS_ONLINE'] = 0;
}
console($server);
setServerStatus($server);

function setServerStatus($server)
{
	print_r('
		<script>
			sessionStorage.setItem("IS_ONLINE", '.$server.');
		</script>
	');
}
function console($params)
{
	print_r('
		<script>
//			console.log("'.$params.'")	
		</script>
	');
}