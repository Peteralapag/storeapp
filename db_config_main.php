<?PHP
ini_set('display_errors',0);
$server = $functions->GetOnlineServer('server_ip');
/* ################### MYSQL DATABASE INFORMATION ########################### */
$conhost = $server.":13306";
$conuser = "rbsapps";
$conpass = "admin@rbs.com";
//$conname = "rosebakeshop_data";
$conname = "rosebakeshop_deyta";

/* ################### MYSQL DATABASE INFORMATION ########################### */
define('CON_HOST', $conhost);
define('CON_USER', $conuser);
define('CON_PASSWORD', $conpass);
define('CON_NAME', $conname);
/* ################### MYSQL DATABASE INFORMATION ########################### */
