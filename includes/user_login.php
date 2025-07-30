<?PHP
require_once 'init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$shiftting = $functions->GetShifting();
$user = $functions->GetUsernames($db);


$lines = file('.file/version.rms');
foreach($lines as $line) {
    define("VERSION_NUMBER", $line);
}
define("VERSION", VERSION_TEXT."".COMPANY." ".VERSION_NUMBER);
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo APP_NAME." - ".COMPANY; ?> </title>
<link rel="stylesheet" href="styles/fa/css/all.css">
<link rel="stylesheet" href="styles/bootstrap.min.css">
<link rel="stylesheet" href="styles/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="styles/styles.css">
<link rel="stylesheet" href="styles/login_style.css">
<script src="scripts/jquery.min.js"></script>
<script src="scripts/bootstrap.min.js"></script>
<link rel="stylesheet" href="styles/jquery-ui.css">
<link rel="stylesheet" href="modules/modals/modals.css">
<script src="scripts/sweetalert.min.js"></script>
</head>
<body>
<style>
</style>
<div class="login-container">
	<div class="app-title">
		<p>Storeapp Reporter</p>
	</div>
	<div class="form-wrapper">
		<div class="login-logo">
			
			<img alt="" src="../images/company_logo.png"></div>
		<div class="login-form">
			<div class="input-wrapper">
				<label>Username</label>
				<div class="form-outline mb-3">
		            <select id="u_name" class="form-control input-lg">
		            	<?php echo $user?>
		            </select>
		            <small id="userName" class="form-text text-muted">We'll never share your username with anyone else.</small>
				</div>
				<label style="margin-top:10px;">Password</label>
				<div class="form-outline mb-3" style="position:relative">
					<input type="password" id="p_word" class="form-control input-lg" placeholder="Enter Password" autocomplete="nope" aria-haspopup="false">
					<span class="show-pass"><i  id="showpassword" class="fa-solid fa-eye-slash"></i></span>
					<small class="form-text text-muted">We'll never share your password with anyone else.</small>
	        	</div>
	        	<label style="margin-top:10px;">Store Shifting</label>
				<div class="form-outline mb-3">				
					<table style="width: 100%">
						<tr>
							<td>
								<select id="storeshift" class="form-control input-lg" disabled>							
									<?php echo $dropdown->GetStoreShifting($shiftting); ?>
								</select>
							</td>
							<td style="width:10px">&nbsp;</td>
							<td>
								<button type="button" class="btn btn-warning btn-lg btn-locked"><i id="btnlocked" class="fa-solid fa-lock"></i></button>
							</td>
						</tr>
					</table>				
					
	        	</div>
				<div class="button-wrapper">						
					<button id="dloginbtn" class="btn btn-info btn-lg btn-block" onclick="pushLogin()">Login</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="page-loader-bd">
	<div class="page-loader"><i class="fa fa-spinner fa-spin"></i></div>
</div>
<div class="login"></div>
<div class="login-footer">
<?php echo VERSION; ?>
</div>
<?php include 'modules/loader/loader.php'; ?><?php include 'modules/modals/modals.php'; ?>
<script src="scripts/default_scripts.js"></script>
<script src="scripts/user_login.js"></script>
<script src="modules/loader/loader.js"></script>
<script src="modules/modals/modals.js"></script>
<script src="scripts/jquery-ui.js"></script>
<script src="scripts/jquery.dataTables.min.js"></script>
<script src="scripts/dataTables.bootstrap.min.js"></script>
<script src="scripts/src.js"></script>
</body>

</html>
<script>
</script>

