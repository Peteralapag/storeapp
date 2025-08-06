<?PHP
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
if(isset($_POST['mode']))
{
	$user_app = "APPSTORE";
	$mode = $_POST['mode'];
	$username = $_POST['username'];
	$shifting = $_POST['shifting'];
	if($mode=="c10cc6b684e1417e8ffa924de1e58373")
	{
		$username = $_POST['username'];
		$_password = $_POST['password'];	
		$username = mysqli_real_escape_string($db, $username);
		/* ######################### ENCRYPT PASSWORD FOR SECURITY ####################### */
		
		$password = $pass->encryptedPassword($_password,$db);
		
		/* ######################### ENCRYPT PASSWORD FOR SECURITY ####################### */
		$sqlLogin = "SELECT * FROM tbl_system_user WHERE username='$username' AND password='$password'";
		$result = mysqli_query($db, $sqlLogin);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($listrow = mysqli_fetch_array($result))  
			{
				$idcode = $listrow['idcode'];	
				$void = $listrow['void_access'];
				$cluster = $listrow['cluster'];
				$branch = $listrow['branch'];
				$user_level = $listrow['level'];
				$user_role = $listrow['role'];
				$employee = $listrow['firstname']." ".$listrow['lastname'];			
			}
			create_config($shifting);
			if($user_role != 'Administrator' AND $user_level < 80)
			{
				$checkPolicy = checkPolicy($idcode, $user_app,$db);	
				if($checkPolicy == 0)
				{
					$cmd = '';
					$cmd .='				
						<script>
							app_alert("System Message","You have no access to this application","warning","Ok","","");
						</script>
					';
					print_r($cmd);
					exit();
				}
			} 
			if($user_role == 'Administrator' AND $user_level >= 80)
			{
				$queryAdmin = "SELECT * FROM store_config WHERE id=1";
				$adminResult = mysqli_query($db, $queryAdmin);    
			    if ( $adminResult->num_rows > 0 ) 
			    {
				    while($listrow = mysqli_fetch_array($result))  
					{
						$branch = $listrow['app_branch'];						
					}
			    }
			}			
			if($void == 0)
			{
				

			
				$_SESSION['appstore_user'] = $username;
				$_SESSION['appstore_branch'] = $branch;
				$_SESSION['appstore_cluster'] = $cluster;
				$_SESSION['appstore_appnameuser'] = $employee;
				$_SESSION['appstore_userlevel'] = $user_level;
				$_SESSION['appstore_userrole'] = $user_role;			
				$_SESSION['appstore_useridcode'] = $idcode;
				$_SESSION['appstore_shifting'] = $shifting;
				$_SESSION['session_date'] = date("Y-m-d");
				$_SESSION['session_shift'] = "FIRST SHIFT";	
				$_SESSION['session_sidebar'] = 'fgts';
				$_SESSION['session_transfer'] = 'TRANSFER IN';
				$_SESSION['OFFLINE_MODE'] = 0;
				$_SESSION['appstore_maintenance'] = maintenanceStatus();
				
				$cmd = '';
				$cmd .='				
					<script>
						sessionStorage.setItem("OFFLINE_MODE", 0);
						app_alert("Login Success","You have successfuly Signing-In","success","Ok","","yes");
					</script>
				';
				print_r($cmd);
				exit();			
			} else {
				$cmd = '';
				$cmd .='				
					<script>
						app_alert("System Message","Your account is locked, Please contact the sytem Administrator","warning","","no");
					</script>
				';
				print_r($cmd);
				exit();
			}
	    } 
	    else 
	    {
			$cmd = '';
			$cmd .='				
				<script>
					app_alert("Login Error","Invalid Username or Password","warning","Ok","username","no");
				</script>
			';
			print_r($cmd);
			exit();
	    }
	} else {
		header("Location: /");
	}
}
/* ############################################### THE FUNCTIONS ######################################## */	
function create_config($shifting)
{
	$myfile = fopen("../.file/shifting.conf", "w") or die("Unable to open file!");
	$txt = $shifting;
	fwrite($myfile, $txt);
	fclose($myfile);
}
function checkPolicy($idcode,$user_app,$db)
{
	$checkPolicy = "SELECT * FROM tbl_system_privilege WHERE idcode='$idcode' AND applications='$user_app'";
	$pRes = mysqli_query($db, $checkPolicy);    
    if ( $pRes->num_rows > 0 ) 
    {
    	return 1;
	} else {
		return 0;
	}
}

function maintenanceStatus()
{
	$dbm = new mysqli('120.28.196.113', 'rbsapps', 'admin@rbs.com', 'rosebakeshop_data', 13306);
	$data = 0;
    $query = "SELECT status FROM store_update_detection WHERE id=1 AND status=1";
    $result = $dbm->query($query);
    if ($result) {

        while ($row = $result->fetch_assoc()) {
            $data = $row['status'];
        }
        return $data;
    } else {
        return $data;
    }
    $result->free();
	$dbm->close();
}


?>