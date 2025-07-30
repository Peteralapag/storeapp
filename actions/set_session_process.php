<?PHP
session_start();
if(isset($_POST['params']))
{
	$session_value = $_POST['value'];
	$session_name = $_POST['params'];
	$_SESSION[$session_name] = $session_value;
}
if(isset($_POST['submenu'])) {
	$_SESSION['menuname'] = $_POST['menuname'];
	$_SESSION['pagename'] = $_POST['pagename'];
}

	print_r('
		<script>
			sessionStorage.setItem("'.$session_name.'", '.$session_value.');
		</script>
	');

function console($params)
{
	print_r('
		<script>
			console.log("'.$params.'");
		</script>
	');
}
?>