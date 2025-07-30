<?PHP
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');
if (session_status() == PHP_SESSION_NONE) { session_start(); }
include 'db_config.php';
define('DB_HOST', $dbhost);
define('DB_USER', $dbuser);
define('DB_PASSWORD', $dbpass);
define('DB_NAME', $dbname);
require 'class/dropdowns.class.php';
require 'class/encrypted_password_class.php';
require 'class/functions.class.php';
require 'class/summary.class.php';
$summary = new TheSummary;
$functions = new TheFunctions;
$dropdown = new DropDowns;
$pass = new Password;
/* ################# STORE SETTINGS ################################ */
$dbe = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$qSettings = "SELECT * FROM store_settings WHERE id=1";
$res = mysqli_query($dbe, $qSettings);    
if ($res->num_rows > 0) 
{ 
    while($SETTROW = mysqli_fetch_array($res))  
	{
		define("APP_NAME", $SETTROW['app_name']);
		define("COMPANY", $SETTROW['company']);
		define("APP_LOGO", $SETTROW['app_logo']);
		define("VERSION_TEXT", $SETTROW['version_text']);
		// define("VERSION_NUMBER", $SETTROW['version_number']);
		if(isset( $SETTROW['theme_color']))
		{
			define("THEME_COLOR", $SETTROW['theme_color']);
		} else {
			define("THEME_COLOR", 0);
		}
	}
} else {
	echo "No Data";
}
if(THEME_COLOR == 0) {
	define("THEME", 'form_class_green');
} else if(THEME_COLOR == 1) {
	define("THEME", 'form_class_orange');
} else {
	define("THEME", 'form_class_green');
}

$url_host = '120.28.196.113';
/* ########### READ VERSION ################### */



$exists = $functions->checkchargestypeexist($dbe);
if (!$exists) {
    $functions->addChargesColumns($dbe);    
    $functions->insertNavigationMenus($dbe);
    
    $initialTables = [
	    'store_brrr_category',
	    'store_brrr_cogs_data',
	    'store_brrr_expense_data',
	    'store_brrr_expense_ho_data',
	    'store_brrr_overhead_data',
	    'store_brrr_pagibig_table',
	    'store_brrr_philhealth_table',
	    'store_brrr_sss_table',
	    'store_brrr_summary_data',
	    'store_brrr_wage_table'
	];
	
	foreach ($initialTables as $table) {
	    if (!$functions->checkIfTableExists($dbe, $table)) {
	        $path = "updates/database_table/{$table}.sql";
	        $functions->importSQLFile($dbe, $path);
	    } else {

	    }
	}
	
}

/*
if (!$functions->checkIfTableExists($dbe, 'tbl_employees_ho'))
{
	$functions->importSQLFile($dbe, 'updates/database_table/tbl_employees_ho.sql');

}

*/




/* ############# NEW UPDATE BY PSA ################### */

/*
if (!$functions->checkTableExists('store_foodpanda_data', $dbe)) {

	include 'updatesfoodpanda.php';
    
	$functions->createTableIfNotExistsrmlocker('store_foodpanda_data', $createTableFoodPanda, $dbe);
	$query = "DROP TABLE IF EXISTS store_navigation";
	        if ($dbe->query($query)) {} else {}
	$functions->createTableIfNotExistsrmlocker('store_navigation', $createTableNavigation, $dbe);
	$functions->insertRecords($insertThisNavigationDataToTable, $dbe);

}
*/

/*
if (!$functions->checkTableExists('store_update_detection', $dbe)) {
	include 'updatestoreappsitems.php';
	echo '<script>rms_reloaderOn("Copying Updates...");</script>';
	
	$functions->dropTables($tablesToDrop, $dbe);
	$functions->createTables($tableDefinitions, $dbe);
	$functions->insertRecords($insertThisItemsDataToTable, $dbe);
	
	echo '<script>rms_reloaderOff();</script>';
  
}

*/





