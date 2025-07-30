<?PHP
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
$username = $_POST['username'];
$password = $_POST['password'];	
$username = mysqli_real_escape_string($db, $username);
/* ######################### ENCRYPT PASSWORD FOR SECURITY ####################### */

$username = $username;
$array = explode(" ", $username);
$first_name = $array[0];
$last_name = $array[1];
$password = $pass->encryptedPassword($password,$db);

$sqlLogin = "SELECT * FROM tbl_system_user WHERE firstname='$first_name' AND lastname='$last_name' AND password='$password' AND role='Administrator' AND level='100'";
$result = mysqli_query($db, $sqlLogin);    
if ( $result->num_rows > 0 ) 
{ 
	echo '
		<script>
			openShifting();
		</script>
	';
} else {
	echo '
		<script>
			swal("Access Denied","Please contact the System Administrator if you need assistance.", "warning");
		</script>
	';
}

